<?php

use common\models\site\Category;
use frontend\widgets\content\ContentWidget;

/* @var $this yii\web\View */

/**
 * Produce a nearly white random color
 * @return string
 */
function getColor() {
    return '#' . dechex(mt_rand(220, 255)) . dechex(mt_rand(220, 255)) . dechex(mt_rand(220, 255));
}

$i = -1;
$size = 0;
$this->title = Yii::t('app_site', 'نهایت توسعه | صفحه اصلی');
$cats = Category::find()->all();
foreach ($cats as $category) {
    $size += $category->box->size;
}
$n = (int) ceil($size / 4);
?>
<div id="top"></div>
<div id="services">
    <?= ContentWidget::widget(['title' => 'jobs', 'id' => ++$i, 'type' => 'h-slide']) ?>
    <?= ContentWidget::widget(['title' => 'logo', 'id' => ++$i, 'type' => 'image']) ?>
    <?= ContentWidget::widget(['title' => 'features', 'id' => ++$i, 'type' => 'image']) ?>
    <?= ContentWidget::widget(['title' => 'social', 'id' => ++$i, 'type' => 'square']) ?>
    <?= ContentWidget::widget(['title' => 'portfolio', 'id' => ++$i, 'type' => 'slide']) ?>
    <?= ContentWidget::widget(['title' => 'about', 'id' => ++$i, 'type' => 'image']) ?>
</div>

<div class="afterContent">
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-lg-offset-1 text-center">
                    <h4><strong>Ultimate Developers</strong>
                    </h4>
                    <p>Tehran, Iran</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-phone fa-fw"></i> +98 933 6365 162</li>
                        <li><i class="fa fa-envelope-o fa-fw"></i> <a href="mailto:ultimate.developers.94@gmail.com">info@ultimatedevelopers.ir</a>
                        </li>
                    </ul>
                    <br>
                    <ul class="list-inline">
                        <li>
                            <a target="_blank" href="https://t.me/ultimate_developer"><i class="fa fa-telegram fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://instagram.com/__ultimate_developer__"><i class="fa fa-instagram fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://twitter.com/ultidev"><i class="fa fa-twitter fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="https://facebook.com/ultimate.developer"><i class="fa fa-facebook fa-fw fa-3x"></i></a>
                        </li>
                    </ul>
                    <hr class="small">
                    <p class="text-muted">Copyright &copy; Ultimate Developers 2017</p>
                </div>
            </div>
        </div>
        <a id="to-top" href="#top" class="btn btn-dark btn-lg"><i class="fa fa-chevron-up fa-fw fa-1x"></i></a>
    </footer>
</div>
<script>
    var n = <?=$i?>;
    var size = <?=$size?>;
    var lines = <?=$n?>;
    var sortedArr = [
        <?php
        for ($j = 0; $j < $n - 1; $j++) {
            echo '[0, 0, 0, 0],';
        }
        echo '[0, 0, 0, 0]';?>
    ];
</script>