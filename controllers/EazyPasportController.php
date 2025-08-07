<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SimpanForm;
use app\models\Pemohon;
use app\models\EazyPasport;
use yii\helpers\Url;

class EazyPasportController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSimpan($tempat = null)
    {
        $model = new SimpanForm();
        $dataPemohon = null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dataPemohon = Pemohon::findOne(['nopermohonan' => $model->nopermohonan]);
            if ($dataPemohon === null) {
                Yii::$app->session->setFlash('error', 'Permohonan tidak ditemukan.');
            } elseif (Yii::$app->request->post('simpan')) {
                // Simpan ke tabel eazypasport
                $new = new EazyPasport();
                $new->attributes = $dataPemohon->attributes;
                $new->tempat = $tempat;
                $new->tanggal_simpan = date('Y-m-d H:i:s');
                $new->status = 'tersimpan';
                $new->nama_loker = 'A.09'; // bisa disesuaikan
                $new->save();

                // Redirect ke WhatsApp
                $wa = preg_replace('/^0/', '62', $dataPemohon->no_telepon);
                $message = urlencode("Halo {$dataPemohon->nama}, berkas Anda sudah tersimpan.");
                return $this->redirect("https://wa.me/{$wa}?text={$message}");
            }
        }

        // Ambil histori berdasarkan tempat
        $histori = EazyPasport::find()
            ->where(['tempat' => $tempat])
            ->orderBy(['tanggal_simpan' => SORT_DESC])
            ->all();

        return $this->render('simpan', [
            'model' => $model,
            'dataPemohon' => $dataPemohon,
            'tempat' => $tempat,
            'histori' => $histori,
        ]);
    }
}
