<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Outbox */

$this->title = $model->DestinationNumber;
$this->params['breadcrumbs'][] = ['label' => 'Outboxes', 'url' => ['/sms/outbox']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="outbox-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php /*= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'UpdatedInDB',
            'InsertIntoDB',
            'SendingDateTime',
            'SendBefore',
            'SendAfter',
            'Text:ntext',
            'DestinationNumber',
            'Coding',
            'UDH:ntext',
            'Class',
            'TextDecoded:ntext',
            'ID',
            'MultiPart',
            'RelativeValidity',
            'SenderID',
            'SendingTimeOut',
            'DeliveryReport',
            'CreatorID:ntext',
            'Retries',
            'Priority',
            'Status',
            'StatusCode',
        ],
    ]) ?>

</div>
