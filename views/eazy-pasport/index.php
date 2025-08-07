<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Eazy Pasport';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['simpan'],
]); ?>

<?= $form->field(new \yii\base\DynamicModel(['tempat']), 'tempat')->textInput()->label('Nama Tempat') ?>

<div class="form-group">
    <?= Html::submitButton('Lanjut', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
