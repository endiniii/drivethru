<?php

namespace app\controllers;

use Yii;
use app\models\Inbox;

class DaemonController extends \yii\web\Controller
{
    public function actionMessageRoutine()
    {
    	$model = Inbox::find()->where(['Processed' => 'false'])->all();

    	foreach ($model as $m) {
    		//echo $m->SenderNumber . '<br />';

    		$explode = explode(" ", strtoupper($m->TextDecoded));

    		if($explode[0] == "PASPOR"){
    			if($explode[1] == 'SYARAT'){
    				//syarat paspor
					$sql = "1.e-KTP;2.KK;3.Akte Lahir/Ijasah/Buku Nikah/Surat Baptis;4.Paspor Lama bagi yang telah memiliki Paspor.Fotocopy kertas A4 & dokumen asli dibawa pd waktu proses.";
					//insert into outbox
		                Yii::$app->dbsms->createCommand()->insert('outbox', [
						    'DestinationNumber' => $m->SenderNumber,
						    'TextDecoded' => $sql,
						    'CreatorID' => $m->ID,
						])->execute();

						//Update status
						Yii::$app->dbsms->createCommand()->update('inbox', ['Processed' => 'true'], 'ID = '.intval($m->ID).'')->execute();
    			}else{
    				$no_permohonan = $explode[1];

    				$query = Yii::$app->dbspri->createCommand('SELECT * FROM paspor WHERE nopermohonan=:id')
                				->bindValue(':id', $no_permohonan)->queryOne();

                	if($query){
		                //query
		                $sql = "Permohonan a.n " . $query['nama'] ." status saat ini adalah " . $query['alurterakhir'];
		                echo  $sql .".<br />";

		                //insert into outbox
		                Yii::$app->dbsms->createCommand()->insert('outbox', [
						    'DestinationNumber' => $m->SenderNumber,
						    'TextDecoded' => $sql,
						    'CreatorID' => $m->ID,
						])->execute();

						//Update status
						Yii::$app->dbsms->createCommand()->update('inbox', ['Processed' => 'true'], 'ID = '.intval($m->ID).'')->execute();

		            }else{
		            	$sql = "No Permohonan ".$no_permohonan." tidak ditemukan.";
		            	echo $sql . " <br />";

		            	//insert into outbox
		            	Yii::$app->dbsms->createCommand()->insert('outbox', [
						    'DestinationNumber' => $m->SenderNumber,
						    'TextDecoded' => $sql,
						    'CreatorID' => $m->ID,
						])->execute();

						//Update status
						Yii::$app->dbsms->createCommand()->update('inbox', ['Processed' => 'true'], 'ID = '.intval($m->ID).' ')->execute();
		            }
    			}
    		}else{
    			echo $m->SenderNumber . '<br />';
    		}
    	}

    	echo "Haiiii";

        // return $this->render('message-routine');
    }

}
