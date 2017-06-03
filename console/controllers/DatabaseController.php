<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\bot\botId_2\Text;
use common\models\bot\botId_2\Quran;
use common\models\bot\botId_2\Translate;

/**
 * Class DatabaseController
 * @package console\controllers
 */
class DatabaseController extends Controller
{
    public function actionQuranSettings()
    {
        $xml = simplexml_load_file("console/controllers/quran.xml") or die("Error: Cannot create object");

        /* @var Text[] $ayat */
        $ayat = Text::find()->all();

        $thisPage = [1, 1, 1];
        foreach ($ayat as $aya) {
            $aya->surah = (string) $xml->suras->sura[$aya->sura - 1]['name'];

            $xmlPage = $xml->pages->page[$thisPage[0]];
            $nextPage = [(int) $xmlPage['index'], (int) $xmlPage['sura'], (int) $xmlPage['aya']];

            if (($aya->sura == $nextPage[1] && $aya->aya < $nextPage[2]) || $aya->sura < $nextPage[1]) {
                $aya->page = $thisPage[0];
            } else {
                $thisPage = $nextPage;
                $aya->page = $thisPage[0];
            }
            $tr = Translate::findOne(['index' => $aya->index]);
            $quran = new Quran([
                'index' => $aya->index,
                'sura' => $aya->surah,
                'suraNum' => $aya->sura,
                'aya' => $aya->aya,
                'text' => $aya->text,
                'translation' => $tr->text,
                'page' => $aya->page,
            ]);
            $this->stdout($aya->index . " - \n");
            $this->stdout($aya->save());
            $this->stdout("  -  ");
            $this->stdout($quran->save());
            $this->stdout("\n");
        }
        exit();
    }
}