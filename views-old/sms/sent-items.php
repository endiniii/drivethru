<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sent Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sent-items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'UpdatedInDB',
            // 'InsertIntoDB',
            'SendingDateTime',
            // 'DeliveryDateTime',
            // 'Text:ntext',
            // 'DestinationNumber',
            [
                'attribute' => 'DestinationNumber',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->DestinationNumber, ['/sms/view-sent-item', 'ID' => $model->ID, 'SequencePosition' => $model->SequencePosition]);
                }
            ],
            //'Coding',
            //'UDH:ntext',
            //'SMSCNumber',
            //'Class',
            'TextDecoded:ntext',
            //'ID',
            //'SenderID',
            //'SequencePosition',
            'Status',
            //'StatusError',
            //'TPMR',
            //'RelativeValidity',
            //'CreatorID:ntext',
            // 'StatusCode',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>