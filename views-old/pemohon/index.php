<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PemohonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pemohon';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //= Html::a('Create Pemohon', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'nopermohonan',
            [
                'attribute' => 'nopermohonan',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->nopermohonan, ['/pemohon/view', 'id' => $model->id]);
                }
            ],
            'nama',
            // 'tempatlahir',
            'tanggallahir',
            //'jeniskelamin',
            //'notelepon',
            'nopaspor',
            //'kodebilling',
            [
                'attribute' => 'koderak',
                'label' => 'Kode Loker',
                'value' => 'rak.namarak'
            ],
            'tanggalsimpan',
            //'tanggalambil',
            // 'smsgateway',
            [
                'attribute' => 'smsgateway',
                'format' => 'raw',
                'value' => function ($model) {
                    if($model->smsgateway == false){
                        return '<span class="label label-danger">No SMS</span>';
                    }else{
                        $keyword = 'smsnotif-'. $model->id;
                        $outbox = \app\models\Outbox::find()->where(['CreatorID' => $keyword])->one();
                        $sentitems = \app\models\SentItems::find()->where(['CreatorID' => $keyword])->orderBy(['SendingDateTime' => SORT_DESC])->one();

                        if($outbox !== null){
                            return '<span class="label label-warning">Pending</span>';
                        }
                        else if($sentitems !== null)
                        {
                            return ($sentitems->Status == 'SendingOKNoReport') ? '<span class="label label-success">'.$sentitems->Status.'</span>' : '<span class="label label-danger">'.$sentitems->Status.'</span>';
                        } else {
                            return '<span class="label label-danger">Tidak Diketahui</span>';
                        }

                    }
                }
            ],
            //'koderak',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
