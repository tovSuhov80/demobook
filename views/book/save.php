<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\BookForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = empty($model->id) ? Yii::t('app','Добавление книги') : Yii::t('app','Редактирование книги');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Мои книги'), 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'release_year')->textInput() ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author_names')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Укажите полные имена авторов через запятую, например: "Стивен Кинг, Джоан Роулинг"')]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', '/book/my',['class' => 'btn btn-second']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>