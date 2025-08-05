<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content: 
        <?=date('Y-m-d H:i:s')?>



    </p>

    <code><?= __FILE__ ?></code>

    <p>
		<?php 
        // $string = '1609000000000006';
        $string = '6';

        $length = strlen($string);
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

        	$final_string = '1609' . $string_ad . $string;

        	echo strlen($final_string) . ' > ' . $final_string;
        }else
        {
        	echo $length . ' > ' .$string;
        }

        // echo strlen($string);


        ?>
    </p>
</div>
