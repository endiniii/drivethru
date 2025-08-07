<?php
namespace app\models;

use yii\base\Model;

class SimpanForm extends Model
{
    public $nopermohonan;

    public function rules()
    {
        return [
            [['nopermohonan'], 'required'],
            [['nopermohonan'], 'string'],
        ];
    }
}
