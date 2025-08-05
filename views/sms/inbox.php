<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PemohonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inbox';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-inbox">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //= Html::a('Create Pemohon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' => ['class' => 'text-center'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'nopermohonan',
            [
                'attribute' => 'SenderNumber',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->SenderNumber, ['/sms/view', 'ID' => $model->ID]);
                }
            ],
            [
                'attribute' => 'ReceivingDateTime',
                'label' => 'Receiving Time',
            ],
            'TextDecoded:ntext',
            // 'Processed',
            [
                'attribute' => 'Processed',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function($model){
                    if($model->Processed == 'true')
                    {
                        return '<i class="glyphicon glyphicon-ok"></i>';
                    }else{
                        return '<i class="glyphicon glyphicon-remove"></i>';
                    }
                    
                }
            ],
            [
                'header' => 'Reply',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function($model){
                    return Html::a('<i class="glyphicon glyphicon-glyphicon glyphicon-share-alt"></i>', ['/sms/reply', 'SenderNumber' => $model->SenderNumber, 'InboxID' => $model->ID]);
                    
                    
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
