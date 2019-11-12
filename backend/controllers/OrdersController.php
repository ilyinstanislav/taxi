<?php

namespace backend\controllers;

use backend\models\forms\OrderForm;
use backend\models\Order;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Orders controller
 * @property Order model
 */
class OrdersController extends BaseBackendController
{
    protected $_model;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Список заказов.
     * @return string
     */
    public function actionIndex()
    {
        $this->title = 'Заказы';

        $model = new Order();
        $model->load(Yii::$app->request->get());

        return $this->render('index', compact('model'));
    }

    /**
     * создание заказа
     * @return string
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new OrderForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Заказ успешно добавлен');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('warning', 'При сохранении возникла ошибка, попробуйте позже');
                return $this->redirect(['index']);
            }
        }

        $this->_breadcrumbs[] = [
            'label' => 'Заказы',
            'url' => ['index']
        ];

        $this->title = 'Добавление нового заказа';
        return $this->render('create', [
            'model' => $model
        ]);
    }
}
