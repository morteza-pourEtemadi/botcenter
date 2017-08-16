<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Content;
use common\models\Category;

/* @var $this yii\web\View */

/**
 * Produce a nearly white random color
 * @return string
 */
function getColor() {
    return '#' . dechex(mt_rand(220, 255)) . dechex(mt_rand(220, 255)) . dechex(mt_rand(220, 255));
}

$this->title = 'Ario Fab';
$i = -1;
$size = 0;
?>

<div id="services" class="row">
    <?php
    if ($sls = Category::findAll(['type' => Category::TYPE_SLIDE])) {
        $i++;
        $size += 9;
        $msc = Content::findAll(['category_id' => $sls[0]->id]); ?>
        <div id="<?=$i?>" class="mainSlider">
            <div class="sliderNav">
                <ul>
                    <?php foreach ($msc as $item) { ?>
                        <li class="slc" about="<?=$item->category_id . '-' . $item->id?>"><span><?= $item->title ?></span></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="sliderMain">
                <?php foreach ($msc as $item) { ?>
                    <img src="files/slides/<?=$item->image?>" class="sliderPic" id="<?=$item->category_id . '-' . $item->id?>">
                    <div class="imageContent" about="<?=$item->category_id . '-' . $item->id?>">
                        <p class="imgT"><?= $item->title ?></p>
                        <span class="imgD"><?= $item->description ?></span>
                        <?= Html::button('ثبت سفارش', ['value' => Url::to(['site/contact', 'id' => $sls[0]->id . '/' . $item->id]), 'title' => 'ثبت سفارش/تماس با ما', 'class' => 'imageLink showModalButton']); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php
        if (isset($sls[1])) {
            $i++;
            $size += 4;
            $msc = Content::findAll(['category_id' => $sls[1]->id]); ?>
            <div id="<?=$i?>" class="secondSlider">
                <div class="sliderNav">
                    <ul>
                        <?php foreach ($msc as $item) { ?>
                            <li class="slc" about="<?=$item->category_id . '-' . $item->id?>"><span><?= $item->title ?></span></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="sliderMain">
                    <?php foreach ($msc as $item) { ?>
                        <img src="files/slides/<?=$item->image?>" class="sliderPic" id="<?=$item->category_id . '-' . $item->id?>">
                        <div class="imageContent" about="<?=$item->category_id . '-' . $item->id?>">
                            <p class="imgT"><?= $item->title ?></p>
                            <span class="imgD"><?= $item->description ?></span>
                            <?= Html::button('ثبت سفارش', ['value' => Url::to(['site/contact', 'id' => $sls[1]->id . '/' . $item->id]), 'title' => 'ثبت سفارش/تماس با ما', 'class' => 'imageLink showModalButton']); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
    }
    $ocs = Category::find()->where(['type' => Category::TYPE_IMAGE])->orderBy(['box_size' => SORT_DESC])->all();
    foreach ($ocs as $oc) {
        $csz = $oc->box_size;
        $className = 'box' . $csz;
        $contents = Content::findAll(['category_id' => $oc->id]);
        foreach ($contents as $content) {
            $i++;
            switch ($csz) {
                case '1':
                    $size += 2;
                    break;
                case '2':
                    $size += 3;
                    break;
                case '3':
                    $size += 6;
                    break;
                default:
                    $size += 8;
            } ?>
            <div id="<?=$i?>" class="<?=$className?>">
                <?php if (isset($content->image) && $content->image != '') { ?>
                    <img src="files/slides/<?= $content->image ?>">
                    <div class="imageContent">
                        <p class="imgT"><?= $content->title ?></p>
                        <p class="imgD"><?= $content->description ?></p>
                        <?= Html::button('ثبت سفارش/تماس با ما', ['value' => Url::to(['site/contact', 'id' => $oc->id . '/' . $content->id]), 'title' => 'ثبت سفارش/تماس با ما', 'class' => 'imageLink showModalButton']); ?>
                    </div>
                <?php } else { ?>
                    <div class="justColor" about="<?=$content->color?>">
                        <p class="title"><?= $content->title ?></p> <br>
                        <p class="description"><?= $content->description ?></p>
                        <?= Html::button('ثبت سفارش/تماس با ما', ['value' => Url::to(['site/contact', 'id' => $oc->id . '/' . $content->id]), 'title' => 'ثبت سفارش/تماس با ما', 'class' => 'contentLink showModalButton']); ?>
                    </div>
                <?php } ?>
            </div>
        <?php }
    }
    $n = (int) ceil($size / 4);
    ?>
</div>
<div class="clear"></div>
<script>
    size = <?=$size?>;
    lines = <?=$n?>;
    n = <?=$i?>;
    var sortedArr = [
        <?php
        for ($j = 0; $j < $n - 1; $j++) {
            echo '[0, 0, 0, 0],';
        }
        echo '[0, 0, 0, 0]';?>
    ];
</script>