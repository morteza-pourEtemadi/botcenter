<?php

namespace frontend\controllers;

use yii;
use yii\rest\Controller;
use common\models\bot\Bot;
use common\models\bot\Receipt;
use common\components\TelegramBot;
use amirasaran\zarinpal\Zarinpal;

/**
 * Payment Controller
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 * @since 2.0
 *
 * @property TelegramBot $api
 * @property Bot $bot
 */
class PaymentController extends Controller
{
    public $bot;

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [];
    }

    public function actionBots($Authority, $Status)
    {
        /** @var Zarinpal $zarinpal */
        $zarinpal = Yii::$app->zarinpal ;
        $receipt = Receipt::findOne(['authority' => $Authority]);
        $bot = Bot::findOne(['bot_id' => $receipt->bot_id]);
        $this->bot = $bot;

        if ($Status != 'OK') {
            $codeStatus = 1;
        } elseif ($zarinpal->verify($Authority, $receipt->price)->getStatus() == '100') {
            //User payment successfully verified!
             $codeStatus = 100;
        } elseif ($zarinpal->getStatus() == '101') {
            //User payment successfuly verified but user try to verify more than once
            $codeStatus = 101;
        } else {
            $codeStatus = 0;
        }

        $code = base64_encode('payStatus:' . $codeStatus . ':' . $receipt->id . ':' . $receipt->product);

        $url = 'https://telegram.me/' . $bot->username . '?start=' . $code;
        header('Location: ' . $url);
        exit();
    }

    public function actionReceipt($pay_id)
    {
        $data = explode('.ubpd', $pay_id);
        $userId = base64_decode($data[0]);
        $botId = base64_decode($data[1]);
        $price = base64_decode($data[2]);
        $time = base64_decode($data[3]);
        $description = base64_decode($data[4]);
        $product = base64_decode($data[5]);

        $bot = Bot::findOne(['bot_id' => $botId]);
        $this->bot = $bot;

        /* @var Zarinpal $zarinpal */
        $zarinpal = Yii::$app->zarinpal;
        if ($zarinpal->request($price, $description)->getStatus() == '100') {
            $receipt = new Receipt([
                'user_id' => $userId,
                'bot_id' => $botId,
                'status' => Receipt::STATUS_NOT_PAYED,
                'price' => $price,
                'time' => $time,
                'product' => $product,
                'authority' => $zarinpal->getAuthority(),
                'description' => $description,
                'redirect_url' => $zarinpal->callback_url,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            $receipt->save();

            return $this->redirect($zarinpal->getRedirectUrl());
        }

        $code = base64_encode('receipt:!100');
        $url = 'https://telegram.me/' . $this->bot->username . '?start=' . $code;
        header('Location: ' . $url);
        exit();
    }


    /**
     * Returns telegram bot api object
     * @return TelegramBot
     */
    public function getApi()
    {
        return new TelegramBot(['authKey' => $this->bot->token]);
    }

    public function getKeyboard($botId, $chatId)
    {
        $keyboard = Yii::$app->cache->get('keyboard:' . $botId . $chatId);
        return isset($keyboard['value']) ? $keyboard['value'] : null;
    }

    public function calcTime($time)
    {
        $dayLong = 3600*24;
        $weekLong = 3600*24*7;
        $monthLong = 3600*24*30;

        $month = floor($time / ($monthLong));
        $week = floor(($time - ($month * $monthLong)) / $weekLong);
        $day = floor(($time - ($month * $monthLong) - ($week * $weekLong)) / $dayLong);

        $returnTime = $month > 0 ? $month . Yii::t('app_payment', 'month') : '';
        $returnTime .= $week > 0 ? $week . Yii::t('app_payment', 'week') : '';
        $returnTime .= $day > 0 ? $day . Yii::t('app_payment', 'day') : '';

        return $returnTime;
    }
}
