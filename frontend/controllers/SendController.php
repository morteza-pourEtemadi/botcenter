<?php

namespace frontend\controllers;

use yii;
use yii\helpers\Json;
use yii\base\Exception;
use yii\rest\Controller;
use common\models\bot\Bot;
use common\components\telegram\SendParser;

/**
 * SendController
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SendController extends Controller
{
    private $_bot;

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return ['massive' => ['POST']];
    }

    /**
     * @param $tokenId
     * @param $tokenString
     * @return array
     */
    public function actionMassive($tokenId, $tokenString)
    {
        if ($this->getBot($tokenId, $tokenString) === null) {
            return ['ok' => true, 'message' => 'The requested page does not exist. Maybe your bot API Token is wrong'];
        }

        try {
            $telegramParser = new SendParser([
                'bot' => $this->_bot,
                'sender' => Yii::$app->request->bodyParams,
            ]);
            $ret = $telegramParser->parse();
            if ($ret == false) {
                return ['ok' => true, 'message' => 'Insufficient data!'];
            } else {
                return Json::decode($ret);
            }
        } catch (Exception $e) {
            Yii::error(['message' => $e->getMessage(), 'file' => $e->getFile() . ':' . $e->getLine(), 'request' => Yii::$app->request->bodyParams], __METHOD__);
            return ['ok' => false];
        }
    }

    /**
     * Returns Bot model
     * @param $tokenId
     * @param $tokenString
     * @return Bot null|static
     */
    public function getBot($tokenId, $tokenString)
    {
        if ($this->_bot) {
            return $this->_bot;
        }
        $params = $this->getMe("{$tokenId}:{$tokenString}");
        if ($params->ok == false) {
            return null;
        }
        $bot = new Bot([
            'telegram_id' => $params->result->id,
            'first_name' => $params->result->first_name,
            'username' => $params->result->username,
            'token' => "{$tokenId}:{$tokenString}"
        ]);
        return $this->_bot = $bot;
    }

    public function getMe($token)
    {
        $url = 'https://api.telegram.org/bot' . $token . '/getMe';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,  $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        if ($curlError = curl_error($curl)) {
            Yii::error(['message' => 'CURL Error', 'error' => curl_error($curl)]);
        }
        curl_close($curl);
        return json_decode($response);
    }
}
