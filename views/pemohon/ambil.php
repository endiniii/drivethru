<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PemohonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ambil Berkas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    
<div class="pemohon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ambil'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <?= $form->field($searchModel, 'nopermohonan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php Pjax::begin(); ?>
    
    <?php
    if ($model == null) {
        if(\Yii::$app->request->get()){
            // echo 'Permohonan tidak ditemukan';
            echo '<div class="alert alert-danger alert-dismissable">
                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                 <h4><i class="icon fa fa-check"></i>Ow, Snap!</h4>
                 Permohonan tidak ditemukan.
            </div>';
        }
        
        // var_dump($query);
    }
    elseif($model['status'] == 'diambil')
    {
        // echo 'Berkas sudah diambil';
        echo '<div class="alert alert-danger alert-dismissable">
                 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                 <h4><i class="icon fa fa-check"></i>Ow, Snap!</h4>
                 Berkas a.n <b>'.$model['nama'].'</b> sudah diambil pada '.\Yii::$app->formatter->asDateTime($model['tanggalambil']).'.
            </div>';
    }else{
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'koderak',
            [
                'label' => 'Lokasi Loker',
                'format' => 'raw',
                'value' => function ($model){
                    $rak = \app\models\Rak::findOne($model['koderak']);

                    return '<h1>'.$rak->namarak . '</h1>';
                }
            ],
            'nopermohonan',
            'nama',
            'tempatlahir',
            'tanggallahir',
            'jeniskelamin',
            'notelepon',
            'nopaspor',
            'kodebilling',
            // 'tanggalsimpan',
            // 'tanggalambil',
            // 'smsgateway',
            // 'koderak',
            // 'status',
        ],
    ]); 

    echo Html::a('<i class="glyphicon glyphicon-arrow-up"></i> Ambil Berkas', ['get-from-rak', 'nopermohonan' => $model['nopermohonan'] ], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Anda yakin akan mengambil berkas ini?',
                'method' => 'post',
            ],
        ]);
    
    } ?>

    <?php Pjax::end(); ?>

</div>
