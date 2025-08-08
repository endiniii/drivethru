<?php

namespace app\controllers;

use Yii;
use app\models\Pemohon;
use app\models\PemohonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * PemohonController implements the CRUD actions for Pemohon model.
 */
class PemohonController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'save-to-rak' => ['POST'],
                    'get-from-rak' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Pemohon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PemohonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['tanggalsimpan' => SORT_DESC]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSimpan()
    {
        $searchModel = new \app\models\SearchSpriForm();
        $model = null;
        $query = null;
        $rak_id = $this->cekrak();
        $tersimpan = false;
        $final_string = '';

        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->request->get())
        {
            $getsearch = Yii::$app->request->get('SearchSpriForm');
            $searchModel->nopermohonan = $getsearch['nopermohonan'];

            $length = strlen($searchModel->nopermohonan);
            $total_length = 16;
            $min_length = 12;

            if($length <= $min_length)
            {

                // $string2 = '1609' . $string;
                $string_ad = '';

                for($i = $length; $i < $min_length; $i++)
                {
                    $string_ad .= '0';
                }

                $final_string = '1609' . $string_ad . $searchModel->nopermohonan;

                // echo strlen($final_string) . ' > ' . $final_string;
            }else{
                $final_string = $searchModel->nopermohonan;
            }

            $pemohonModel = Pemohon::find()->where(['nopermohonan' => $final_string, 'status' => 'tersimpan'])->one();
            

            if($pemohonModel !== null){
                // return 'Pemohon sudah tersimpan';
                $query = Yii::$app->db->createCommand('SELECT * FROM pemohon WHERE nopermohonan=:id')
                ->bindValue(':id', $final_string)->queryOne();

                $tersimpan = true;
                Yii::$app->session->setFlash('warning', "Permohonan a.n <b>" .$query['nama']. "</b> sudah tersimpan.");

            }else{
                $query = Yii::$app->dbspri->createCommand('SELECT * FROM paspor WHERE nopermohonan=:id')
                ->bindValue(':id', $final_string)->queryOne();
            }

            //Search on Data
            /*$query = Yii::$app->dbspri->createCommand('SELECT * FROM paspor WHERE nopermohonan=:id')
                ->bindValue(':id', $searchModel->nopermohonan)->queryOne();*/

            if($query){
                $model = $query;
            }
        }

        $searchModel->nopermohonan = $final_string;

        return $this->render('simpan', [
            'searchModel' => $searchModel,
            'model' => $model,
            'query' => $query,
            'rak_id' => $rak_id,
            'tersimpan' =>$tersimpan
            // 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaveToRak()
    {
        $model2 = new \app\models\SaveRakForm();

        // populate model attributes with user inputs
        $model2->load(\Yii::$app->request->post());

        if ($model2->validate()) {
            // all inputs are valid
            // echo $model2->nopermohonan; echo "<br />";
            // echo ($model2->sendsms == true) ? "-sendsms-" : "-nosms-";

            $query = Yii::$app->dbspri->createCommand('SELECT * FROM paspor WHERE nopermohonan=:id')
                ->bindValue(':id', $model2->nopermohonan)->queryOne();

            if($query){
                $orgDate = $query['tanggallahir'];  
                $date = \DateTime::createFromFormat('d-m-Y', $orgDate);
                $new_date = $date->format('Y-m-d');

                $model = Pemohon::find()->where(['nopermohonan' => $model2->nopermohonan ])->one();
                if($model !== null){
                    $model->tanggalsimpan = date('Y-m-d H:i:s');
                    // 'tanggalambil' => 'Tanggal Ambil',
                    $model->smsgateway = $model2->sendsms;
                    $model->koderak = $this->cekrak();
                    $model->status = 'tersimpan';
                }else{
                    $model = new Pemohon();
                    $model->nopermohonan = $query['nopermohonan'];
                    $model->nama = $query['nama'];
                    $model->tempatlahir = $query['tempatlahir'];
                    $model->tanggallahir = $new_date;
                    $model->jeniskelamin = $query['jeniskelamin'];
                    $model->notelepon = $query['notelepon'];
                    $model->nopaspor = $query['nopaspor'];
                    $model->kodebilling = $query['kodebilling'];
                    $model->tanggalsimpan = date('Y-m-d H:i:s');
                    // 'tanggalambil' => 'Tanggal Ambil',
                    $model->smsgateway = $model2->sendsms;
                    $model->koderak = $this->cekrak();
                    $model->status = 'tersimpan';
                }

                if($model->save())
                {
                    $rakStore = new \app\models\RakStore();
                    $rakStore->idpemohon = $model->id;
                    $rakStore->idrak = $model->koderak;
                    $rakStore->save();

                    Yii::$app->session->setFlash('success', "Silahkan simpan berkas ke Loker <b>" .$model->rak->namarak. "</b>.");
                    // return $this->redirect(['view', 'id' => $model->id]);

                    /***** SENDING SMS ******/
                    if($model->smsgateway == '1'){
                        $smsModel = new \app\models\Outbox();
                        $smsModel->CreatorID = 'smsnotif-' . $model->id;
                        $smsModel->DestinationNumber = $model->notelepon;
                        $smsModel->MultiPart = 'true';
                        $smsModel->TextDecoded = 'Yth. Bapak/Ibu, Paspor an. ' . $model->nama . ' Telah Selesai & Dapat Diambil di Imigrasi Cilacap Senin-Jumat 08.00-16.00 WIB & Sabtu/Minggu 08.00-12.00 WIB.';

                        $smsModel->save();
                    }
					if($model->smsgateway == '1'){
                        $smsModel = new \app\models\Outbox();
                        $smsModel->CreatorID = 'smsnotif-' . $model->id;
                        $smsModel->DestinationNumber = $model->notelepon;
                        $smsModel->MultiPart = 'true';
                        $smsModel->TextDecoded = 'Demi Peningkatan Kinerja, Mohon Isi Survey Melalui link: https://survei.balitbangham.go.id/ly/dcn6yJSM Call Center 081217000900';

                        $smsModel->save();
                    }
                    /***** /END SENDING SMS ******/


                    if ($model->save()) {
                        $rakStore = new \app\models\RakStore();
                        $rakStore->idpemohon = $model->id;
                        $rakStore->idrak = $model->koderak;
                        $rakStore->save();

                        // Buat URL WhatsApp Web
                       $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $model->notelepon));
                        $waMessage = urlencode(
                            "Hallo *{$model->nama}* dengan No Permohonan *{$model->nopermohonan}*\n\n" .
                            "Saat ini paspor anda sudah *SELESAI*.\n" .
                            "Silahkan datang ke Kantor Imigrasi Kelas I TPI Cilacap untuk pengambilan Paspor.\n\n" .
                            "Jam Operasional:\n" .
                            "- Senin - Kamis: 08.00 s/d 15.00 WIB\n" .
                            "- Jumat        : 08.00 s/d 15.30 WIB\n\n" .
                            "Persyaratan:\n" .
                            "1. Lampirkan Pengantar Pengambilan paspor, bukti pendaftaran M-Paspor, dan kartu identitas (KTP) \n" .
                            "2. Pengambilan dapat diwakilkan oleh orang lain dalam satu kartu keluarga yang sama dengan membawa KTP pemohon dan pengambil\n" .
                            "3. Surat Kuasa bermaterai apabila dikuasakan\n\n" .
                            "Untuk informasi lebih lanjut silahkan hubungi Call Center: 081217000900"
                        );
                        $waUrl = "https://wa.me/$waNumber?text=$waMessage";

                        return Yii::$app->response->redirect($waUrl);
                    }

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Tidak dapat menyimpan data.");
                    // return $this->redirect(['view', 'id' => $model->id]);
                    return $this->redirect(['simpan']);
                }
            }
        }
        /*
        $query = Yii::$app->dbspri->createCommand('SELECT * FROM paspor WHERE nopermohonan=:id')
                ->bindValue(':id', $nopermohonan)->queryOne();

        if($query){
            $orgDate = $query['tanggallahir'];  
            $date = \DateTime::createFromFormat('d-m-Y', $orgDate);
            $new_date = $date->format('Y-m-d');

            $model = Pemohon::find()->where(['nopermohonan' => $query['nopermohonan'] ])->one();
            if($model !== null){
                $model->tanggalsimpan = date('Y-m-d H:i:s');
                // 'tanggalambil' => 'Tanggal Ambil',
                $model->smsgateway = null;
                $model->koderak = $this->cekrak();
                $model->status = 'tersimpan';
            }else{
                $model = new Pemohon();
                $model->nopermohonan = $query['nopermohonan'];
                $model->nama = $query['nama'];
                $model->tempatlahir = $query['tempatlahir'];
                $model->tanggallahir = $new_date;
                $model->jeniskelamin = $query['jeniskelamin'];
                $model->notelepon = $query['notelepon'];
                $model->nopaspor = $query['nopaspor'];
                $model->kodebilling = $query['kodebilling'];
                $model->tanggalsimpan = date('Y-m-d H:i:s');
                // 'tanggalambil' => 'Tanggal Ambil',
                $model->smsgateway = null;
                $model->koderak = $this->cekrak();
                $model->status = 'tersimpan';
            }

            if($model->save())
            {
                $rakStore = new \app\models\RakStore();
                $rakStore->idpemohon = $model->id;
                $rakStore->idrak = $model->koderak;
                $rakStore->save();

                Yii::$app->session->setFlash('success', "Silahkan simpan berkas ke Rak <b>" .$model->rak->namarak. "</b>.");
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['simpan']);
            }
            else
            {
                Yii::$app->session->setFlash('error', "Tidak dapat menyimpan data.");
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['simpan']);
            }
        }*/

        return $this->redirect(['simpan']);
    }

    public function actionAmbil()
    {
        $searchModel = new \app\models\SearchSpriForm();
        $model = null;
        $query = null;
        $rak_id = $this->cekrak();
        $final_string = '';
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->request->get())
        {
            $getsearch = Yii::$app->request->get('SearchSpriForm');
            $searchModel->nopermohonan = $getsearch['nopermohonan'];

            $length = strlen($searchModel->nopermohonan);
            $total_length = 16;
            $min_length = 12;

            if($length <= $min_length)
            {

                // $string2 = '1609' . $string;
                $string_ad = '';

                for($i = $length; $i < $min_length; $i++)
                {
                    $string_ad .= '0';
                }

                $final_string = '1609' . $string_ad . $searchModel->nopermohonan;

                // echo strlen($final_string) . ' > ' . $final_string;
            }else{
                $final_string = $searchModel->nopermohonan;
            }

            //Search on Data
            $query = Yii::$app->db->createCommand('SELECT * FROM pemohon WHERE nopermohonan=:id')
                ->bindValue(':id', $final_string)->queryOne();

            if($query){
                $model = $query;
            }
        }

        $searchModel->nopermohonan = $final_string;
        
        return $this->render('ambil', [
            'searchModel' => $searchModel,
            'model' => $model,
            'query' => $query,
            // 'rak_id' => $rak_id
            // 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGetFromRak($nopermohonan)
    {
        $model = Pemohon::find()->where(['nopermohonan' => $nopermohonan])->one();

        if($model !== null){
            
            $model->tanggalambil = date('Y-m-d H:i:s');
            // 'tanggalambil' => 'Tanggal Ambil',
            $model->status = 'diambil';
            

            if($model->save(false))
            {
                $rakStore = \app\models\RakStore::find()->where(['idpemohon' => $model->id])->one();
                if($rakStore !== null) 
                {
                    //set to queue
                    $queueModel = \app\models\Scandrivethru::updateAll(['status' => 'diambil'],['idpemohon' => $rakStore->id]);
                    
                    $rakStore->delete();    //Ambil dari slot

                }

                Yii::$app->session->setFlash('success', "Berkas a.n <b>" .$model->nama. "</b> berhasil diambil");
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['ambil']);
            }
            else
            {
                Yii::$app->session->setFlash('error', "Tidak dapat mengambil berkas.");
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['ambil']);
            }
        }

        return $this->redirect(['ambil']);
    }

    /**
     * Displays a single Pemohon model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pemohon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pemohon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pemohon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pemohon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pemohon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pemohon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   protected function findModel($id)
{
    if (($model = Pemohon::findOne($id)) !== null) {
        return $model;
    }

    throw new NotFoundHttpException('Data tidak ditemukan.');
}

public function actionUpdatePesanWa($id)
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $model = $this->findModel($id);
    $model->smsgateway = true; // ubah status jadi terkirim
    if ($model->save(false)) {
        return ['success' => true];
    }
    return ['success' => false];
}


    protected function cekrak()
    {
        $rak_id = null;
        $max_isi = 10; //Default

        $setting = \app\models\Settings::findOne(1);
        $max_isi = ($setting !== null) ? $setting->max_isi : $max_isi;

        $models = \app\models\Rak::find()->all();
        foreach ($models as $model) {
            $total = \app\models\RakStore::find()->where(['idrak' => $model->id ])->count();

            if($total < $max_isi)
            {
                $rak_id = $model->id;
                break;
            }
        }


        return $rak_id;
    }

    public function actionPesanWaTerkirim($id)
{
    $model = $this->findModel($id); // Ambil data pemohon berdasarkan ID
    $model->smsgateway = true; // Ubah status jadi terkirim
    $model->save(false); // Simpan tanpa validasi

    Yii::$app->session->setFlash('success', 'Status Pesan WA berhasil diperbarui.');
    return $this->redirect(['index']); // Kembali ke daftar pemohon
}

public function actionCreateCilacap()
{
    $model = new Pemohon();

    if ($model->load(Yii::$app->request->post())) {
        // Atur default lokasi dan sub_lokasi khusus untuk menu Cilacap
        $model->lokasi = 'Cilacap';
        $model->sub_lokasi = 'MPP'; // atau nilai default lainnya

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    return $this->render('create_cilacap', [
        'model' => $model,
    ]);
}

public function actionCilacapMpp()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionCilacapNgapak()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionBanyumasMpp()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('PemohonSearch')['nopermohonan'] ?? null;

    $model = null;
    if ($nopermohonan !== null) {
        $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);
    }

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}


public function actionBanyumasNgapak()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionPurbalinggaMpp()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionPurbalinggaNgapak()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionKebumenMpp()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionKebumenNgapak()
{
    $searchModel = new \app\models\PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $nopermohonan = Yii::$app->request->get('nopermohonan');
    $model = \app\models\Pemohon::findOne(['nopermohonan' => $nopermohonan]);

    $tersimpan = false;

    return $this->render('simpan', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'tersimpan' => $tersimpan,
    ]);
}

public function actionExportCilacapMpp()
{
    $searchModel = new PemohonSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andWhere([
        'tempatlahir' => 'cilacap',
    ]);

    $models = $dataProvider->getModels();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(['No', 'No Permohonan', 'Nama', 'Tempat Lahir', 'Sub Lokasi', 'Tanggal'], null, 'A1');

    $row = 2;
    foreach ($models as $index => $model) {
        $sheet->fromArray([
            $index + 1,
            $model->no_permohonan,
            $model->nama_lengkap,
            $model->lokasi,
            $model->sub_lokasi,
            $model->created_at,
        ], null, 'A' . $row++);
    }

    $filename = 'cilacap-mpp.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
   
}
