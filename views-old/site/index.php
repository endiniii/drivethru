<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */

$this->title = 'Dashboard | Drive THRU application';
?>
<div class="site-index">
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading text-center">Total Pemohon</div>
            <div class="panel-body text-center"><h1><?=$total_pemohon?></h1></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading text-center">Slot Berkas</div>
            <div class="panel-body text-center"><h1><?=$total_slot?></h1></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading text-center">Belum Diambil</div>
            <div class="panel-body text-center"><h1><?=$blm_diambil?></h1></div>
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Status Penyimpanan Berkas</div>
                <div class="panel-body">
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$persentase?>"
                      aria-valuemin="0" aria-valuemax="100" style="width:<?=$persentase?>%">
                        <?=$persentase?>% Terisi
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Pemohon Terakhir</div>
                <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderPemohon,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // 'id',
                            'nopermohonan',
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
                            [
                                'attribute' => 'smsgateway',
                                'label' => 'SMS',
                                'format' => 'raw',
                                'visible' => false,
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
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model){
                                    $css_class = ($model->status == 'tersimpan') ? 'success' : 'primary';

                                    return '<span class="label label-'.$css_class.'">'.strtoupper($model->status).'</span>';

                                }
                            ],
                            // 'status',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
        
    </div>
<?php /*
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Statistik Per Hari</div>
                <div class="panel-body">
                    <?=
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'bar'
        ],
        'title' => [
             'text' => 'Pengambilan Paspor'
             ],
        'xAxis' => [
            'categories' => [
                'Januari',
                'Februari',
                'Maret'
            ]
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Jumlah berkas'
            ]
        ],
        'series' => [
            ['name' => 'Tersimpan', 'data' => [11, 20, 14]],
            ['name' => 'Pengambilan', 'data' => [9, 17, 10]]
        ]
    ]
]); ?>
                </div>
            </div>
        </div>
        
    </div> */ ?>

    
</div>
