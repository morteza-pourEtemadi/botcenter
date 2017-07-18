<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\botId_1\Vote;
use common\models\bot\botId_1\X;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class AdminVoteCommand extends CommandLocal
{
    protected $name = 'adminVote';
    protected $description = 'It\'s a command for reporting by admins';
    protected $pattern = '/adminVote';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = $this->getInput();
        if (isset($input[2]) == false) {
            return false;
        }

        $item = X::findOne(['id' => $input[2]]);

        if ($input[1] == '1') {
            $item->status = X::STATUS_REPORTED;
            $item->save();
            $this->sendMessage(Yii::t('app_1', 'Item reported successfully'));
        } else {
            $votes = Vote::findAll(['item' => $input[2], 'type' => Vote::TYPE_REPORT]);
            foreach ($votes as $vote) {
                $user = User::findOne(['user_id' => $vote->voter]);
                $user->bonus_score -= 100;
                $user->save();
                $this->api->sendMessage(
                    $user->user_id,
                    Yii::t('app_1', 'Your score decreased by 100 points for abusing report ability on video:') . "\n" . $item->caption,
                    null,
                    $this->getUserKeyboard($user->user_id)
                );
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
