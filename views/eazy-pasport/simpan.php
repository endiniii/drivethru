<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Simpan Berkas - ' . Html::encode($tempat);
?>

<h2>Tempat: <?= Html::encode($tempat) ?></h2>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'nopermohonan')->textInput(['placeholder' => 'Scan atau masukkan No Permohonan']) ?>

<div class="form-group">
    <?= Html::submitButton('Cari Data', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php if ($dataPemohon): ?>
    <h3>Data Pemohon:</h3>
    <ul>
        <li>Nama: <?= Html::encode($dataPemohon->nama) ?></li>
        <li>Tempat Lahir: <?= Html::encode($dataPemohon->tempat_lahir) ?></li>
        <li>Tanggal Lahir: <?= Html::encode($dataPemohon->tanggal_lahir) ?></li>
        <li>Jenis Kelamin: <?= Html::encode($dataPemohon->jenis_kelamin) ?></li>
        <li>No Paspor: <?= Html::encode($dataPemohon->no_paspor) ?></li>
        <li>No Telepon: <?= Html::encode($dataPemohon->no_telepon) ?></li>
        <li>Kode Billing: <?= Html::encode($dataPemohon->kode_billing) ?></li>
    </ul>

    <form method="post">
        <?= Html::hiddenInput('SimpanForm[nopermohonan]', $model->nopermohonan) ?>
        <button type="submit" name="simpan" class="btn btn-success">Simpan ke Loker & Kirim WA</button>
    </form>
<?php endif; ?>

<hr>
<h3>Riwayat Data Tersimpan di Tempat: <?= Html::encode($tempat) ?></h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th><th>Nama</th><th>No Permohonan</th><th>No Telepon</th><th>Nama Loker</th><th>Status</th><th>Tanggal Simpan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($histori as $i => $item): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($item->nama) ?></td>
                <td><?= Html::encode($item->nopermohonan) ?></td>
                <td><?= Html::encode($item->no_telepon) ?></td>
                <td><?= Html::encode($item->nama_loker) ?></td>
                <td><?= Html::encode($item->status) ?></td>
                <td><?= Html::encode($item->tanggal_simpan) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
