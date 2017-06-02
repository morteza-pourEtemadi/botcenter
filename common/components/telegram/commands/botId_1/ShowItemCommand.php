<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use common\models\bot\botId_1\X;
use Yii;
use yii\helpers\Json;

/**
 * ShowItem Command to show items and collect votes
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class ShowItemCommand extends CommandLocal
{
    protected $name = 'showItem';
    protected $description = 'Command to show items to users and collect votes';
    protected $pattern = '/showItem';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $input = explode(' ', $this->_messageText);
        if (isset($input[1]) == false) {
            $item = $this->getItem();
        } else {
            $item = X::findOne(['code' => $input[1]]);
        }

        if ($item) {
            $user = User::findOne(['user_id' => $item->creator_id]);
            $caption = Yii::t('app_1', 'Sent Clip By:');
            $caption .= $user->type == User::TYPE_PREMIUM ? ' 👑 ' : ' ' . $user->getAName() . "\n\n";
            $caption .= Yii::t('app_1', 'which Captioned as:') . "\n";
            $caption .= $item->caption;

            $this->setCache(['code' => $item->code, 'spec' => $item->specialOptions]);
            $this->setPartKeyboard('voteItem');
            $this->sendFile($item->file_id, $caption);

            $seenXs = Json::decode($user->seenXs);
            $seenXs[] = $item->id;
            $user->seenXs = Json::encode($seenXs);
            $user->save();
        } else {
            $this->setPartKeyboard('competition');
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
            if (array_search($item->id, $seen, false) == false) {
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
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }
}
