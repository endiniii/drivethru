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
class Inbox extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        // use the "db2" application component
        return Yii::$app->dbsms;  
    }

    public static function tableName()
    {
        return 'inbox';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*[['namarak', 'kode', 'nourut'], 'required'],
            [['nourut'], 'integer'],
            [['namarak'], 'string', 'max' => 32],
            [['kode'], 'string', 'max' => 4],
            ['nourut', 'validateMaxSlot'],*/

            [['UpdatedInDB', 'ReceivingDateTime'], 'safe'],
            [['Text', 'SenderNumber', 'Coding', 'UDH', 'SMSCNumber', 'TextDecoded', 'RecipientID', 'Processed'], 'string'],
            [['Class', 'ID', 'Status'], 'integer']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UpdatedInDB' => 'Updated In DB',
            'ReceivingDateTime' => 'Receiving Date Time',
            'Text' => 'Text',
            'SenderNumber' => 'Sender Number',
            'Coding' => 'Coding',
            'UDH' => 'UDH',
            'SMSCNumber' => 'SMSC Number',
            'Class' => 'Class',
            'TextDecoded' => 'Message',
            'ID' => 'ID',
            'RecipientID' => 'Recipient ID',
            'Processed' => 'Processed',
            'Status' => 'Status',
        ];

        /*`UpdatedInDB` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `ReceivingDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `Text` text NOT NULL,
          `SenderNumber` varchar(20) NOT NULL DEFAULT '',
          `Coding` enum('Default_No_Compression','Unicode_No_Compression','8bit','Default_Compression','Unicode_Compression') NOT NULL DEFAULT 'Default_No_Compression',
          `UDH` text NOT NULL,
          `SMSCNumber` varchar(20) NOT NULL DEFAULT '',
          `Class` int(11) NOT NULL DEFAULT '-1',
          `TextDecoded` text NOT NULL,
          `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `RecipientID` text NOT NULL,
          `Processed` enum('false','true') NOT NULL DEFAULT 'false',
          `Status` int(11) NOT NULL DEFAULT '-1',*/

    }
}
