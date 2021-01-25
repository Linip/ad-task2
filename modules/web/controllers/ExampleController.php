<?php


namespace app\controllers;


use app\models\Country;
use app\models\Order;
use yii\web\Controller;

/**
 * Class ExampleController
 * @package app\modules\controllers
 */
class ExampleController extends Controller
{
    protected $countryChar;

    public function actionIndex($countryChar = 'ID')
    {
        $this->countryChar = $countryChar;
        $resultApiJson = file_get_contents('php://input');
        $country = Country::find()->byCharCode($countryChar)->one();

        if ($result = json_decode($resultApiJson, true)) {
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
        }
    }
}
