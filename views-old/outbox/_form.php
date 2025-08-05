<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Outbox */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outbox-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'UpdatedInDB')->textInput() ?>

    <?= $form->field($model, 'InsertIntoDB')->textInput() ?>

    <?= $form->field($model, 'SendingDateTime')->textInput() ?>

    <?= $form->field($model, 'SendBefore')->textInput() ?>

    <?= $form->field($model, 'SendAfter')->textInput() ?>

    <?= $form->field($model, 'Text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'DestinationNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Coding')->dropDownList([ 'Default_No_Compression' => 'Default No Compression', 'Unicode_No_Compression' => 'Unicode No Compression', '8bit' => '8bit', 'Default_Compression' => 'Default Compression', 'Unicode_Compression' => 'Unicode Compression', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'UDH')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Class')->textInput() ?>

    <?= $form->field($model, 'TextDecoded')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'MultiPart')->dropDownList([ 'false' => 'False', 'true' => 'True', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'RelativeValidity')->textInput() ?>

    <?= $form->field($model, 'SenderID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SendingTimeOut')->textInput() ?>

    <?= $form->field($model, 'DeliveryReport')->dropDownList([ 'default' => 'Default', 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'CreatorID')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Retries')->textInput() ?>

    <?= $form->field($model, 'Priority')->textInput() ?>

    <?= $form->field($model, 'Status')->dropDownList([ 'SendingOK' => 'SendingOK', 'SendingOKNoReport' => 'SendingOKNoReport', 'SendingError' => 'SendingError', 'DeliveryOK' => 'DeliveryOK', 'DeliveryFailed' => 'DeliveryFailed', 'DeliveryPending' => 'DeliveryPending', 'DeliveryUnknown' => 'DeliveryUnknown', 'Error' => 'Error', 'Reserved' => 'Reserved', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'StatusCode')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
