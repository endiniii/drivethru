<?php

namespace app\controllers;

use Yii;
use app\models\Inbox;
use app\models\InboxSearch;
use app\models\Outbox;
use app\models\OutboxSearch;
use app\models\SentItems;
use app\models\SentItemsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

class SmsController extends \yii\web\Controller
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
                ],
            ],
        ];
    }


    public function actionInbox()
    {

    	$searchModel = new InboxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['ReceivingDateTime' => SORT_DESC]);
        $dataProvider->pagination->pageSize = 15;

        return $this->render('inbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOutbox()
    {
        $searchModel = new OutboxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('outbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendSms()
    {
        $model = new Outbox();
        $model->CreatorID = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/outbox/view', 'id' => $model->ID]);
        }

        return $this->render('send-sms', [
            'model' => $model,
        ]);
    }

    public function actionReply($SenderNumber, $InboxID)
    {
        $request = Yii::$app->request;
        $SenderNumber = $request->get('SenderNumber');
        $InboxID = intval($request->get('InboxID') );

        // echo $SenderNumber;
        $model = new Outbox();
        $model->CreatorID = $InboxID; //Change Creator ID
        $model->DestinationNumber = $SenderNumber;

        $messageModel = Inbox::find();

        $data = [];

        foreach (Inbox::find()->where(['SenderNumber' => $SenderNumber])->all() as $d) {
            $data[] = [
                'status' => 'in',
                'TextDecode' => $d->TextDecoded,
                'texttime' => $d->ReceivingDateTime 
            ];
        }

        foreach (SentItems::find()->where(['DestinationNumber' => $SenderNumber])->all() as $m) {
            $data[] = [
                'status' => 'out',
                'TextDecode' => $m->TextDecoded,
                'texttime' => $m->SendingDateTime 
            ];
        }

        foreach (Outbox::find()->where(['DestinationNumber' => $SenderNumber])->all() as $m) {
            $data[] = [
                'status' => 'pending',
                'TextDecode' => $m->TextDecoded,
                'texttime' => $m->SendingDateTime 
            ];
        }

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
            'sort' => [
                'attributes' => ['status', 'TextDecode', 'texttime'],
                'defaultOrder' => ['texttime' => SORT_ASC],
            ],
        ]);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //update status to Processed
            Yii::$app->dbsms->createCommand()->update('inbox', ['Processed' => 'true'], 'ID = "'.$model->CreatorID.'"')->execute();

            return $this->redirect(['/sms/reply', 'SenderNumber' => $model->DestinationNumber, 'InboxID' => $model->CreatorID]);
        }


        return $this->render('reply', [
            'model' => $model,
            'messageModel' => $messageModel,
            'provider' => $provider,
            'datas' => $data
        ]);
    }

    public function actionSentItems()
    {
        $searchModel = new SentItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['SendingDateTime' => SORT_DESC]);
        $dataProvider->pagination->pageSize = 15;

        return $this->render('sent-items', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewSentItem($ID, $SequencePosition)
    {
        return $this->render('view-sent-item', [
            'model' => $this->findModelSent($ID, $SequencePosition),
        ]);
    }

    protected function findModelSent($ID, $SequencePosition)
    {
        if (($model = SentItems::findOne(['ID' => $ID, 'SequencePosition' => $SequencePosition])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
