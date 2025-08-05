<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rak;

/**
 * RakSearch represents the model behind the search form of `app\models\Rak`.
 */
class RakSearch extends Rak
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nourut'], 'integer'],
            [['namarak', 'kode'], 'safe'],
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
        $query = Rak::find();

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
            'id' => $this->id,
            'nourut' => $this->nourut,
        ]);

        $query->andFilterWhere(['like', 'namarak', $this->namarak])
            ->andFilterWhere(['like', 'kode', $this->kode]);

        return $dataProvider;
    }
}
