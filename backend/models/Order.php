<?php

namespace backend\models;

use common\models\Order as BaseClass;
use yii\data\ActiveDataProvider;

class Order extends BaseClass
{
    protected $pageSize = 20;

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Order::find()
            ->alias('order')
            ->joinWith('passenger passenger', false)
            ->joinWith('trip trip', false)
            ->andFilterWhere(['like', 'passenger.name', $this->passenger_name])
            ->andFilterWhere(['like', 'passenger.phone', $this->passenger_phone])
            ->andFilterWhere(['like', 'trip.address', $this->address]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
    }
}
