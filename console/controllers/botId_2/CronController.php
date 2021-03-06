<?php

namespace console\controllers\botId_2;

use common\components\telegram\types\keyboards\InlineKeyboardButton;
use Yii;
use yii\helpers\Json;
use common\models\bot\Bot;
use yii\console\Controller;
use common\components\TelegramBot;
use common\models\bot\botId_2\User;
use common\models\bot\botId_2\Khatm;

/**
 * Class CronController
 * @package console\controllers\botId_2
 */
class CronController extends Controller
{
    public function actionDailyCheck()
    {
        $users = User::find()->all();
        $messages = 0;
        $t = time();
        $this->stdout(date('Y m d / H i s') . " - Start warning:\n\n");
        foreach ($users as $user) {
            if ($messages >= 29) {
                if ((time() - $t) <= 1) {
                    sleep(1);
                    $messages = 0;
                    $t = time();
                }
            }
            /* @var User $user */
            $currents = isset($user->current_aya) ? (is_array(Json::decode($user->current_aya)) ? Json::decode($user->current_aya) : []) : [];
            $unReads = [];
            foreach ($currents as $current) {
                if (isset($current['lup'])) {
                    if ((time() - $current['lup']) > 86400) {
                        if ($ktm = Khatm::findOne(['id' => $current['ktm_id']])) {
                            $unReads[] = [
                                'id' => $ktm->id,
                                'title' => $ktm->title,
                                'x' => $ktm->getTypePart(),
                            ];
                        }
                    }
                }
            }
            if (count($unReads) != 0) {
                $message = Yii::t('app_2', 'You have not read this khatms for more than 24 hours:') . "\n\n";
                foreach ($unReads as $item) {
                    $message .= "- " . $item['title'] . "\n";
                }
                $message .= "\n" . Yii::t('app_2', 'Please pay more attention to them');
                $sent = $this->getApi()->sendMessage($user->user_id, $message, null, $this->makeKeyboard($unReads));
                if ($sent->ok == false && $sent->error_code == 403) {
                    $this->stdout("B - User: " . $user->getUniqueUser()->username . " - " . $user->user_id . " -> Blocked Bot\.\n");
                } else {
                    $this->stdout("W - User: " . $user->getUniqueUser()->username . " - " . $user->user_id . " -> Warned.\n");
                    $messages++;
                }
            }
        }
        $this->stdout("\n" . date('H i s') . " - Finish warning:\n\n");
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

    public function makeKeyboard($ktms)
    {
        $key = [];
        $keyboard = [];
        foreach ($ktms as $ktm) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'get {x} for {t}', ['x' => $ktm['x'], 't' => $ktm['title']]), '/get share ' . $ktm['id']);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_2', 'back to main menu'), '/start');

        foreach ($key as $item) {
            $keyboard[] = [$item];
        }
        $inline = [
            'inline_keyboard' => $keyboard
        ];
        return $inline;
    }
}