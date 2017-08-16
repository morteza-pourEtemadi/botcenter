<?php

namespace common\components\telegram\commands\botId_1;

use common\components\telegram\types\keyboards\InlineKeyboardButton;
use Yii;
use yii\helpers\Json;
use common\models\bot\botId_1\X;
use common\models\bot\botId_1\Vote;
use common\models\bot\botId_1\User;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class VoteCommand extends CommandLocal
{
    protected $name = 'vote';
    protected $description = 'It\'s a command to process the votes';
    protected $pattern = '/vote';
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
        $item = X::findOne(['code' => $input[2]]);
        $user = User::findOne(['user_id' => $this->_chatId]);
        $seen = Json::decode($user->seenXs);
        $itemOwner = User::findOne(['user_id' => $item->creator_id]);
        $vote = Vote::findOne(['voter' => $user->id, 'item' => $item->id]);
        if ($vote == null) {
            $vote = new Vote([
                'voter' => $user->id,
                'item' => $item->id,
            ]);

            switch ($input[1]) {
                case '1':
                    $user->coins += 2;
                    $itemOwner->Xs_loves += 1;
                    $vote->type = Vote::TYPE_LOVE;
                    break;
                case '2':
                    $user->coins += 1;
                    $itemOwner->Xs_likes += 1;
                    $vote->type = Vote::TYPE_LIKE;
                    break;
                case '3':
                    $user->bonus_score -= 2;
                    $itemOwner->Xs_dislikes += 1;
                    $vote->type = Vote::TYPE_DISLIKE;
                    break;
                case '4':
                    $user->bonus_score -= 4;
                    $itemOwner->Xs_hates += 1;
                    $vote->type = Vote::TYPE_HATE;
                    break;
                case '5':
                    $vote->type = Vote::TYPE_REPORT;
                    $this->checkReport($item);
                    break;
            }
            $vote->save();
            $user->save();
            $itemOwner->save();
        } else {
            switch ($vote->type) {
                case Vote::TYPE_LOVE:
                    $user->coins -= 2;
                    $itemOwner->Xs_loves -= 1;
                    break;
                case Vote::TYPE_LIKE:
                    $user->coins -= 1;
                    $itemOwner->Xs_likes -= 1;
                    break;
                case Vote::TYPE_DISLIKE:
                    $user->bonus_score += 2;
                    $itemOwner->Xs_dislikes -= 1;
                    break;
                case Vote::TYPE_HATE:
                    $user->bonus_score += 4;
                    $itemOwner->Xs_hates -= 1;
                    break;
                case Vote::TYPE_REPORT:
                    $this->setMainKeyboard();
                    $this->sendMessage(Yii::t('app_1', 'You have reported this clip before.'));
                    return false;
            }
            switch ($input[1]) {
                case '1':
                    $user->coins += 2;
                    $itemOwner->Xs_loves += 1;
                    $vote->type = Vote::TYPE_LOVE;
                    break;
                case '2':
                    $user->coins += 1;
                    $itemOwner->Xs_likes += 1;
                    $vote->type = Vote::TYPE_LIKE;
                    break;
                case '3':
                    $user->bonus_score -= 2;
                    $itemOwner->Xs_dislikes += 1;
                    $vote->type = Vote::TYPE_DISLIKE;
                    break;
                case '4':
                    $user->bonus_score -= 4;
                    $itemOwner->Xs_hates += 1;
                    $vote->type = Vote::TYPE_HATE;
                    break;
                case '5':
                    $vote->type = Vote::TYPE_REPORT;
                    $this->checkReport($item);
                    break;
            }
            $vote->save();
            $user->save();
            $itemOwner->save();
        }
        $this->killKeyboard();
        $this->sendMessage(Yii::t('app_1', 'You vote is collected. You have earned diamonds according to guide! If you have not read it yet, Please do it!'));

        $item = $this->getItem();
        if ($item) {
            $itemOwner = User::findOne(['user_id' => $item->creator_id]);
            $caption = Yii::t('app_1', 'Sent Clip By:');
            $caption .= ($itemOwner->type == User::TYPE_PREMIUM ? ' 👑 ' : ' ') . $itemOwner->getAName() . "\n\n";
            $caption .= Yii::t('app_1', 'which Captioned as:') . "\n";
            $caption .= $item->caption;

            $this->setCache(['code' => $item->code, 'spec' => $item->specialOptions]);
            $this->setPartKeyboard('voteItem', 1, 'vote');
            $this->sendFile($item->file_id, $caption);

            $seenXs = Json::decode($user->seenXs);
            $seenXs[] = $item->id;
            $user->seenXs = Json::encode($seenXs);
            $user->save();
        } else {
            $this->setPartKeyboard('competition', 1, 'comp');
            $this->sendMessage(Yii::t('app_1', 'There is no more clips to watch! Please visit again later.'));
        }
        return true;
    }

    /**
     * @return false|X
     */
    public function getItem()
    {
        $user = User::findOne(['user_id' => $this->_chatId]);
        $seen = Json::decode($user->seenXs);
        $items = X::findAll(['status' => X::STATUS_ACTIVE]);

        $specials = $normals = [];
        foreach ($items as $item) {
            if (array_search($item->id, $seen, false) === false) {
                $spec = Json::decode($item->specialOptions);
                if ($spec != [] && isset($spec['top']['time']) && $spec['top']['time'] > time()) {
                    $specials[] = $item;
                } else {
                    $normals[] = $item;
                }
            }
        }

        if (count($specials) != 0) {
            $rnd = mt_rand(1, count($specials));
            $selectedItem = $specials[$rnd - 1];
        } elseif (count($normals) != 0) {
            $rnd = mt_rand(1, count($normals));
            $selectedItem = $normals[$rnd - 1];
        } else {
            return false;
        }
        return $selectedItem;
    }

    /**
     * @param X $item
     */
    public function checkReport($item)
    {
        $admins = ['101538817']; // My Profile First
        $reports = Vote::findAll(['item' => $item->id, 'type' => Vote::TYPE_REPORT]);

        if (count($reports) >= 3) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'report this'), '/adminVote 1 ' . $item->id);
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_1', 'release this'), '/adminVote 2 ' . $item->id);
            $keyboard = [
                'inline_keyboard' => [[$key[0]], [$key[1]]],
            ];
            foreach ($admins as $admin) {
                $this->api->sendVideo($admin, $item->file_id, Yii::t('app_1', 'What do you do'), null, $keyboard);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
