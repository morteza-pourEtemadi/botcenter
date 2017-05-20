<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/email-verify', 'verification' => $user->emailVerifier()]);
?>
<div class="password-reset">
    <p class="text-center"><?= Yii::t('app', 'Welcome to Ultimate Developers,') . "\n" ?>,</p>

    <p><?= Yii::t('app', 'First, please confirm your email address. Then you can complete your profile data.') ?></p>

    <p><?= Html::a(Yii::t('app', 'Confirm your email address'), $resetLink, ['class' => 'btn btn-primary']) ?></p>
</div>
