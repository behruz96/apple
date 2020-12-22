<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\WarehouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Apple');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?>
        <small class="pull-right">
            <?= Html::a(Yii::t('app', 'Create Apples'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
        </small>
    </h1>

    <div class="apple-grid">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php Pjax::begin(['id' => 'apple']) ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'idapple',
                'color',
                'date_appearance',
                'date_fall',
                [
                    'attribute' => 'status',
                    'value' => function ($data) {
                        return ($data->status == '0') ? "<span class='alert-secondary glyphicon glyphicon-tree-deciduous'></span>"
                            : (($data->status == '1') ? "<span class='alert-success glyphicon glyphicon-apple'></span>"
                                : "<span class='alert-danger'>rotten apple</span>");
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'size',
                    'value' => function ($data) {
                        return $data->size ? $data->size . " %" : "";
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{fall} {eat}{delete}',
                    'buttons' => [
                        'fall' => function ($url) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-download-alt"></span>',
                                $url,
                                [
                                    'title' => 'Fall',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                        'eat' => function ($url, $model) {
                            return
                                '<div class="btn-group btn-group-xs" role="group">
                                        <button title="Eat percent" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <span class="glyphicon glyphicon-cutlery"></span>
                                          <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                          <li>' . Html::a('25%', \yii\helpers\Url::to(['eat', 'id' => $model->idapple,
                                    'percent' => 25]), ['title' => 'Eat 25%']) . '</li>
                                          <li>' . Html::a('50%', \yii\helpers\Url::to(['eat', 'id' => $model->idapple,
                                    'percent' => 50]), ['title' => 'Eat 50%']) . '</li>
                                          <li>' . Html::a('75%', \yii\helpers\Url::to(['eat', 'id' => $model->idapple,
                                    'percent' => 75]), ['title' => 'Eat 75%']) . '</li>
                                          <li>' . Html::a('100%', \yii\helpers\Url::to(['eat', 'id' => $model->idapple,
                                    'percent' => 100]), ['title' => 'Eat 100%']) . '</li>
                                        </ul>
                                      </div>';
                        },
                        'delete' => function ($url) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                $url,
                                [
                                    'title' => 'Delete',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]); ?><?Pjax::end();?>

    </div>
</div>
