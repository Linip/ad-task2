<?php


namespace app\modules\web\controllers;


use app\models\Country;
use app\models\Order;
use app\modules\web\exceptions\DomainRecordNotFoundException;
use app\modules\web\exceptions\OrderStatusAlreadySetException;
use app\modules\web\helpers\ApiHelper;
use app\modules\web\services\OrderServiceInterface;
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
    /**
     * @var OrderServiceInterface
     */
    protected $orderService;

    public function __construct($id, $module, OrderServiceInterface $orderService,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->orderService = $orderService;
    }

    public function actionIndex($countryChar = 'ID')
    {
        $resultApiJson = Yii::$app->request->getRawBody();

        try {
            $result = BaseJson::decode($resultApiJson, true);
        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }

        if (!$this->inputIsVaild($result))
            $this->fail('Invalid json object structure got.');

        try {
            $orderId = (int)$result->OrderCode;

            $trackingCode = (string)$result->TrackingCode;

            $status = ApiHelper::resolveOrderStatus($result->StatusCode);

            $this->orderService->changeStatus($orderId, $trackingCode, $status);

        } catch (DomainRecordNotFoundException $e) {
            return $this->fail($e->getMessage());
        } catch (OrderStatusAlreadySetException $e) {
            $msg = "Status of order #{$orderId} already set to \"{$e->getStatus()}\".";
            return $this->fail($msg);
        } catch (InvalidArgumentException $e) {
            return $this->fail($e->getMessage());
        }

        return $this->success();
    }

    protected function inputIsVaild($result) {
        return (
            isset($result->OrderCode) && isset($result->TrackingCode) && isset($result->StatusCode)
            && !empty($result->OrderCode) && !empty($result->TrackingCode) && empty($result->StatusCode)
        );
    }
}
