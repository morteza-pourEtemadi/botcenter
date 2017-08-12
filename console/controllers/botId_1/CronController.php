<?php

namespace console\controllers\botId_1;

use common\models\bot\Bot;
use yii\console\Controller;
use common\components\TelegramBot;
use common\models\bot\botId_1\User;

/**
 * Class CronController
 * @package console\controllers\botId_2
 */
class CronController extends Controller
{
    public function actionDailyCheck()
    {
        $users = User::find()->all();
        $this->stdout("Daily Check on extra points. Time: " . date('Y m d / h:i:s') . "  Started!\n\n");
        foreach ($users as $user) {
            /* @var User $user */
            //var_dump($user);exit;
            if ($user->extra == 0) {
                continue;
            }

            if ($user->extra % 3 == 0) {
                if (!$this->isJoinedChannel('@UD_newsletter', $user->user_id)) {
                    $user->extra -= 500;
                }
                if ($user->extra == 1000) {
                    if (!$this->isJoinedChannel('@ultimate_developer', $user->user_id)) {
                        $user->extra -= 1000;
                    }
                }
            } else {
                if (!$this->isJoinedChannel('@ultimate_developer', $user->user_id)) {
                    $user->extra -= 1000;
                }
            }
            $user->save();
            $this->stdout('user: ' . $user->getAName() . ' with ID: ' . $user->user_id . " Saved!\n");
        }
        $this->stdout("Finished!!\n\n\n");
    }

    /**
     * Returns telegram bot api object
     * @return TelegramBot
     */
    public function getApi()
    {
        $bot = Bot::findOne(['bot_id' => 2]);
        return new TelegramBot(['authKey' => $bot->token]);
    }

    public function isJoinedChannel($username, $userId)
    {
        $response = $this->getApi()->getChatMember($username, $userId);
        if ($response->ok == true) {
            $status = $response->result->status;
            if ($status == 'left' || $status == 'kicked') {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}