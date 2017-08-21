<?php
/**
 * 无权限异常类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Exceptions;

use Exception;

/**
 * Class NoPermitException
 * @package App\Api\V1\Exceptions
 */
class NoPermitException extends Exception
{

    /**
     * NoPermitException constructor.
     */
    public function __construct()
    {
        $message = '权限不足';
        $code = ERROR_NO_PERMIT;

        parent::__construct($message, $code);
    }
}