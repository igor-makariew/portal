<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\models\postauto\PostAuto */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Autos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-auto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'post:ntext',
            'author',
//            'image',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function($value) {
                    $image = Yii::getAlias('/admin/images/uploadImages/') . $value['image'];
                    return '<a type="button" class="" data-toggle="modal" data-target="#exampleModalLong'.$value['id'].'">
                          <img src="'.$image.'" style="width: 40px" alt="Кнопка «button»">
                        </a>
                        <div class="modal fade" id="exampleModalLong'.$value['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">My_girl'.$value['id'].'</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="'.$image.'" style="width: 500px; " alt="'.$value['title'].'"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            ],
            'date',
        ],
    ]) ?>

</div>
