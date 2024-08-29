<?php

use app\models\Author;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "DemoBook: мои книги";

$placeholderImage = Url::to('@web/images/placeholder.jpg');
?>
<div class="site-index">
    <div class="body-content">
        <div class="book-index">

            <div class="book-index">

                <h1><?= Html::encode($this->title) ?></h1>

                <div class="form-group">
                    <?= Html::a(Yii::t('app', 'Добавить книгу'), '/book/add',['class' => 'btn btn-success']) ?>
                </div>

                <?php Pjax::begin(); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ['class' => 'table-responsive'],
                    'tableOptions' => ['class' => 'table table-bordered table-striped table-hover'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'format' => 'html',
                            'value' => function ($model) use ($placeholderImage) {
                                // Если photo_url не задан, используем заглушку
                                $photoUrl = $model->photo_url ?: $placeholderImage;
                                return Html::img($photoUrl, ['style' => 'height: 150px; width: auto;']);
                            },
                        ],
                        'title',
                        'description:ntext',
                        'release_year',

                        [
                            'label' => 'Authors',
                            'value' => function ($model) {
                                $authors = array_map(static function($author) {
                                    /** @var Author $author  */
                                    return $author->getFullName();
                                }, $model->authors);
                                return implode(', ', $authors);
                            },
                        ],
                        'isbn',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    return Url::to("/book/edit/{$model->id}");
                                }
                                if ($action === 'delete') {
                                    return Url::to("/book/delete/{$model->id}");
                                }
                                return '#';
                            },
                            'template' => '{update} {delete}'
                        ],
                    ],
                    'pager' => [
                        'class' => 'yii\bootstrap5\LinkPager',
                    ],
                ]); ?>

                <?php Pjax::end(); ?>

            </div>
        </div>
    </div>
</div>
