<?php

namespace common\components\telegram\commands\botId_16;

use common\models\botId_16\Question;
use common\models\botId_16\User;
use Yii;
use yii\helpers\Json;

/**
 * Sample Command for guide purpose
 * @property \common\components\telegram\types\Update $update
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
class SurveyCommand extends CommandLocal
{
    protected $name = 'sample';
    protected $description = 'It\'s a sample command';
    protected $pattern = '/survey';
    protected $public = true;

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $text = explode(' ', $this->_messageText);
        $user = User::findOne(['user_id' => $this->_chatId]);

        if ($this->isReply) {
            if (is_numeric($this->_messageText)) {
                if ($this->_messageText >= 0 && $this->_messageText <= 100) {
                    $answers = $this->getAnswers($user);
                    $answers[45] = $this->_messageText;

                    $user->survey = json_encode($answers);
                    $user->save();

                    $this->killReply();
                    $this->setPartKeyboard('main');
                    $this->sendMessage(Yii::t('app_16', 'thanks. you have done survey'));
                    return true;
                } else {
                    $this->sendMessage(Yii::t('app_16', 'must be 0-100'));
                    return true;
                }
            } else {
                $this->sendMessage(Yii::t('app_16', 'must be integer'));
                return true;
            }
        }

        if ($this->isExhibitor()) {
            if (isset($text[1]) === false) {
                $last = $this->getLastAnswer($user);
                if ($last == 0) {
                    $this->killKeyboard();
                    $this->sendMessage(Yii::t('app_16', 'welcome to survey. lets begin'));
                } elseif ($last == 45) {
                    $this->killKeyboard();
                    $this->sendMessage(Yii::t('app_16', 'you have done survey'));
                    return true;
                } else {
                    $this->killKeyboard();
                    $this->sendMessage(Yii::t('app_16', 'you have answered to {count} questions. now we continue', ['count' => $last]));
                }

                $question = Question::findOne(['index' => $last + 1]);
                $this->page = $question->index;

                $this->setPartKeyboard('setOptions');
                if ($last + 1 == 45) {
                    $this->setReply();
                    $this->killKeyboard();
                }
                $this->sendMessage(Yii::t('app_16', 'question {index}', ['index' => $question->index]) . "\n\n" . $question->text);
                return true;
            } else {
                if (isset($text[2]) === false) {
                    return false;
                }

                $answers = $this->getAnswers($user);
                $answers[$text[1]] = $text[2];
                $user->survey = json_encode($answers);
                $user->save();

                $question = Question::findOne(['index' => ($text[1] + 1)]);
                $this->page = $question->index;

                $this->setPartKeyboard('setOptions');
                $this->sendMessage(Yii::t('app_16', 'question {index}', ['index' => $question->index]) . "\n\n" . $question->text);
                return true;
            }
        } else {
            $this->setPartKeyboard('main');
            $this->sendMessage(Yii::t('app_16', 'just exhibitors'));

            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return Yii::t('app', 'It\'s a sample command');
    }

    /**
     * @param User $user
     */
    public function getAnswers($user)
    {
        $answers = [];
        if ($user->survey == '' || $user->survey == null) {
            for ($i = 1; $i <= 45; $i++) {
                $answers[$i] = '';
            }
        } else {
            $answers = Json::decode($user->survey);
        }
        return $answers;
    }

    /**
     * @param User $user
     * @return int
     */
    public function getLastAnswer($user)
    {
        $answers = $this->getAnswers($user);
        foreach ($answers as $id => $answer) {
            if ($answer == '') {
                return $id - 1;
            }
        }
        return count($answers);
    }
}
