<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class EazyPasport extends ActiveRecord
{
    public static function tableName()
    {
        return 'eazy_pasport'; // pastikan ini nama tabel yang kamu buat
    }

    public function rules()
    {
        return [
            [['nopermohonan', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'no_telepon', 'no_paspor', 'kode_billing', 'tanggal_simpan', 'nama_loker', 'status', 'tempat'], 'safe'],
        ];
    }
}
