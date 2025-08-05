<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PemohonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simpan Berkas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-index">

    <h1><?= Html::encode($this->title) ?></h1>

    
<div class="pemohon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['simpan'],
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
if($tersimpan == true)
{
    echo $this->render('_view-ambil', [
        'model' => $model,
    ]);

}else{

    
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
    }else{
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
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

    $model2 = new \app\models\SaveRakForm();
    $model2->nopermohonan = $model['nopermohonan'];
    $model2->sendsms = 0;

    $form2 = ActiveForm::begin(['method' => 'POST', 'action' => 'save-to-rak']);

    echo $form2->field($model2, 'nopermohonan')->hiddenInput()->label(false);
    echo $form2->field($model2, 'sendsms')->hiddenInput()->label(false);
    echo $form2->field($model2, 'sendsms')->checkbox();

    echo Html::submitButton('<i class="glyphicon glyphicon-floppy-save"></i> Simpan ke Loker', ['class' => 'btn btn-success', 'data' => [
                'confirm' => 'Are you sure you want to save this item?',
                'method' => 'post',
            ]
        ]);
    /*echo Html::a('<i class="glyphicon glyphicon-floppy-save"></i> Simpan ke Loker', ['save-to-rak', 'nopermohonan' => $model['nopermohonan'] ], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Are you sure you want to save this item?',
                'method' => 'post',
            ],
        ]); */

    ActiveForm::end();
    } 
} //End if Tersimpan

    ?>


    <?php Pjax::end(); ?>

</div>
