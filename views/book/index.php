<?php

use app\models\Author;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var int $year */
/** @var int[] $releasedYears */

$this->title = "DemoBook: книги за {$year} год";

$placeholderImage = Url::to('@web/images/placeholder.jpg');
?>
<div class="site-index">
    <div class="body-content">
        <div class="book-index">

            <div class="book-index">

                <h1><?= Html::encode($this->title) ?></h1>

                <?php if (!empty($releasedYears)) {?>
                    <div class="container mt-4">
                        Смотрите также:
                        <div class="d-flex flex-wrap">
                            <?php foreach($releasedYears as $releasedYear)  { ?>
                                <a href="/book/<?=$releasedYear?>" class="btn btn-outline-primary me-2 mb-2"><?=$releasedYear?> год</a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

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
