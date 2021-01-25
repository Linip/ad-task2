<?php
namespace app\modules\web\services;

use app\models\Order;
use app\models\OrderStatus;
use app\modules\web\exceptions\DomainRecordNotFoundException;
use app\modules\web\exceptions\OrderStatusAlreadySetException;
use yii\base\InvalidArgumentException;

class OrderService implements OrderServiceInterface
{
    /**
     * @param $orderId
     * @param $trackingCode
     * @param $status
     * @throws DomainRecordNotFoundException
     * @throws OrderStatusAlreadySetException
     */
    public function changeStatus($orderId, $trackingCode, $status)
    {
        $order = Order::find()->where(['id' => $orderId, 'tracking' => $trackingCode])->one();

        if (!$order)
            throw new DomainRecordNotFoundException("Order {$orderId} not found.");

        if ($order->status_id == $status)
            throw new OrderStatusAlreadySetException($status);

        $order->status_id = $status;
        $order->save();
    }
}