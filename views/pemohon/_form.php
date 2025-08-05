<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pemohon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pemohon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nopermohonan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tempatlahir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggallahir')->textInput() ?>

    <?= $form->field($model, 'jeniskelamin')->dropDownList([ 'LAKI-LAKI' => 'LAKI-LAKI', 'PEREMPUAN' => 'PEREMPUAN', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'notelepon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nopaspor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kodebilling')->textInput() ?>

    <?= $form->field($model, 'tanggalsimpan')->textInput() ?>

    <?= $form->field($model, 'tanggalambil')->textInput() ?>

    <?= $form->field($model, 'smsgateway')->textInput() ?>

    <?= $form->field($model, 'koderak')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'tersimpan' => 'Tersimpan', 'diambil' => 'Diambil', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
