<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/email-verify', 'verification' => $user->emailVerifier()]);
?>

<?= Yii::t('app', 'Welcome to Ultimate Developers,') . "\n"?>
<?= Yii::t('app', 'First, please confirm your email address. Then you can complete your profile data.') ?>

<?= $resetLink ?>
