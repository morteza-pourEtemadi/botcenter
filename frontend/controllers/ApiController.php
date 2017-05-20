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
                'Join Contest' . ' 🏁' => '/start',
                'Results' . ' 📊' => '/start',
                'Invite Friends' . ' 👬👭' => '/start',
                'Guide' . ' 📚' => '/start',
                'About Us' . ' 🔖' => '/start',
                'Upgrade to premium' . ' 📤' => '/start',
                'شرکت در مسابقه' . ' 🏁' => '/start',
                'مشاهده نتایج' . ' 📊' => '/start',
                'دعوت از دوستان' . ' 👬👭' => '/start',
                'راهنما' . ' 📚' => '/start',
                'درباره ما' . ' 🔖' => '/start',
                'ارتقاء به نسخه ویژه' . ' 📤' => '/start',
            ];
            $bot = new Bot([
                'bot_id' => 1,
                'telegram_id' => 350954048,
                'first_name' => 'دابسمش',
                'username' => 'iran_dubsmash_robot',
                'token' => '350954048:AAH2zJy-YFZTPVybo18MHqzdyysPtBapuRo',
                'type' => Bot::TYPE_IN_APP_PAYMENT,
                'priceString' => '"[]"',
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
