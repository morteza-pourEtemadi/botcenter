<?php

namespace common\components\telegram\commands\botId_2;

use Yii;

/**
 * Sample Command for guide purpose
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SampleCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/sample';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->isReply) {
            $this->sendMessage($this->reply . ' Is it reply message.');
            $this->killReply();
        } else {
            $this->setReply('1');
            $this->sendMessage('Sample command write for tutorial purpose.');
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
