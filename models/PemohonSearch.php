<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pemohon;

/**
 * PemohonSearch represents the model behind the search form of `app\models\Pemohon`.
 */
class PemohonSearch extends Pemohon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kodebilling', 'tanggalambil', 'smsgateway', 'koderak'], 'integer'],
            [['nopermohonan', 'nama', 'tempatlahir', 'tanggallahir', 'jeniskelamin', 'notelepon', 'nopaspor', 'tanggalsimpan', 'status'], 'safe'],
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
        $query = Pemohon::find();

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
            'tanggallahir' => $this->tanggallahir,
            'kodebilling' => $this->kodebilling,
            'tanggalsimpan' => $this->tanggalsimpan,
            'tanggalambil' => $this->tanggalambil,
            'smsgateway' => $this->smsgateway,
            'koderak' => $this->koderak,
        ]);

        $query->andFilterWhere(['like', 'nopermohonan', $this->nopermohonan])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'tempatlahir', $this->tempatlahir])
            ->andFilterWhere(['like', 'jeniskelamin', $this->jeniskelamin])
            ->andFilterWhere(['like', 'notelepon', $this->notelepon])
            ->andFilterWhere(['like', 'nopaspor', $this->nopaspor])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
