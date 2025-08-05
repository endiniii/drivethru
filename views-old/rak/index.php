<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RakSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Slot Loker';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rak-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Tambah Loker', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'namarak',
            [
                'header' => 'Total',
                'value' => function ($model) {
                    return \app\models\RakStore::find()->where(['idrak' => $model->id])->count();
                }
            ],
            // 'kode',
            // 'nourut',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
