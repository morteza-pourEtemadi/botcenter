<?php

namespace common\components\telegram\commands\botId_1;

use common\models\bot\botId_1\User;
use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class CountCommand extends CommandLocal
{
    protected $name = 'count';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/count';
    protected $public = false;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $users = User::find()->all();
        $this->sendMessage("N: " . count($users));
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
