<?php

namespace common\components\telegram\commands\botId_1;

use Yii;
use yii\helpers\Json;
use common\models\bot\botId_1\X;
use common\models\bot\Subscribers;
use common\models\bot\botId_1\User;

/**
 * Start Command for begin or restart bot's functions
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class StartCommand extends CommandLocal
{
    protected $name = 'start';
    protected $description = 'Start or restart the bot';
    protected $pattern = '/start';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
//        var_dump(Subscribers::deleteAll());
//        var_dump(User::deleteAll());
//        $this->killCache();
//        exit('123');
        $newUser = false;
        $input = $this->getInput();
        if ($this->paymentRendering($input)) {
            return true;
        }

        $user = User::findOne(['user_id' => $this->_chatId]);
        $subscriber = Subscribers::findOne(['user_id' => $this->_chatId, 'bot_id' => $this->bot->bot_id]);

        if ($subscriber === null) {
            $subscriber = new Subscribers([
                'user_id' => $this->_chatId,
                'bot_id' => $this->bot->bot_id,
            ]);
            $subscriber->save();
            $user = new User([
                'user_id' => $this->_chatId,
                'bonus_score' => 0,
            ]);
            $user->save();
            $newUser = true;
            if ($this->basicSettings($subscriber) == false) {
                return true;
            }
        }

        if (isset($input[1])) {
            if ($input[1] == 'lang') {
                $subscriber->getUser()->setSettings('language', $input[2]);
                Yii::$app->language = ($input[2] == 1) ? 'fa-IR' : 'en-US';
                $newUser = true;
                if (isset($input[3])) {
                    $payload = base64_decode($input[3]);
                    $this->killKeyboard();
                    $this->sendMessage(Yii::t('app_1', "Hi {user} \nWelcome to {bot} bot. Enjoy your time here 😊.\nRead the guide if you need help", ['user' => $this->getFirstName(), 'bot' => $this->bot->first_name]));
                } else {
                    $this->setMainKeyboard();
                    $this->sendMessage(Yii::t('app_1', "Hi {user} \nWelcome to {bot} bot. Enjoy your time here 😊.\nRead the guide if you need help", ['user' => $this->getFirstName(), 'bot' => $this->bot->first_name]));
                    return true;
                }
            } else {
                $payload = base64_decode($input[1]);
            }
            $inputParts = explode(':', $payload);
            if ($inputParts[0] == 'X') {
                if ($newUser) {
                    $user->bonus_score = 15;
                    $user->save();
                    $item = X::findOne(['code' => ($input[1] == 'lang' ? $input[3] : $input[1])]);
                    $caller = User::findOne(['user_id' => $item->creator_id]);
                    $caller->bonus_score += 30;
                    $caller->save();

                    $this->killKeyboard();
                    $this->sendMessage(Yii::t('app_1', 'You earned {up} points for joining via invite link and person who invited you gained {cp} points', ['up' => 15, 'cp' => 30]));
                }
                $this->setCache(['code' => ($input[1] == 'lang' ? $input[3] : $input[1])]);
                $this->setPartKeyboard('showItemStart');
                $this->sendMessage(Yii::t('app_1', 'click the button to watch the clip'));
                return true;
            } elseif ($inputParts[0] == 'invite') {
                if ($newUser) {
                    $user->bonus_score = 15;
                    $user->save();

                    $caller = User::findOne(['user_id' => $inputParts[1]]);
                    $caller->bonus_score += 50;
                    $caller->save();

                    $this->setMainKeyboard();
                    $this->sendMessage(Yii::t('app_1', 'You earned {up} points for joining via invite link and person who invited you gained {cp} points', ['up' => 15, 'cp' => 50]));
                } else {
                    $this->setMainKeyboard();
                    $this->sendMessage(Yii::t('app_1', 'You have used the bot before. So this invitation is not count!'));
                }
                return true;
            }
        }

        $this->setMainKeyboard();
        $this->sendMessage(Yii::t('app_1', "Hi {user} \nWelcome to {bot} bot. Enjoy your time here 😊.\nRead the guide if you need help", ['user' => $this->getFirstName(), 'bot' => $this->bot->first_name]));
        return true;
    }

    /**
     * @param Subscribers $subscriber
     * @return bool
     */
    public function basicSettings($subscriber)
    {
        $user = $subscriber->getUser();
        $settings = Json::decode($user->settings);
        if (!isset($settings['language'])) {
            $this->getLanguage();
            return false;
        }

        return true;
    }

    public function getLanguage()
    {
        $prevInput = substr($this->_messageText, 7, mb_strlen($this->_messageText) - 1);
        $this->setCache(['prevCommand' => $prevInput]);
        $this->setPartKeyboard('language');
        $this->sendMessage(Yii::t('app_1', 'Please choose your language'));
    }

    public function paymentRendering($input)
    {
        if (isset($input[1]) == false) {
            return false;
        }

        $dataString = base64_decode($input[1]);
        $data = explode(':', $dataString);

        if ($data[0] == 'receipt') {
            if ($data[1] == '!100') {
                $this->sendMessage(Yii::t('app_1', 'There must be a problem. Please try again later!'));
                return true;
            }
        } elseif ($data[0] == 'payStatus') {
            $this->killKeyboard();
            switch ($data[1]) {
                case '100':
                    $message = Yii::t('app_1', 'Payment done successfully!');
                    $this->setCache(['receipt' => $data[2]]);
                    $this->setPartKeyboard($data[3] == 'upgrade' ? 'getUpgrade' : 'getCoins');
                    break;
                case '101':
                    $message = Yii::t('app_1', 'You are trying to verify your payment more than once. If your connection is slow, please be patient! Otherwise don`t');
                    break;
                case '1':
                    $message = Yii::t('app_1', 'Payment process was canceled by yourself!');
                    break;
                case '0':
                default:
                    $message = Yii::t('app_1', 'An error occurred since operating your payment. If your credit does not came back to you after 24 hours please let us know!');
                    break;
            }
            $this->sendMessage($message);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
