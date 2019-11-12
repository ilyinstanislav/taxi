<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "passengers".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 *
 * @property Order[] $orders
 */
class Passenger extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'passengers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['passenger_id' => 'id']);
    }

    /**
     * Функция логики получения пассажиров
     * @param $phone
     * @param $name
     * @return array|bool|Passenger|ActiveRecord|null
     */
    public static function createOrFind($phone, $name)
    {
        /*
         * Непонятно какая логика должна быть в пассажирах, например если номер телефона один а имена разные,
         * то менять имя или создавать нового пользователя? Я предположил что имя не так важно как номер и
         * если пассажир заказывает со своего номера но с другим именем, то отдаем существующего пассажира
         * с этим номером ничего не меняя.
         */

        $passenger = self::find()
            ->where(compact('phone'))
            ->one();

        if (!$passenger) {
            $passenger = new Passenger(compact('phone', 'name'));
            return $passenger->save() ? $passenger : false;
        }
        return $passenger;
    }
}
