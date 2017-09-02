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
    <div class="sliderNav">
        <ul>
            <?php foreach ($sContents as $item) { ?>
                <li class="slc" about="<?=$item->category_id . '-' . $item->id?>"><span><?= $item->title ?></span></li>
            <?php } ?>
        </ul>
    </div>
    <div class="sliderMain">
        <?php foreach ($sContents as $item) {
            if ($item->image != '') { ?>
                <img src="<?=$item->image?>" class="sliderPic" id="<?=$item->category_id . '-' . $item->id?>">
                <div class="imageContent" about="<?=$item->category_id . '-' . $item->id?>">
                    <p class="imgT"><?= $item->title ?></p>
                    <span class="imgD"><?= $item->description ?></span>
                </div>
            <?php } else { ?>
                <div about="<?=$item->color?>" class="sliderPic justColor" id="<?=$item->category_id . '-' . $item->id?>">
                    <p class="title"><?= $item->title ?></p> <br>
                    <p class="description"><?= $item->description ?></p>
                </div>
            <?php }
        } ?>
    </div>
</div>
