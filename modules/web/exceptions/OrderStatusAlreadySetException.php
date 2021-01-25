<?php


namespace app\modules\web\exceptions;


use Throwable;

class OrderStatusAlreadySetException extends  \Exception
{
    protected $status;

    public function __construct($status, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
    }

    /**
     * @return integer
     */
    public function getStatus(){
        return (int) $this->status;
    }

}