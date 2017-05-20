<?php

namespace common\components\telegram\commands\botId_1;

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

        } else {

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
