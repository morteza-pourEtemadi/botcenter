<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\site\Box;
use common\models\site\Content;
use common\models\site\Category;

/* @var int $id */
/* @var string $title */

$sCategory = Category::findOne(['slug' => $title]);
$sContents = Content::findAll(['category_id' => $sCategory->id]); ?>
<div id="<?=$id?>" class="box <?=$sCategory->box->title?>">
    <?php
    foreach ($sContents as $key => $content) { ?>
        <div class="square quarter<?=($key + 1)?>" about="<?=$content->color?>">
            <a href="<?=$content->description?>" target="_blank" class="socialLink"><?=$content->title?></a>
        </div>
    <?php } ?>
</div>
