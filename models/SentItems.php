<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sentitems".
 *
 * @property string $UpdatedInDB
 * @property string $InsertIntoDB
 * @property string $SendingDateTime
 * @property string|null $DeliveryDateTime
 * @property string $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string $UDH
 * @property string $SMSCNumber
 * @property int $Class
 * @property string $TextDecoded
 * @property int $ID
 * @property string $SenderID
 * @property int $SequencePosition
 * @property string $Status
 * @property int $StatusError
 * @property int $TPMR
 * @property int $RelativeValidity
 * @property string $CreatorID
 * @property int $StatusCode
 */
class SentItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sentitems';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbsms');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UpdatedInDB', 'InsertIntoDB', 'SendingDateTime', 'DeliveryDateTime'], 'safe'],
            [['Text', 'UDH', 'TextDecoded', 'ID', 'SenderID', 'SequencePosition', 'CreatorID'], 'required'],
            [['Text', 'Coding', 'UDH', 'TextDecoded', 'Status', 'CreatorID'], 'string'],
            [['Class', 'ID', 'SequencePosition', 'StatusError', 'TPMR', 'RelativeValidity', 'StatusCode'], 'integer'],
            [['DestinationNumber', 'SMSCNumber'], 'string', 'max' => 20],
            [['SenderID'], 'string', 'max' => 255],
            [['ID', 'SequencePosition'], 'unique', 'targetAttribute' => ['ID', 'SequencePosition']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UpdatedInDB' => 'Updated In Db',
            'InsertIntoDB' => 'Insert Into Db',
            'SendingDateTime' => 'Sending Date Time',
            'DeliveryDateTime' => 'Delivery Date Time',
            'Text' => 'Text',
            'DestinationNumber' => 'Destination Number',
            'Coding' => 'Coding',
            'UDH' => 'Udh',
            'SMSCNumber' => 'Smsc Number',
            'Class' => 'Class',
            'TextDecoded' => 'Text Decoded',
            'ID' => 'ID',
            'SenderID' => 'Sender ID',
            'SequencePosition' => 'Sequence Position',
            'Status' => 'Status',
            'StatusError' => 'Status Error',
            'TPMR' => 'Tpmr',
            'RelativeValidity' => 'Relative Validity',
            'CreatorID' => 'Creator ID',
            'StatusCode' => 'Status Code',
        ];
    }
}
