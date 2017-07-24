<?php

namespace console\controllers\botId_2;

use common\models\bot\Subscribers;
use yii\console\Controller;
use common\models\bot\botId_2\User;
use common\models\bot\botId_2\Hadith;
use common\models\bot\botId_2\Narratives;

/**
 * Class DatabaseController
 * @package console\controllers
 */
class DatabaseController extends Controller
{
    public function actionUniqueFix()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            /* @var User $user */
            if ($user->getUniqueUser() == null) {
                $subscriber = new Subscribers([
                    'user_id' => $user->user_id,
                    'bot_id' => 2,
                ]);
                $subscriber->save();
            }
        }
    }

    public function actionHadith()
    {
        $narratives = Narratives::find()->all();
        foreach ($narratives as $narrative) {
            /* @var Narratives $narrative*/
            $hadith = new Hadith([
                'index' => $narrative->id,
                'quoter' => $narrative->quotee,
                'quoter_trans' => $narrative->quoteeTranslation,
                'quote' => $narrative->quote,
                'quote_trans' => $narrative->quoteTranslation,
                'source' => $narrative->source,
            ]);
            $this->stdout($hadith->index . ' -> ');
            $this->stdout($hadith->save());
            $this->stdout("\n");
        }
        $this->stdout("All Done");
    }
}