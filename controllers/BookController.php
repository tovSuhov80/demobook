<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class BookController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['my', 'add','edit', 'delete'],
                'rules' => [
                    [
                        'actions' => ['my', 'add','edit', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex(int $year = 2024): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->bookService->getBooksByYear($year),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'year' => $year,
            'releasedYears' => Yii::$app->bookService->getReleasedYears($year),
        ]);
    }

    public function actionMy(): string
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->bookService->getBooksByUserId(Yii::$app->user->id),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('mybooks', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
