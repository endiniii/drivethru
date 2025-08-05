<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Outbox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php /*$form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]);*/ ?>
    <?php $form = ActiveForm::begin(); ?>

    <?php /*= $form->field($model, 'UpdatedInDB')->textInput() ?>

    <?= $form->field($model, 'InsertIntoDB')->textInput() ?>

    <?= $form->field($model, 'SendingDateTime')->textInput() ?>

    <?= $form->field($model, 'SendBefore')->textInput() ?>

    <?= $form->field($model, 'SendAfter')->textInput() ?>

    <?= $form->field($model, 'Text')->textarea(['rows' => 6]) */ ?>

    <?= $form->field($model, 'DestinationNumber')->hiddenInput()->label(false) ?>

    <?php /*= $form->field($model, 'Coding')->dropDownList([ 'Default_No_Compression' => 'Default No Compression', 'Unicode_No_Compression' => 'Unicode No Compression', '8bit' => '8bit', 'Default_Compression' => 'Default Compression', 'Unicode_Compression' => 'Unicode Compression', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'UDH')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Class')->textInput() */ ?>

    <?php //= $form->field($model, 'TextDecoded')->textarea(['rows' => 6])->label('Message') ?>

    <?php //= $form->field($model, 'MultiPart')->dropDownList([ 'false' => 'False', 'true' => 'True', ], ['prompt' => '- Multipart Options -']) ?>

    <?php /*= $form->field($model, 'RelativeValidity')->textInput() ?>

    <?= $form->field($model, 'SenderID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SendingTimeOut')->textInput() ?>

    <?= $form->field($model, 'DeliveryReport')->dropDownList([ 'default' => 'Default', 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'CreatorID')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Retries')->textInput() ?>

    <?= $form->field($model, 'Priority')->textInput() ?>

    <?= $form->field($model, 'Status')->dropDownList([ 'SendingOK' => 'SendingOK', 'SendingOKNoReport' => 'SendingOKNoReport', 'SendingError' => 'SendingError', 'DeliveryOK' => 'DeliveryOK', 'DeliveryFailed' => 'DeliveryFailed', 'DeliveryPending' => 'DeliveryPending', 'DeliveryUnknown' => 'DeliveryUnknown', 'Error' => 'Error', 'Reserved' => 'Reserved', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'StatusCode')->textInput() */ ?>
    <?= $form->field($model, 'CreatorID')->hiddenInput()->label(false) ?>
    <div class="col-md-8"><label class="control-label" for="outbox-textdecoded">Message</label></div>
    <div class="col-md-4"></div>

    <div class="col-md-11">
        <?= $form->field($model, 'TextDecoded')->textarea(['rows' => 4])->label(false) ?>
    </div>

    <div class="col-md-1">
        <?= Html::submitButton('<i class="glyphicon glyphicon-send"></i> Send', ['class' => 'btn btn-success']) ?>
    </div>

    <!--div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div-->

    <?php ActiveForm::end(); ?>

</div>
