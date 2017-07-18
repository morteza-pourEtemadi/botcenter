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
            $bot = new Bot([
                'bot_id' => 16,
                'telegram_id' => 12312,
                'first_name' => 'myTestBot',
                'username' => 'morteza_test_bot',
                'token' => '227461477:AAFklo5LR-WyZFRVnmJYz2-CVWdgMBwJmUc',
                'type' => Bot::TYPE_IN_APP_PAYMENT,
                'translations' => '[]',
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
