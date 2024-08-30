<?php

namespace app\controllers;

use app\models\forms\SubscribeForm;
use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SubscribeController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionAdd(int $id): \yii\web\Response|string
    {
        $book = Yii::$app->bookService->getBookOrFail($id);

        $authors = $book->authors; // Получаем авторов книги
        $authorNames = [];
        foreach ($authors as $author) {
            $authorNames[$author->id] = $author->getFullName();
        }

        $model = new SubscribeForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $successCount = 0;

            foreach ($model->authorNames as $authorId => $authorName) {
                if (empty($authorName)) continue;
                if (Yii::$app->subscribeService->subscribeToAuthor($authorId, $model->phone)) {
                    $successCount++;
                }
            }
            if ($successCount) {
                Yii::$app->session->setFlash('success', 'Вы успешно подписались.');
            } else {
                Yii::$app->session->setFlash('info', 'Вы уже подписаны на данного автора.');
            }
            return $this->redirect('/book');
        }

        $model->authorNames = $authorNames;
        $model->verifyCode = '';

        return $this->render('add', [
            'model' => $model,
            'book' => $book,
            'authors' => $authors,
        ]);
    }
}
