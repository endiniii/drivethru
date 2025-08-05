<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rak".
 *
 * @property int $id
 * @property string $namarak
 * @property string $kode
 * @property int $nourut
 */
class Rak extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rak';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['namarak', 'kode', 'nourut'], 'required'],
            [['nourut'], 'integer'],
            [['namarak'], 'string', 'max' => 32],
            [['kode'], 'string', 'max' => 4],
            ['nourut', 'validateMaxSlot'],
        ];
    }

    public function validateMaxSlot($attribute, $params, $validator)
    {
        $max_sekat = 10;    //default
        $model = \app\models\Settings::findOne(1);
        if($model !== null){
            $max_sekat = $model->max_sekat;
        }

        if ($this->$attribute > $max_sekat) {
            $this->addError($attribute, 'Sekat tidak boleh lebih dari '. $max_sekat .'.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'namarak' => 'Nama Loker',
            'kode' => 'Kode',
            'nourut' => 'No Urut',
        ];
    }
}
