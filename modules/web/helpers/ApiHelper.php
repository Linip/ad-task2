<?php
namespace app\modules\web\helpers;

use app\models\OrderStatus;
class ApiHelper
{
    const STATUS_DELIVERY_PENDING = 100;
    const STATUS_DELIVERY_ACCEPTED = 500;
    const STATUS_DELIVERY_BUYOUT = 800;

    static function resolveOrderStatus($statusCode) {
        switch ($statusCode) {
            case 100:
                $status = OrderStatus::STATUS_DELIVERY_PENDING;
                break;
            case 500:
                $status = OrderStatus::STATUS_DELIVERY_ACCEPTED;
                break;
            case 800:
                $status = OrderStatus::STATUS_DELIVERY_BUYOUT;
                break;
            default:
                throw new \yii\base\InvalidArgumentException("There is not api status {$statusCode}.");
        }

        return $status;
    }
}