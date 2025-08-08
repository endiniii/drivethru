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
            'attribute' => 'smsgateway', // atau ganti dengan kolom baru pesan_wa kalau sudah ada
            'label' => 'Pesan WA',
            'format' => 'raw',
            'value' => function ($model) {
                // Kalau belum pernah kirim WA
                if ($model->smsgateway == false) {
                    return '<span class="label label-danger">Belum Dikirim</span>';
                }

                // Kalau sudah pernah kirim WA
                if ($model->smsgateway == 'pending') {
                    return '<span class="label label-warning">Pending</span>';
                }

                if ($model->smsgateway == 'terkirim') {
                    return '<span class="label label-success">Terkirim</span>';
                }

                // Default kalau status tidak dikenali
                return '<span class="label label-default">Tidak Diketahui</span>';
            }
        ],

            //'koderak',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
