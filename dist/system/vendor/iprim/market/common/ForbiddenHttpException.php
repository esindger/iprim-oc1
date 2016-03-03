<?php
namespace iprim\market\common;

/**
 * Class ForbiddenHttpException
 * @package iprim\market\exceptions
 */
class ForbiddenHttpException extends HttpException
{
    /**
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(403, $message, $code, $previous);
    }
}
