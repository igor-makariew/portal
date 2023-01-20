<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\modules\models\postauto\PostAuto;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\models\postauto\PostAutoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Autos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-auto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post Auto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                // скрытие счетчика
                'visible' => false

            ],
//            'id',
            'title',
            'post:ntext',
            'author',
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
                                    <h5 class="modal-title" id="exampleModalLongTitle">'.$value['title'].'</h5>
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
            [
                'class' => ActionColumn::class,
                'header' => 'Действие',
                'urlCreator' => function ($action, PostAuto $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
