<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RakStore */

$this->title = 'Create Rak Store';
$this->params['breadcrumbs'][] = ['label' => 'Rak Stores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rak-store-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
