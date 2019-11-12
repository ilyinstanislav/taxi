<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $passenger_id
 * @property int $trip_id
 * @property string $created_at
 *
 * @property-read string $createdFormat
 * @property-read string $passengerName
 * @property-read string $passengerPhone
 * @property-read string $addressName
 * @property-read string $dateFrom
 * @property-read string $dateTo
 *
 * @property Passenger $passenger
 * @property Trip $trip
 */
class Order extends ActiveRecord
{
    /**
     * @var string
     */
    public $passenger_name;

    /**
     * @var string
     */
    public $passenger_phone;

    /**
     * @var string
     */
    public $address;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['passenger_id', 'trip_id'], 'integer'],
            [['created_at'], 'safe'],
            [['trip_id'], 'unique'],
            [['passenger_id'], 'exist', 'skipOnError' => true, 'targetClass' => Passenger::className(), 'targetAttribute' => ['passenger_id' => 'id']],
            [['trip_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trip::className(), 'targetAttribute' => ['trip_id' => 'id']],
            [['passenger_name', 'passenger_phone', 'address'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'passenger_id' => 'Passenger ID',
            'trip_id' => 'Trip ID',
            'created_at' => 'Дата создания',
            'createdFormat' => 'Дата создания',
            'passenger_name' => 'Имя пассажира',
            'passenger_phone' => 'Телефон пассажира',
            'address' => 'Адрес',
            'dateFrom' => 'Дата с',
            'dateTo' => 'Дата по',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPassenger()
    {
        return $this->hasOne(Passenger::className(), ['id' => 'passenger_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTrip()
    {
        return $this->hasOne(Trip::className(), ['id' => 'trip_id']);
    }

    /**
     * дата создания заказа в нужном формате
     * @param string $format
     * @return false|string
     */
    public function getCreatedFormat($format = 'd.m.Y H:i:s')
    {
        return date($format, strtotime($this->created_at));
    }

    /**
     * Имя пассажира
     * @return mixed
     */
    public function getPassengerName()
    {
        return ArrayHelper::getValue($this->passenger, 'name', null);
    }

    /**
     * Телефон пассажира
     * @return mixed
     */
    public function getPassengerPhone()
    {
        return ArrayHelper::getValue($this->passenger, 'phone', null);
    }

    /**
     * адрес заказа
     * @return mixed
     */
    public function getAddressName()
    {
        return ArrayHelper::getValue($this->trip, 'address', null);
    }

    /**
     * Дата заказа с
     * @return mixed
     */
    public function getDateFrom()
    {
        return ArrayHelper::getValue($this->trip, 'date_start', null);
    }

    /**
     * Дата заказа по
     * @return mixed
     */
    public function getDateTo()
    {
        return ArrayHelper::getValue($this->trip, 'date_end', null);
    }
}
