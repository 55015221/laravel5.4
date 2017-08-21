<?php
/**
 * 认证失败异常类.
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class AuthorizedFailedException extends HttpResponseException
{

}