<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= Yii::t('app', 'Profile of ') . Html::encode($user->username) ?></h2>
    <hr>
    <div class="center-block">
        <img src="<?= 'profile-images' . ($user->avatar != '' ? $user->avatar : 'unknown.png') ?>" width="150px" height="150px">
        <?= Html::a(Yii::t('app', 'Change Avatar'), '#', ['onclick' => 'tryUpload();']) ?>
        <input type="file" name="avatar" id="avatar" class="hidden"/>
    </div>
</div>

<script>
    function tryUpload()
    {
        var uploader = document.getElementById('avatar');
        uploader.click();
        return false;
    }
</script>