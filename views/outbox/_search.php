<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OutboxSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outbox-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'UpdatedInDB') ?>

    <?= $form->field($model, 'InsertIntoDB') ?>

    <?= $form->field($model, 'SendingDateTime') ?>

    <?= $form->field($model, 'SendBefore') ?>

    <?= $form->field($model, 'SendAfter') ?>

    <?php // echo $form->field($model, 'Text') ?>

    <?php // echo $form->field($model, 'DestinationNumber') ?>

    <?php // echo $form->field($model, 'Coding') ?>

    <?php // echo $form->field($model, 'UDH') ?>

    <?php // echo $form->field($model, 'Class') ?>

    <?php // echo $form->field($model, 'TextDecoded') ?>

    <?php // echo $form->field($model, 'ID') ?>

    <?php // echo $form->field($model, 'MultiPart') ?>

    <?php // echo $form->field($model, 'RelativeValidity') ?>

    <?php // echo $form->field($model, 'SenderID') ?>

    <?php // echo $form->field($model, 'SendingTimeOut') ?>

    <?php // echo $form->field($model, 'DeliveryReport') ?>

    <?php // echo $form->field($model, 'CreatorID') ?>

    <?php // echo $form->field($model, 'Retries') ?>

    <?php // echo $form->field($model, 'Priority') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'StatusCode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
