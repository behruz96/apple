<?php
/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 22.12.2020
 * Time: 15:40
 */

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
class AppleSearch extends Apple
{

    public function rules()
    {
        return [
            [['idapple','quantity'], 'integer'],
            [['date_appearance', 'date_fall', 'status'], 'safe'],
            [['color'], 'string', 'max' => 10],
            [['size',], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Apple::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => '10',
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_appearance' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idapple' => $this->idapple,
            'quantity' => $this->quantity,
            'size' => $this->size,
        ]);

        $query->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'date_appearance', $this->date_appearance])
            ->andFilterWhere(['like', 'date_fall', $this->date_fall])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}

