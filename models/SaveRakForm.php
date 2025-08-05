<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class SaveRakForm extends Model
{
    public $nopermohonan;
    public $sendsms;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['nopermohonan', 'sendsms'], 'required'],
            [['nopermohonan', 'sendsms'], 'string'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nopermohonan' => 'No Permohonan',
            'sendsms' => 'Kirim Pesan Otomatis ?',
        ];
    }

}
