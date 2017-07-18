<?php

namespace common\components\telegram\commands\botId_16;

use yii;
use yii\helpers\Json;
use yii\helpers\Html;
use common\models\botId_16\User;
use common\models\botId_16\News;
use common\models\botId_16\Leads;
use common\models\botId_16\Shows;
use common\models\botId_16\Question;
use common\models\botId_16\Placement;
use common\components\telegram\commands\Command;
use common\components\telegram\types\keyboards\InlineKeyboardButton;

/**
 * Telegram Command Core
 *
 * @property mixed $reply
 * @property Shows $shows
 * @property integer $page
 *
 * @author Morteza Pouretemadi <e.morteza94@yahoo.com>
 */
abstract class CommandLocal extends Command
{
    public $page;
    public $shows;

    public function isExhibitor()
    {
//        $user = User::findOne(['user_id' => $this->_chatId]);
        return true;
    }

    public function setOptions()
    {
        $question = Question::findOne(['index' => $this->page]);
        $options = Json::decode($question->options);

        foreach ($options as $option) {
            $key[] = InlineKeyboardButton::setNewKeyButton($option['text'], '/survey ' . $question->index . ' ' . $option['index']);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function adminsRule()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'count'), '/count');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'send news'), '/sendNews');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'submit leads'), '/submitLeads');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'submit placements'), '/submitPlacements');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'add new show'), '/newShow');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'delete a show'), '/deleteShows');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function sendNews()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'yes. send to all'), '/sendNews all ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelNews ' . $this->page);

        return $key;
    }

    public function submitLead()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'submit'), '/submitLeads 1 ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'deny'), '/submitLeads 2 ' . $this->page);

        return $key;
    }

    public function submitPlacement()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'submit'), '/submitPlacements 1 ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'deny'), '/submitPlacements 2 ' . $this->page);

        return $key;
    }

    public function deleteRecipe1()
    {
        $shows = Shows::find()->all();
        foreach ($shows as $recipe) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Html::decode($recipe->title), '/deleteShows ' . $recipe->id);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back'), '/admin');

        return $key;
    }

    public function cancelRecipe()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelShow ' . $this->page);
        return $key;
    }

    public function addAttachments()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'done'), '/admin done ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelShow ' . $this->page);

        return $key;
    }

    public function addNewsAttachments()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'done'), '/sendNews done');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelNews ' . $this->page);

        return $key;
    }

    public function addLeadAttachments()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'done'), '/newLead done');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelLead ' . $this->page);

        return $key;
    }

    public function getRecipes()
    {
        $key = [];
        $recipes = Shows::find()->all();

        foreach ($recipes as $recipe) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Html::decode($recipe->title), '/selectedShow ' . $recipe->id);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'main menu'), '/main');

        return $key;
    }

    public function showRecipe()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back'), '/selectShow');
        return $key;
    }

    public function selectWhich()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Exhibitor'), '/start 0 ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Participant'), '/start 1 ' . $this->page);

        return $key;
    }

    public function newsPage()
    {
        $news = News::find()->all();

        if (count($news) > 1) {
            if ($this->page == 0) {
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/archive 1 1');
            } elseif ($this->page == count($news) - 1) {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/archive ' . ($this->page - 1) . ' 0');
            } else {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/archive ' . ($this->page - 1) . ' 0');
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/archive ' . ($this->page + 1) . ' 1');
            }
        }

        if ($this->isUserOwner()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'remove news'), '/block news ' . $this->page);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function leads()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'before print'), '/leads 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'printing'), '/leads 2');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'after print'), '/leads 3');

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'main menu'), '/main');

        return $key;
    }

    public function subLeads()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'sell leads'), '/leads ' . $this->page . ' 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'buy leads'), '/leads ' . $this->page . ' 2');

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back'), '/leads');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'main menu'), '/main');

        return $key;
    }

    public function subPlacements()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'standby force'), '/placements 0 1');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Recruitment Ads'), '/placements 0 2');

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'main menu'), '/main');

        return $key;
    }

    public function leadsPage()
    {
        $page = explode(':', $this->page);
        $page1 = $page[0];
        $page2 = $page[1];
        $page3 = $page[2];

        $leads = Leads::findAll(['category' => $page1, 'subcategory' => $page2, 'status' => Leads::STATUS_CONFIRMED]);

        if (count($leads) > 3) {
            if ($page3 == 0) {
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/leads ' . $page1 . ' ' . $page2 . ' 3');
            } elseif ($page3 >= count($leads) - 3) {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/leads ' . $page1 . ' ' . $page2 . ' ' . ($page3 - 3));
            } else {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/leads ' . $page1 . ' ' . $page2 . ' ' . ($page3 - 3));
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/leads ' . $page1 . ' ' . $page2 . ' ' . ($page3 + 3));
            }
        }

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'new lead'), '/newLead ' . $page1 . ' ' . $page2);
        if ($this->isUserOwner()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'block lead'), '/block lead ' . $this->page);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function placementsPage()
    {
        $page = explode(':', $this->page);
        $page1 = $page[0];
        $page2 = $page[1];
        $page3 = $page[2];

        $placements = Placement::findAll(['category' => $page1, 'subcategory' => $page2, 'status' => Placement::STATUS_CONFIRMED]);

        if (count($placements) > 3) {
            if ($page3 == 0) {
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/placements ' . $page1 . ' ' . $page2 . ' 3');
            } elseif ($page3 >= count($placements) - 3) {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/placements ' . $page1 . ' ' . $page2 . ' ' . ($page3 - 3));
            } else {
                $key[] = InlineKeyboardButton::setNewKeyButton('👈 ' . Yii::t('app_16', 'prev page'), '/placements ' . $page1 . ' ' . $page2 . ' ' . ($page3 - 3));
                $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'next page') . ' 👉', '/placements ' . $page1 . ' ' . $page2 . ' ' . ($page3 + 3));
            }
        }

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'new placement'), '/newPlacement 0 ' . $page2);
        if ($this->isUserOwner()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'block lead'), '/block placement ' . $this->page);
        }
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function cancelLead()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelLead ' . $this->page);
        return $key;
    }

    public function cancelPlacement()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'cancel'), '/cancelPlacement ' . $this->page);
        return $key;
    }

    public function firstLead()
    {
        $page = explode(':', $this->page);
        $page1 = $page[0];
        $page2 = $page[1];

        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'new lead'), '/newLead ' . $page1 . ' ' . $page2);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function firstPlacement()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'new placement'), '/newPlacement 0 ' . $this->page);
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'back to main menu'), '/main');

        return $key;
    }

    public function main()
    {
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'news archive'), '', 'https://telegram.me/papna');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'print leads'), '/leads');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'placements'), '/placements 0');
        $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'shows'), '/selectShow');
        if ($this->isExhibitor()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Survey'), '/survey');
        }

        if ($this->isUserOwner()) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Admin Access'), '/admin');
        }

        return $key;
    }

    public function questions()
    {
        $question = Question::findOne(['index' => $this->page]);
        $options = json_decode($question->options);

        foreach ($options as $num => $option) {
            $key[] = InlineKeyboardButton::setNewKeyButton(Yii::t('app_16', 'Admin Access'), '/survey ' . $this->page . ' ' . $num);
        }
    }

    /**
     * set each part keyboards
     * @param string $part
     * @param array $selectedButtons
     */
    public function setPartKeyboard($part, $selectedButtons = [], $page = 1)
    {
        $key = $this->$part();

        if ($selectedButtons === []) {
            $buttons = $key;
        } else {
            $buttons = [];
            foreach ($selectedButtons as $selectedButton) {
                $buttons[] = $key[$selectedButton];
            }
        }

        if (($part == 'getRecipes' || $part == 'deleteRecipe1') && count($buttons) > 5) {
            if ($page * 5 < count($buttons)) {
                $limit = $page * 5;
            } else {
                $limit = count($buttons) - 1;
            }
            for ($i = ($page - 1) * 5; $i < $limit; $i++) {
                $thisPageButtons[] = $buttons[$i];
            }
            $endButton = end($buttons);
            $n = count($buttons) - 1;
            unset($buttons[$n]);
            $pagination = $this->createPagination($buttons, $page, $part);
        } else {
            $thisPageButtons = $buttons;
        }

        $n = count($thisPageButtons);
        $keyboard = [];
        if ($part == 'placementsPage' || $part == 'leadsPage') {
            if (count($thisPageButtons) == 5) {
                $keyboard[] = [$buttons[0], $buttons[1]];
                $keyboard[] = [$buttons[2], $buttons[4]];
                $keyboard[] = [$buttons[3]];
            } elseif (count($thisPageButtons) == 4) {
                if ($this->isUserOwner()) {
                    $keyboard[] = [$buttons[0]];
                    $keyboard[] = [$buttons[1], $buttons[3]];
                    $keyboard[] = [$buttons[2]];
                } else {
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    $keyboard[] = [$buttons[2]];
                    $keyboard[] = [$buttons[3]];
                }
            } elseif (count($thisPageButtons) == 3) {
                if ($this->isUserOwner()) {
                    $keyboard[] = [$buttons[0], $buttons[2]];
                    $keyboard[] = [$buttons[1]];
                } else {
                    $keyboard[] = [$buttons[0]];
                    $keyboard[] = [$buttons[1]];
                    $keyboard[] = [$buttons[2]];
                }
            } else {
                $keyboard[] = [$buttons[0]];
                $keyboard[] = [$buttons[1]];
            }
        } elseif ($part == 'newsPage') {
            if (count($thisPageButtons) == 4) {
                $keyboard[] = [$buttons[0], $buttons[1]];
                $keyboard[] = [$buttons[2]];
                $keyboard[] = [$buttons[3]];
            } elseif (count($thisPageButtons) == 3) {
                if ($this->isUserOwner()) {
                    $keyboard[] = [$buttons[0]];
                    $keyboard[] = [$buttons[1]];
                    $keyboard[] = [$buttons[2]];
                } else {
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    $keyboard[] = [$buttons[2]];
                }
            } else {
                $keyboard[] = [$buttons[0]];
                $keyboard[] = [$buttons[1]];
            }
        } elseif ($part == 'setOptions') {
            switch (count($thisPageButtons)) {
                case 3:
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    break;
                case 4:
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    $keyboard[] = [$buttons[2]];
                    break;
                case 5:
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    $keyboard[] = [$buttons[2], $buttons[3]];
                    break;
                case 9:
                    $keyboard[] = [$buttons[0], $buttons[1]];
                    $keyboard[] = [$buttons[2], $buttons[3]];
                    $keyboard[] = [$buttons[4], $buttons[5]];
                    $keyboard[] = [$buttons[6], $buttons[7]];
                    break;
                default:
                    for ($i = 0; $i < $n - 1; $i++) {
                        $keyboard[] = [$buttons[$i]];
                    }
            }
            $keyboard[] = [$buttons[count($thisPageButtons) - 1]];
        } else {
            for ($i = 0; $i < $n; $i++) {
                $keyboard[] = [$thisPageButtons[$i]];
            }
        }

        if (isset($pagination) && $pagination !== false) {
            $keyboard[] = $pagination;
            $keyboard[] = [$endButton];
        }

        $this->setKeyboard([
            'inline_keyboard' => $keyboard
        ]);
    }

    public function createPagination($buttons, $page, $part)
    {
        if (floor(count($buttons) / 5) == (count($buttons) / 5)) {
            $pages = count($buttons) / 5;
        } else {
            $pages = floor(count($buttons) / 5) + 1;
        }

        if ($pages == 1) {
            return false;
        }

        $key = [];
        $categoryId = 'nc';
        $recipeId = 'nr';

        if ($pages > 5) {
            if ($page - 2 >= 1 && $page + 2 <= $pages) {
                if ($page == 3) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('1', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('2', '/keyboard ' . $part . ' ' . 2 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. 3 .', '/keyboard ' . $part . ' ' . 3 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                } elseif ($page == $pages - 2) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton((string) ($page + 1), '/keyboard ' . $part . ' ' . ($page + 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages, '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton(($page + 1) . ' >', '/keyboard ' . $part . ' ' . ($page + 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                }
            } elseif ($page - 1 >= 1 && $page + 1 <= $pages) {
                if ($page == 2) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('1', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('3', '/keyboard ' . $part . ' ' . 3 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton($pages . ' >>', '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($page - 2), '/keyboard ' . $part . ' ' . ($page - 2) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton((string) ($page - 1), '/keyboard ' . $part . ' ' . ($page - 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('. ' . $page . ' .', '/keyboard ' . $part . ' ' . $page . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages, '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                }
            } else {
                if ($page == 1) {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('. 1 .', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('2', '/keyboard ' . $part . ' ' . 2 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton('3', '/keyboard ' . $part . ' ' . 3 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton('4 >', '/keyboard ' . $part . ' ' . 4 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton((string) $pages . ' >>', '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                } else {
                    $key[0] = InlineKeyboardButton::setNewKeyButton('<< 1 ', '/keyboard ' . $part . ' ' . 1 . ' ' . $categoryId . ' ' . $recipeId);
                    $key[1] = InlineKeyboardButton::setNewKeyButton('< ' . ($pages - 3), '/keyboard ' . $part . ' ' . ($pages - 3) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[2] = InlineKeyboardButton::setNewKeyButton((string) ($pages - 2), '/keyboard ' . $part . ' ' . ($pages - 2) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[3] = InlineKeyboardButton::setNewKeyButton((string) ($pages - 1), '/keyboard ' . $part . ' ' . ($pages - 1) . ' ' . $categoryId . ' ' . $recipeId);
                    $key[4] = InlineKeyboardButton::setNewKeyButton('. ' . $pages . ' .', '/keyboard ' . $part . ' ' . $pages . ' ' . $categoryId . ' ' . $recipeId);
                }
            }
        } else {
            for ($i = 1; $i <= $pages; $i++) {
                if ($i == $page) {
                    $key[] = InlineKeyboardButton::setNewKeyButton('. ' . $i . ' .', '/keyboard ' . $part . ' ' . $i . ' ' . $categoryId . ' ' . $recipeId);
                } else {
                    $key[] = InlineKeyboardButton::setNewKeyButton((string) $i, '/keyboard ' . $part . ' ' . $i . ' ' . $categoryId . ' ' . $recipeId);
                }
            }
        }
        return $key;
    }

    public function getFilePath($fileId)
    {
        $path = Yii::getAlias('@frontend/web/attachments/');
        $file = glob($path . $fileId . '.*')[0];
        $ext = explode('.', $file);
        $fileName = $fileId . '.' . end($ext);

        return Html::a('&#160;', 'https://redis.tiva-network.com/attachments/' . $fileName);
    }

    public function sendMessage($text, $replyMessageId = null)
    {
        $text = $text . "\n\n\n" . Yii::t('app_16', 'signature text');
        return parent::sendMessage($text, $replyMessageId);
    }

    public function beforeExecute()
    {
        if ($this->pattern !== 'sendNews' && $this->isReply && isset($this->getReply()['message'])) {
            $news = News::findOne(['id' => $this->getReply()['id']]);
            $news->delete();
            $this->killReply();
        }
        return parent::beforeExecute();
    }
}
