<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="مخزن مورد علاقه شما از کدهای خوب">
    <meta name="keywords" content="کسب و کار,وبسایت,طراحی,سایت,طراحی سایت,ربات,تلگرام,ربات تلگرام,برنامه نویسی,آن لاین,تکنولوژی,ارزان,محصول,شبکه اجتماعی,مجازی,بات,وب,وبولوژی,مخزن,توسعه نهایی,نهایت توسعه,نهایی,توسعه,کد">
    <meta name="author" content="Ultimate Developer">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" type="image/x-icon" href="faveicon.ico">
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
<script>
    $(document).ready(function () {
        <?php
        if (Yii::$app->session->getFlash('success')) { ?>
        alert('    پیام شما دریافت شده و در اسرع وقت به آن پاسخ داده خواهد شد.');
        <?php } ?>
    })
</script>
</html>
<?php $this->endPage() ?>
