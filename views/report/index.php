<?php
use app\models\Author;

/** @var yii\web\View $this */
/** @var Author[] $authors */
/** @var int $year */
/** @var int[] $releasedYears */

$this->title = "DemoBook: ТОП 10 авторов за {$year} год";
?>
<div class="site-index">
    <div class="body-content">
        <h1><?=$this->title?></h1>
        <div class="table-responsive" style="max-width: 600px;">
        <table class="table table-striped table-bordered " style="max-width: 600px;">
            <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Автор</th>
                <th scope="col" class="text-end text-nowrap" style="max-width: 6ch;">Кол-во книг</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($authors as $idx => $author)  { ?>
            <tr>
                <td><?=$idx+1?></td>
                <td><?=$author->getFullName()?></td>
                <td class="text-end" ><?=$author->booksCount?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
        <?php if (!empty($releasedYears)) {?>
            <div class="container mt-4">
                Смотрите также:
                <div class="d-flex flex-wrap">
                    <?php foreach($releasedYears as $releasedYear)  { ?>
                    <a href="/report/<?=$releasedYear?>" class="btn btn-outline-primary me-2 mb-2"><?=$releasedYear?> год</a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
