<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=Yii::getAlias("@web/images/")?>logo-cilacap.png">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="'.Yii::getAlias("@web/images/").'logo-cilacap.png" width="32" height="32" style="float:left" />&nbsp; '. Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Simpan Berkas', 'url' => ['/pemohon/simpan']],
            ['label' => 'Ambil Berkas', 'url' => ['/pemohon/ambil']],
            //['label' => 'Slot Rak', 'url' => ['/rak/index']],
            ['label' => 'Pemohon', 'url' => ['/pemohon/index']],
            //['label' => 'List', 'url' => ['/scan-drive-thru/index']],
            //['label' => 'Settings', 'url' => ['/settings']],
            [
                'label' => 'Settings',
                'items' => [
                     ['label' => 'List Antrian', 'url' => ['/scan-drive-thru/index']],
                     '<li class="divider"></li>',
                     '<li class="dropdown-header">Loker</li>',
                     ['label' => 'Kapasitas', 'url' => ['/settings/index']],
                     ['label' => 'Slot', 'url' => ['/rak/index']],
                ],
            ],
            /*[
                'label' => 'SMS',
                'items' => [
                     ['label' => 'Inbox', 'url' => ['/sms/inbox']],
                     ['label' => 'Outbox', 'url' => ['/sms/outbox']],
                     ['label' => 'Sent Items', 'url' => ['/sms/sent-items']],
                     ['label' => 'Send SMS', 'url' => ['/sms/send-sms']],
                ],
            ],*/
            //['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Kantor Imigrasi Kelas II TPI Cilacap <?= date('Y') ?></p>

        <p class="pull-right"><?= 'v1.0' ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
