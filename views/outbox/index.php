<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OutboxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Outboxes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outbox-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Outbox', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'UpdatedInDB',
            'InsertIntoDB',
            'SendingDateTime',
            'SendBefore',
            'SendAfter',
            //'Text:ntext',
            //'DestinationNumber',
            //'Coding',
            //'UDH:ntext',
            //'Class',
            //'TextDecoded:ntext',
            //'ID',
            //'MultiPart',
            //'RelativeValidity',
            //'SenderID',
            //'SendingTimeOut',
            //'DeliveryReport',
            //'CreatorID:ntext',
            //'Retries',
            //'Priority',
            //'Status',
            //'StatusCode',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
