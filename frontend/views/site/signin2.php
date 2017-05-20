<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SigninForm2 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to complete sign up:') ?> </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'bio')->textarea(['autofocus' => true]) ?>

                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '0999-999-9999',
                ]) ?>

                <?= $form->field($model, 'address')->textarea() ?>

                <img id="preview_image" src="profile-images/unknown.png" width="150px" height="150px"/>

                <?= $form->field($model, 'avatar')->fileInput(['onChange' => 'preview(this);']) ?>

            <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Finish >>'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    function preview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview_image')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(120);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>