<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\widgets\Box;

Box::begin();

$form = ActiveForm::begin([
    'options' => ['class' => 'order_form'],
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnSubmit' => true,
]);

print $form->field($model, 'passenger_name')->textInput();
print $form->field($model, 'passenger_phone')->textInput();
print $form->field($model, 'address')->textInput();
print $form->field($model, 'datetime_from')->textInput();
print $form->field($model, 'datetime_to')->textInput();

print Html::submitButton('Укусить', ['class' => 'btn btn-sm btn-primary']);

$form->end();
Box::end();