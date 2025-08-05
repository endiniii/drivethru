<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = \app\models\Pemohon::find()->orderBy(['tanggalsimpan' => SORT_DESC])->limit(10);

        $dataProviderPemohon = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        /*
        $searchModel = new \app\models\PemohonSearch();
        $dataProviderPemohon = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderPemohon->query->orderBy(['tanggalsimpan' => SORT_DESC])->limit(1);
        $dataProviderPemohon->pagination->pageSize = 15;
        // $dataProviderPemohon->query->limit(10);*/

        $settings = \app\models\Settings::find()->where(['id' => 1])->one();
        $max_sekat = ($settings === null) ? 10 : $settings->max_sekat;
        $max_isi = ($settings === null) ? 10 : $settings->max_isi;

        $pemohonModel = \app\models\Pemohon::find();

        //$total_slot = \app\models\Rak::find()->count() * $max_sekat * $max_isi;
		$total_slot = \app\models\Rak::find()->count() * $max_isi;
        $total_pemohon = $pemohonModel->count();
        // $blm_diambil = $pemohonModel->where(['status' => 'tersimpan'])->count();

        $blm_diambil = \app\models\RakStore::find()->count();

        $persentase = ($blm_diambil > 0) ? (round($blm_diambil / $total_slot * 100, 0)) : 0;

        return $this->render('index',[
            'dataProviderPemohon' => $dataProviderPemohon,
            'total_slot' => $total_slot,
            'total_pemohon' => $total_pemohon,
            'blm_diambil' => $blm_diambil,
            'persentase' => $persentase,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
