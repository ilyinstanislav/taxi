<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "trips".
 *
 * @property int $id
 * @property string $address
 * @property string $date_start
 * @property string $date_end
 *
 * @property Order $orders
 */
class Trip extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'date_start', 'date_end'], 'required'],
            [['date_start', 'date_end'], 'safe'],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Адрес',
            'date_start' => 'Дата с',
            'date_end' => 'Дата по',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Order::className(), ['trip_id' => 'id']);
    }
}
