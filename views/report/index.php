<?php
use app\models\Author;

/** @var yii\web\View $this */
/** @var Author[] $authors */
/** @var int $year */

$this->title = "DemoBook: ТОП 10 авторов за {$year} год";
?>
<div class="site-index">
    <div class="body-content">
        <h1><?=$this->title?></h1>
        <table>
<?php foreach($authors as $idx => $author)  { ?>
            <tr><td><?=$idx+1?></td><td><?= $author->getFullName()?></td></tr>

<?php } ?>
        </table>
    </div>
</div>
