<?php

use backend\widgets\Box;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->buttons = [
    Html::a('Добавить', ['/orders/create'], [
        'class' => 'btn btn-sm btn-primary',
    ])
];

Box::begin();
Pjax::begin();
echo GridView::widget([
    'id' => 'orders-grid',
    'dataProvider' => $model->search(),
    'filterModel' => $model,
    'columns' => [
        'createdFormat',
        [
            'attribute' => 'passenger_name',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->passengerName;
            }
        ],
        [
            'attribute' => 'passenger_phone',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->passengerPhone;
            }
        ],
        [
            'attribute' => 'address',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->addressName;
            }
        ],
        'dateFrom',
        'dateTo'
    ]
]);
Pjax::end();
Box::end();