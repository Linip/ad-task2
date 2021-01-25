<?php
namespace app\modules\web\services;

use app\modules\web\exceptions\DomainRecordNotFoundException;
use app\modules\web\exceptions\OrderStatusAlreadySetException;

interface OrderServiceInterface
{
    /**
     * @param $orderId
     * @param $trackingCode
     * @param $statusCode
     * @throws DomainRecordNotFoundException
     * @throws OrderStatusAlreadySetException
     */
    public function changeStatus($orderId, $trackingCode, $status);
}