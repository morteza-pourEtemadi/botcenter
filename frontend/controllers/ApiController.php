<?php

namespace frontend\controllers;

use yii;
use common\models\bot\Bot;
use yii\base\Exception;
use yii\rest\Controller;
use common\components\telegram\Parser;

/**
 * ApiController
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 * @since 2.0
 */
class ApiController extends Controller
{
    private $_bot;

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return ['hook' => ['POST']];
    }

    /**
     * Webhook for telegram requests
     * @param $tokenId
     * @param $tokenString
     * @return array
     */
    public function actionHook($tokenId, $tokenString)
    {
        if ($this->getBot($tokenId, $tokenString) === null) {
            $trans = [
                'Join Quran Khatm' . ' 📗' => '/join',
                'مشارکت در ختم قرآن' . ' 📗' => '/join',
                'See khatms lists' . ' 📑' => '/join',
                'مشاهده لیست ختم های قرآن' . ' 📑' => '/join',
                'Random Aya' . ' 📤' => '/random',
                'آیه تصادفی' . ' 📤' => '/random',
                'Hadith of the day' . ' 📜' => '/hadith',
                'حدیث روز' . ' 📜' => '/hadith',
                'Guide' . ' 📚' => '/help',
                'راهنما' . ' 📚' => '/help',
                'About Us' . ' 🔖' => '/about',
                'درباره ما' . ' 🔖' => '/about',
            ];
            $bot = new Bot([
                'bot_id' => 2,
                'telegram_id' => 210807040,
                'first_name' => 'ختم قرآن',
                'username' => 'PocketQuranBot',
                'token' => '210807040:AAED2LjXbeyZDaHSyDGPxEo2OW1iliadqOU',
                'type' => Bot::TYPE_IN_APP_PAYMENT,
                'priceString' => json_encode([
                    'objects' => [
                        'support' => [1000, 2000, 5000, 10000]
                    ]
                ]),
                'translations' => yii\helpers\Json::encode($trans),
            ]);
            var_dump($bot->save());
            exit('132');
            return ['ok' => true, 'message' => 'The requested page does not exist.'];
        }

        try {
            $telegramParser = new Parser([
                'bot' => $this->_bot,
                'update' => Yii::$app->request->bodyParams,
            ]);
            $telegramParser->parse();
        } catch (Exception $e) {
            Yii::error(['message' => $e->getMessage(), 'file' => $e->getFile() . ':' . $e->getLine(), 'request' => Yii::$app->request->bodyParams], __METHOD__);
            return ['ok' => false];
        }

        if ($telegramParser->hasErrors()) {
            return ['ok' => false];
        }

        return ['ok' => true];
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

        return $this->_bot = Bot::findOne(['token' => "{$tokenId}:{$tokenString}"]);
    }
}
