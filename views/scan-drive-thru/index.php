<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PemohonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Pengambilan Berkas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'pemohon.nama',
            'pemohon.nopaspor',
            [
                'attribute' => 'pemohon.rak.namarak',
                'label' => 'Lokasi Rak',
            ],
            'tanggalscan',
            'status',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
