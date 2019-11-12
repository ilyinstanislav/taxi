<?php

namespace backend\models\forms;

use backend\models\Order;
use common\models\Passenger;
use common\models\Trip;
use common\validators\XssFilter;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * Class OrderForm
 * @package backend\models\forms
 *
 * @property-read string $passenger_name
 * @property-read string $passenger_phone
 * @property-read string $address
 * @property-read string $datetime_from
 * @property-read string $datetime_to
 */
class OrderForm extends Model
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
     * @var string
     */
    public $datetime_from;

    /**
     * @var string
     */
    public $datetime_to;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['passenger_name', 'passenger_phone', 'address', 'datetime_from', 'datetime_to'], 'required'],
            [['passenger_name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
            [
                'passenger_name',
                'match',
                'pattern' => '#^[аА-яЯaA-zZ\s\-]+$#u',
                'message' => '«{attribute}» может содержать только латинские и кириллические символы'
            ],
            [
                'passenger_phone',
                'match',
                'pattern' => '#^([0-9]{3})\-([0-9]{3})\-([0-9]{4})$#',
                'message' => 'Телефон должен быть в формате 999-999-9999'
            ],
            [
                ['datetime_from', 'datetime_to'],
                'match',
                'pattern' => '#^([0-9]{2})\.([0-9]{2})\.([0-9]{4}) ([0-9]{2}):([0-9]{2})$#',
                'message' => 'Телефон должен быть в формате dd.mm.YYYY HH:ii'
            ],
            [['datetime_from', 'datetime_to'], 'checkDate'],
            [['address'], XssFilter::class],
        ];
    }

    /**
     * Проверка даты на правильность
     * @param $attribute
     */
    public function checkDate($attribute)
    {
        $date = strtotime($this->{$attribute});
        if ($date < time()) {
            $this->addError($attribute, 'Дата должна быть больше текущей');
        }

        if ($this->datetime_from && $this->datetime_to && (strtotime($this->datetime_from) >= strtotime($this->datetime_to))) {
            $this->addError('datetime_to', 'Дата конца должна быть больше даты начала');
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'passenger_name' => 'Имя клиента',
            'passenger_phone' => 'Телефон клиента',
            'address' => 'Адрес',
            'datetime_from' => 'Дата и время с',
            'datetime_to' => 'Дата и время по'
        ];
    }

    /**
     * Сохранение заказа
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $passenger_id = $this->getPassenger();
        $trip_id = $this->getTrip();

        $order = new Order([
            'passenger_id' => $passenger_id,
            'trip_id' => $trip_id
        ]);

        if (!$passenger_id || !$trip_id || !$order->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * Получение id пассажира для заказа
     * @return bool|int
     */
    protected function getPassenger()
    {
        $passenger = Passenger::createOrFind($this->passenger_phone, $this->passenger_name);

        if ($passenger) {
            return $passenger->id;
        }

        return false;
    }

    /**
     * Получение id поездки для заказа
     * @return bool|int
     */
    protected function getTrip()
    {
        $trip = new Trip([
            'address' => $this->address,
            'date_start' => date('Y-m-d H:i:s', strtotime($this->datetime_from)),
            'date_end' => date('Y-m-d H:i:s', strtotime($this->datetime_to))
        ]);

        if ($trip->save()) {
            return $trip->id;
        }

        return false;
    }
}
