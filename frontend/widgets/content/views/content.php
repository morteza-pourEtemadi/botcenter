<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\site\Box;
use common\models\site\Content;
use common\models\site\Category;

/* @var int $id */
/* @var string $title */

$category = Category::findOne(['slug' => $title]);
$content = Content::findOne(['category_id' => $category->id]); ?>

<div id="<?=$id?>" class="box <?=$category->box->title?>">
    <?php if (isset($content->image) && $content->image != '') { ?>
        <img src="<?= $content->image ?>">
        <div class="imageContent">
            <p class="imgT"><?= $content->title ?></p>
            <p class="imgD"><?= $content->description ?></p>
        </div>
    <?php } else { ?>
        <div class="justColor" about="<?=$content->color?>">
            <p class="title"><?= $content->title ?></p> <br>
            <p class="description"><?= $content->description ?></p>
        </div>
    <?php } ?>
</div>