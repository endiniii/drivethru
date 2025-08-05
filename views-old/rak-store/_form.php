<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RakStore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rak-store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idpemohon')->textInput() ?>

    <?= $form->field($model, 'idrak')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
