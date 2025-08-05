<?php

namespace app\controllers;

use Yii;
use app\models\Pemohon;
use app\models\PemohonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                        $smsModel->TextDecoded = 'Paspor a.n ' . $model->nama . ' telah selesai. Silahkan melakukan pengambilan melalui DRIVE THRU / walk-in. -Imigrasi Madiun-';

                        $smsModel->save();
                    }
                    /***** /END SENDING SMS ******/


                    return $this->redirect(['simpan']);
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

        throw new NotFoundHttpException('The requested page does not exist.');
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
}
