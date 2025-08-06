<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'MPP - Cilacap';
$this->params['breadcrumbs'][] = ['label' => 'Cilacap', 'url' => ['#']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-mpp">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Download Excel', ['export-excel', 'lokasi' => 'cilacap', 'sub_lokasi' => 'mpp'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'nopermohonan',
                'nama',
                'tempatlahir',
                'tanggallahir',
                'jeniskelamin',
                'nopaspor',
                'kodebilling',
                'tanggalsimpan',
                // Tambah kolom lain sesuai kebutuhan
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
