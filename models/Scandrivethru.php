<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "scandrivethru".
 *
 * @property int $id
 * @property int $idpemohon
 * @property string $tanggalscan
 * @property string $status
 */
class Scandrivethru extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scandrivethru';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpemohon', 'tanggalscan', 'status'], 'required'],
            [['idpemohon'], 'integer'],
            [['tanggalscan'], 'safe'],
            [['status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpemohon' => 'Id Pemohon',
            'tanggalscan' => 'Tanggal Scan',
            'status' => 'Status',
        ];
    }

    public function getPemohon()
    {
        return $this->hasOne(Pemohon::className(), ['id' => 'idpemohon']);
    }
}
