<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SentItems;

/**
 * SentItemsSearch represents the model behind the search form of `app\models\SentItems`.
 */
class SentItemsSearch extends SentItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UpdatedInDB', 'InsertIntoDB', 'SendingDateTime', 'DeliveryDateTime', 'Text', 'DestinationNumber', 'Coding', 'UDH', 'SMSCNumber', 'TextDecoded', 'SenderID', 'Status', 'CreatorID'], 'safe'],
            [['Class', 'ID', 'SequencePosition', 'StatusError', 'TPMR', 'RelativeValidity', 'StatusCode'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SentItems::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'UpdatedInDB' => $this->UpdatedInDB,
            'InsertIntoDB' => $this->InsertIntoDB,
            'SendingDateTime' => $this->SendingDateTime,
            'DeliveryDateTime' => $this->DeliveryDateTime,
            'Class' => $this->Class,
            'ID' => $this->ID,
            'SequencePosition' => $this->SequencePosition,
            'StatusError' => $this->StatusError,
            'TPMR' => $this->TPMR,
            'RelativeValidity' => $this->RelativeValidity,
            'StatusCode' => $this->StatusCode,
        ]);

        $query->andFilterWhere(['like', 'Text', $this->Text])
            ->andFilterWhere(['like', 'DestinationNumber', $this->DestinationNumber])
            ->andFilterWhere(['like', 'Coding', $this->Coding])
            ->andFilterWhere(['like', 'UDH', $this->UDH])
            ->andFilterWhere(['like', 'SMSCNumber', $this->SMSCNumber])
            ->andFilterWhere(['like', 'TextDecoded', $this->TextDecoded])
            ->andFilterWhere(['like', 'SenderID', $this->SenderID])
            ->andFilterWhere(['like', 'Status', $this->Status])
            ->andFilterWhere(['like', 'CreatorID', $this->CreatorID]);

        return $dataProvider;
    }
}
