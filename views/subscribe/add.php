<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\forms\SubscribeForm */
/* @var $book app\models\Book */
/* @var $authors app\models\Author[] */

$this->title = 'Подписка на авторов';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => '/book'];
$this->params['breadcrumbs'][] = $this->title;

$placeholderImage = Url::to('@web/images/placeholder.jpg');
?>

<div class="book-subscribe">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Детальная информация о книге -->
    <?= DetailView::widget([
        'model' => $book,
        'attributes' => [
            'title',
            'photo_url:image',
            'isbn',
            'release_year',
            'description:ntext',
        ],
    ]) ?>

    <h3>Для подписки отметьте автора и укажите свой номер телефона</h3>
    <p>В случае появления в каталоге новой книги этого автора Вам придет смс-уведомление.</p>
    <div class="row">
        <div class="col-lg-5">
    <?php $form = ActiveForm::begin(); ?>

    <!-- Чекбоксы для авторов -->
    <?php foreach ($model->authorNames as $index => $authorName): ?>
        <?= $form->field($model, "authorNames[$index]")->checkbox(['label' => $authorName, 'checked' => true]) ?>
    <?php endforeach; ?>

    <!-- Поле для ввода номера телефона -->
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 15, 'style' => 'width: 200px;']) ?>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>