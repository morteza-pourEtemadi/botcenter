<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\botId_1\Vote;
use common\models\bot\botId_1\X;
use Yii;

/**
 * Results Command for showing results in general and details
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ResultsCommand extends CommandLocal
{
    protected $name = 'results';
    protected $description = 'result command';
    protected $pattern = '/results';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1]) && $input[1] == 'detail') {
            $this->sendDetailBoard();
        } else {
            $this->sendLeaderBoard();
        }

        return true;
    }

    public function sendDetailBoard()
    {
        $message = '';
        $user = User::findOne(['user_id' => $this->_chatId]);
        $items = X::findAll(['creator_id' => $user->user_id]);

        foreach ($items as $item) {
            /* @var X $item*/
            $loves = Vote::findAll(['item' => $item->id, 'type' => Vote::TYPE_LOVE]);
            $likes = Vote::findAll(['item' => $item->id, 'type' => Vote::TYPE_LIKE]);
            $dislikes = Vote::findAll(['item' => $item->id, 'type' => Vote::TYPE_DISLIKE]);
            $hates = Vote::findAll(['item' => $item->id, 'type' => Vote::TYPE_HATE]);
            $score = floor(((count($loves) * 2) + count($likes) - count($dislikes) - (count($hates) * 1.8)) * 12);
            $message .= '⭐️ ' . $item->caption . ":\n\n";

            $message .= '❤️❤️' . Yii::t('app_1', 'number of loves:') . ' ' . count($loves) . "\n";
            $message .= '😍 ' . Yii::t('app_1', 'number of likes:') . ' ' . count($likes) . "\n";
            $message .= '😒 ' . Yii::t('app_1', 'number of dislikes:') . ' ' . count($dislikes) . "\n";
            $message .= '🤢 ' . Yii::t('app_1', 'number of hates:') . ' ' . count($hates) . "\n\n";
            $message .= '🏁 ' . Yii::t('app_1', 'This item score:') . ' ' . $score;
            $message .= "\n-------------------------\n\n";
        }

        $message .= '🏆' . Yii::t('app_1', 'your total score is:') . ' ' . $user->getScore() . '🏆';
        $message .= "\n\n@" . $this->bot->username;

        $this->setPartKeyboard('competition');
        $this->sendMessage($message);

        return true;
    }

    public function sendLeaderBoard()
    {
        $yourBoard = '';
        $topRanks = '🏆 ' . Yii::t('app_1', 'Top 10') . " 🏆\n\n";

        $users = User::find()->all();
        usort($users, function ($a, $b) {
            /* @var User $a */
            /* @var User $b */
            if ($a->getScore() > $b->getScore()) {
                return -1;
            }
            return $a->getScore() < $b->getScore() ? 1 : 0;
        });

        $i = 1;
        foreach ($users as $user) {
            /* @var User $user */
            if ($i <= 10) {
                $topRanks .= '🏅' . Yii::t('app_1', ' rank ') . (string) $i . ' « ' . $user->type == User::TYPE_PREMIUM ? '👑 ' : '' . $user->getAName() . ' » ' . Yii::t('app_1', ' with score ') . $user->getScore() . "\n\n";
            }
            if ($user->user_id == $this->_chatId) {
                $rank = $i;
            }
            $i++;
        }

        $user = User::findOne(['user_id' => $this->_chatId]);
        if (isset($rank)) {
            $yourBoard .= '🏁 ' . Yii::t('app_1', 'your score') . ': ' . $user->getScore() . "\n\n";
            $yourBoard .= '🎖 ' . Yii::t('app_1', 'your rank') . ': ' . $rank . "\n\n";
            $yourBoard .= "-------------------------\n\n";
        } else {
            $yourBoard .= Yii::t('app_1', 'You have not participate in competition yet. Please join the contest!');
            $yourBoard .= "\n\n-------------------------\n\n";
        }

        $this->setMainKeyboard();
        $this->sendMessage($yourBoard . $topRanks . "\n@" . $this->bot->username . "\n🏆🎞🏆");

        return true;
    }
}
