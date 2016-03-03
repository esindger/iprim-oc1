<?php
namespace iprim\market\common;

class HttpException extends Exception
{
    /**
     * @var integer
     */
    public $statusCode;

    /**
     * @param string $status
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($status, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->statusCode = $status;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Error ' . $this->statusCode;
    }
}
