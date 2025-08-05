<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Outbox */

$this->title = 'Reply SMS';
$this->params['breadcrumbs'][] = ['label' => 'Inbox', 'url' => ['inbox']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outbox-create">

    <h1><?= Html::encode($this->title) ?> to <?=Yii::$app->request->get('SenderNumber')?></h1>

    <div class="row">
    	<div class="col-md-12" style="height: 400px; overflow-y: scroll; overflow-x: auto;" id="conversation">
    		
    		<?php
    		foreach($messageModel as $data)
    		{
    			//echo '<h4><span class="label label-default">'.$data->TextDecoded.'</span></h4>';
    			// echo '<br />';
    		}
    		?>

            <?php
            foreach($provider->getModels() as $data)
            {
                $class = ($data['status'] == 'in') ? 'warning' : 'primary';
                $status = '';
                switch ($data['status']) {
                    case 'in':
                        $status = '<span class="text-warning"><i class="glyphicon glyphicon-folder-open"></i> Inbox  </span>';
                        break;
                    case 'pending':
                        $status = '<span class="text-danger"><i class="glyphicon glyphicon-info-sign"></i> Pending  </span>';
                        break;
                    case 'out':
                        $status = '<span class="text-success"><i class="glyphicon glyphicon-ok"></i> Sent </span>';
                        break;
                    default:
                        # code...
                        break;
                }

                $text_align = ($data['status'] == 'in') ? 'left' : 'right';
                $alert = ($data['status'] == 'in') ? 'warning' : 'info';
                $reverse = ($data['status'] == 'in') ? '' : 'class="blockquote-reverse"';

                /*echo '<div class="alert-'.$alert.'">';
                // echo '<p class="text-'.$text_align.'">'.$data['TextDecode'].'</p>';
                // echo '<small>'.$data['texttime'].' <i class="glyphicon glyphicon-time"></i>'.$status.' </small>';
                echo '<blockquote '.$reverse.'><span style="padding: 3px;">'.$data['TextDecode'].'</span>';
                echo '<footer>'.$data['texttime'].' <i class="glyphicon glyphicon-time"></i>'.$status.' </footer></blockquote>';
                echo '</div>'; */

                // echo '<div class="text-'.$text_align.'"><small>'.$data['texttime'].' <i class="glyphicon glyphicon-time"></i>'.$status.' </small></div>';
                echo '<div class="alert alert-'.$alert.'" role="alert"><div class="text-'.$text_align.'"><h4>'.$data['TextDecode']. '</h4>';
                // echo '<p class="text-'.$text_align.'">'.$data['TextDecode'].'</p>';
                // echo '<small>'.$data['texttime'].' <i class="glyphicon glyphicon-time"></i>'.$status.' </small>';
                echo '<small>'.$status.' <i class="glyphicon glyphicon-time"></i> '.$data['texttime'].'</small></div>';
                echo '</div>';
            }
            ?>

    	</div>

    </div>

    

    <?= $this->render('_reply-form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$script = <<< JS
    var messageBody = document.querySelector('#conversation');
    messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
JS;
$this->registerJs($script);
?>
