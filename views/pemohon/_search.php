<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PemohonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pemohon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nopermohonan') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'tempatlahir') ?>

    <?= $form->field($model, 'tanggallahir') ?>

    <?php // echo $form->field($model, 'jeniskelamin') ?>

    <?php // echo $form->field($model, 'notelepon') ?>

    <?php // echo $form->field($model, 'nopaspor') ?>

    <?php // echo $form->field($model, 'kodebilling') ?>

    <?php // echo $form->field($model, 'tanggalsimpan') ?>

    <?php // echo $form->field($model, 'tanggalambil') ?>

    <?php // echo $form->field($model, 'smsgateway') ?>

    <?php // echo $form->field($model, 'koderak') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
