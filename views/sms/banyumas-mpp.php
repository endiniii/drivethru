<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['banyumas-mpp'], // arahkan ke actionBanyumasMpp
]);

echo $form->field($searchModel, 'nopermohonan');
echo Html::submitButton('Cari', ['class' => 'btn btn-primary']);

ActiveForm::end();
?>
