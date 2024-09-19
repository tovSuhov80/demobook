<?php

namespace app\controllers;

use app\models\forms\BookForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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

    public function actionAdd(): \yii\web\Response|string
    {
        $model = new BookForm();

        if ($model->load(Yii::$app->request->post())
            && $model->validate()
            && Yii::$app->bookService->saveBook($model, null, Yii::$app->user->id)
        ) {
            Yii::$app->session->setFlash('success', 'Книга успешно добавлена.');
            return $this->redirect(['my']);
        }

        return $this->render('save', [
            'model' => $model,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionEdit(int $id): \yii\web\Response|string
    {
        $book = Yii::$app->bookService->getBookOrFail($id);
        Yii::$app->bookService->assertBookAccess($book);

        $model = new BookForm();
        $model->id = $id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && Yii::$app->bookService->saveBook($model, $id, Yii::$app->user->id)) {
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена.');
                    return $this->redirect(['my']);
            }
        } else {
            $model->setAttributes($book->getAttributes());
            $model->author_names = $book->getAuthorsAsString();
        }

        return $this->render('save', [
            'model' => $model,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     */
    public function actionDelete($id): \yii\web\Response|string
    {
        $book = Yii::$app->bookService->getBookOrFail($id);
        Yii::$app->bookService->assertBookAccess($book);

        if (Yii::$app->bookService->deleteBook($book)) {
            Yii::$app->session->setFlash('success', "Книга \"".Html::encode($book->title)."\" была удалена.");
            return $this->redirect(['my']);
        } else {
            throw new ServerErrorHttpException("Ошибка удаления книги");
        }
    }

}
