<?php

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
    <link rel="shortcut icon" href="<?= Yii::getAlias("@web/images/") ?>logo-cilacap.png">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        /* Tambahan untuk dropdown submenu */
        .dropdown-submenu {
            position: relative;
        }
        .dropdown-submenu > .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }
    </style>
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

    // Menu bawaan Yii (kecuali Kirim Berkas)
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Simpan Berkas', 'url' => ['/pemohon/simpan']],
            ['label' => 'Ambil Berkas', 'url' => ['/pemohon/ambil']],
            ['label' => 'Pemohon', 'url' => ['/pemohon/index']],
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

    // Kirim Berkas: manual HTML
    ?>
   <!-- Kirim Berkas: manual HTML -->
<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kirim Berkas <b class="caret"></b></a>
        <ul class="dropdown-menu">

            <!-- Banyumas -->
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Banyumas</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/banyumas-mpp']) ?>">MPP</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/banyumas-ngapak']) ?>">Ngapak</a></li>
                </ul>
            </li>

            <!-- Purbalingga -->
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Purbalingga</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/purbalingga-mpp']) ?>">MPP</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/purbalingga-ngapak']) ?>">Ngapak</a></li>
                </ul>
            </li>

            <!-- Kebumen -->
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#">Kebumen</a>
                <ul class="dropdown-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/kebumen-mpp']) ?>">MPP</a></li>
                    <li><a href="<?= \yii\helpers\Url::to(['/pemohon/kebumen-ngapak']) ?>">Ngapak</a></li>
                </ul>
            </li>

             <!-- Eazy Passport -->
            <li class="dropdown-submenu">
                <a tabindex="-1" href="<?= \yii\helpers\Url::to(['/pemohon/eazy-passport']) ?>">EAZY PASSPORT</a>
            </li>

        </ul>
    </li>
</ul>


    <?php
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
        <p class="pull-right">v1.0</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
