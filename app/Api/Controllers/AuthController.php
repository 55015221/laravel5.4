<?php
/**
 * 本类可以作为认证类的基类，用于API的多用户认证
 *
 * 使用方法：
 * 在config/auth.php 中配置相应的guard
 * 继承此类，覆盖$guard，$username, $password, $maxAttempts, $decayMinutes属性
 * 覆盖create方法，使用相应的Model创建用户。
 *
 * 注意：
 * 用户模型需要实现JWTSubject契约
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */


namespace App\Api\Controllers;

use App\Api\Requests\LoginRequest;
use App\Api\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{

    use AuthenticatesUsers;

    protected $guard = 'user';


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function password()
    {
        return 'password';
    }

    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * 组装用户名登录凭证
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), $this->password());
    }

    /**
     * 注册用户
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->create($request->all());

        event(new Registered($user));

        $token = $this->guard()->login($user);

        return $this->responseData(compact('token', 'user'));
    }

    /**
     * 创建用户
     *
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
        $data[$this->password()] = bcrypt($data[$this->password()]);

        return User::create($data);
    }

    /**
     * 用户登录
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        //检测尝试次数，如果超过则锁定
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //登录
        $token = $this->attemptLogin($request);

        //登录失败，则记录尝试次数
        if (!$token) {
            $this->incrementLoginAttempts($request);
            //返回登录失败响应
            return $this->sendFailedLoginResponse($request);
        }

        /**
         * @var $user User
         */
        $user = $this->guard()->user();
        if ($user->status == 2) {
            return $this->sendUserDisabledResponse($request);
        }

        return $this->sendLoginResponse($request, $token);
    }

    /**
     * 登录
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request)
        );
    }

    /**
     * 登录失败的响应
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        $retriesLeft = $this->limiter()->retriesLeft($this->throttleKey($request), $this->maxAttempts());

        $response = [
            'attempts'     => intval($attempts),
            'retries_left' => intval($retriesLeft),
        ];

        return $this->responseErrorWithData(ERROR_LOGIN_FAILED, '登录失败：用户名或密码错误', $response);
    }

    /**
     * 被锁定的响应
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = Lang::get('auth.throttle', ['seconds' => $seconds]);
        return $this->responseError(ERROR_TOO_MANY_ATTEMPTS, $message);
    }


    /**
     * 生成账号被禁用响应
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function sendUserDisabledResponse(Request $request)
    {
        //@todo 奇怪的是，登录成功后也不能清除redis里的失败记录
        $this->clearLoginAttempts($request);

        return $this->responseError(ERROR_UNKNOWN, '账号已禁用，请联系管理员');
    }

    /**
     * 登录成功的响应
     *
     * @param Request $request
     * @param $token
     * @return JsonResponse
     */
    protected function sendLoginResponse(Request $request, $token)
    {
        //@todo 奇怪的是，登录成功后也不能清除redis里的失败记录
        $this->clearLoginAttempts($request);

        return $this->responseData(compact('token', 'user'), '登录成功');
    }

    /**
     * 用户注销
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return $this->responseSuccess('注销成功');
    }

    /**
     * 获取用户信息
     *
     * @return JsonResponse
     */
    public function user()
    {
        $user = $this->guard()->user();
        return $this->responseData($user);
    }

    /**
     * 刷新token
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function refreshToken()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (TokenBlacklistedException $e) {
            return $this->responseError(ERROR_TOKEN_BLACKLISTED, '登录状态失效');
        }

        //$token = $this->guard()->refresh();
        return $this->responseData(compact('token'));
    }


    /**
     * 重设密码
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $username = $request->input('username');
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $password = $request->input('password');

        //检查验证码是否正确
        if (!$this->checkVerifyCode($mobile, $code)) {
            return $this->responseError(ERROR_UNKNOWN, '验证码错误');
        }

        $user = User::where('username', $username)
            ->where('mobile', $mobile)
            ->first();

        if (!$user) {
            return $this->responseError(ERROR_UNKNOWN, '用户不存在');
        }

        //重设密码
        $user->password = bcrypt($password);
        $user->save();

        //清除保存在redis里的验证码
        $key = 'sms_verify_code_' . $mobile;
        Cache::forget($key);

        return $this->responseSuccess('密码重设成功');
    }


    /*
        public function authenticate(Request $request)
        {
            // grab credentials from the request
            $credentials = $request->only('email', 'password');

            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            // all good so return the token
            return response()->json(compact('token'));
        }*/
}