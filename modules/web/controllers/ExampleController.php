<?php


namespace app\controllers;


use app\models\Country;
use app\models\Order;
use yii\base\InvalidArgumentException;
use yii\helpers\BaseJson;
use yii\web\Controller;
use Yii;

/**
 * Class ExampleController
 * @package app\modules\controllers
 */
class ExampleController extends Controller
{
    public function actionIndex($countryChar = 'ID')
    {
        $resultApiJson = Yii::$app->request->getRawBody();

        try {
            $result = BaseJson::decode($resultApiJson, true);
            $orderId = (int)$result->OrderCode;
            $trackingCode = (string)$result->TrackingCode;

            $order = Order::find()->where(['id' => $orderId, 'tracking' => $trackingCode])->one();

            switch ($result->StatusCode) {
                case 100:
                    $status = OrderStatus::STATUS_DELIVERY_PENDING;
                    break;
                case 500:
                    $status = OrderStatus::STATUS_DELIVERY_ACCEPTED;
                    break;
                case 800:
                    $status = OrderStatus::STATUS_DELIVERY_BUYOUT;
                    break;
            }

            if ($order->status_id != $status) {
                $order->status_id = $status;
                $order->save();
                return $this->success();
            } else {
                $msg = "Status of order #{$orderId} already set to \"{$status}\".";
                return $this->fail($msg);
            }

        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }
    }
}
