<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rak_store".
 *
 * @property int $id
 * @property int $idpemohon
 * @property int $idrak
 */
class RakStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rak_store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpemohon', 'idrak'], 'required'],
            [['idpemohon', 'idrak'], 'integer'],
            [['idpemohon'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpemohon' => 'ID Pemohon',
            'idrak' => 'ID Rak',
        ];
    }
}
