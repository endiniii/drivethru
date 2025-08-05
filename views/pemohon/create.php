<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pemohon */

$this->title = 'Create Pemohon';
$this->params['breadcrumbs'][] = ['label' => 'Pemohons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pemohon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
