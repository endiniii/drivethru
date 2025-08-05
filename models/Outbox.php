<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "outbox".
 *
 * @property string $UpdatedInDB
 * @property string $InsertIntoDB
 * @property string $SendingDateTime
 * @property string $SendBefore
 * @property string $SendAfter
 * @property string|null $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string|null $UDH
 * @property int|null $Class
 * @property string $TextDecoded
 * @property int $ID
 * @property string|null $MultiPart
 * @property int|null $RelativeValidity
 * @property string|null $SenderID
 * @property string|null $SendingTimeOut
 * @property string|null $DeliveryReport
 * @property string $CreatorID
 * @property int|null $Retries
 * @property int|null $Priority
 * @property string $Status
 * @property int $StatusCode
 */
class Outbox extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'outbox';
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
            [['UpdatedInDB', 'InsertIntoDB', 'SendingDateTime', 'SendBefore', 'SendAfter', 'SendingTimeOut'], 'safe'],
            [['Text', 'Coding', 'UDH', 'TextDecoded', 'MultiPart', 'DeliveryReport', 'CreatorID', 'Status'], 'string'],
            [['Class', 'RelativeValidity', 'Retries', 'Priority', 'StatusCode'], 'integer'],
            [['TextDecoded', 'CreatorID'], 'required'],
            [['DestinationNumber'], 'string', 'max' => 20],
            [['SenderID'], 'string', 'max' => 255],
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
            'SendBefore' => 'Send Before',
            'SendAfter' => 'Send After',
            'Text' => 'Text',
            'DestinationNumber' => 'Destination Number',
            'Coding' => 'Coding',
            'UDH' => 'Udh',
            'Class' => 'Class',
            'TextDecoded' => 'Text Decoded',
            'ID' => 'ID',
            'MultiPart' => 'Multi Part',
            'RelativeValidity' => 'Relative Validity',
            'SenderID' => 'Sender ID',
            'SendingTimeOut' => 'Sending Time Out',
            'DeliveryReport' => 'Delivery Report',
            'CreatorID' => 'Creator ID',
            'Retries' => 'Retries',
            'Priority' => 'Priority',
            'Status' => 'Status',
            'StatusCode' => 'Status Code',
        ];
    }
}
