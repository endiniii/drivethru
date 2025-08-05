<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pemohon".
 *
 * @property int $id
 * @property string $nopermohonan
 * @property string $nama
 * @property string $tempatlahir
 * @property string $tanggallahir
 * @property string $jeniskelamin
 * @property string $notelepon
 * @property string $nopaspor
 * @property int $kodebilling
 * @property string|null $tanggalsimpan
 * @property int|null $tanggalambil
 * @property int|null $smsgateway
 * @property int $koderak
 * @property string|null $status
 */
class Pemohon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pemohon';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nopermohonan', 'nama', 'tempatlahir', 'tanggallahir', 'jeniskelamin', 'notelepon', 'nopaspor', 'kodebilling', 'koderak'], 'required'],
            [['tanggallahir', 'tanggalsimpan', 'tanggalambil'], 'safe'],
            [['jeniskelamin', 'status'], 'string'],
            [['smsgateway', 'koderak'], 'integer'],
            [['nopermohonan'], 'string', 'max' => 17],
            [['kodebilling'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 255],
            [['tempatlahir'], 'string', 'max' => 32],
            [['notelepon'], 'string', 'max' => 13],
            [['nopaspor'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nopermohonan' => 'No Permohonan',
            'nama' => 'Nama',
            'tempatlahir' => 'Tempat Lahir',
            'tanggallahir' => 'Tanggal Lahir',
            'jeniskelamin' => 'Jenis Kelamin',
            'notelepon' => 'No Telepon',
            'nopaspor' => 'No Paspor',
            'kodebilling' => 'Kode Billing',
            'tanggalsimpan' => 'Tanggal Simpan',
            'tanggalambil' => 'Tanggal Ambil',
            'smsgateway' => 'SMS Gateway',
            'koderak' => 'Kode Rak',
            'status' => 'Status',
        ];
    }

    public function getRak()
    {
        return $this->hasOne(Rak::className(), ['id' => 'koderak']);
    }
}
