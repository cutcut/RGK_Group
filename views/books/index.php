<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\ArrayHelper;
use app\models\Authors;
use app\components\CustomDateTimeWidget;
use yii\helpers\Url;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;

$Authors = ArrayHelper::map(Authors::find()->all(), 'id', 'fullname');

?>

<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'id',
				'filter' => false,
			],
            'name',
            [ 
				'attribute' => 'preview',
				'content' => function ($model, $key, $index, $column) { return $model->preview ? Html::a(Html::img('@web/upload/prev_'.$model->preview), '@web/upload/'.$model->preview, ['target'=>'_blank']) : ''; },
				'filter' => false,
			],
            [
				'attribute' => 'author_id',
				'content' => function ($model, $key, $index, $column) use ($Authors) {return isset($Authors[$model['author_id']]) ? $Authors[$model['author_id']] : $model['author_id'];},
				'filter' => Html::activeDropDownList($searchModel, 'author_id', $Authors, ['class' => 'form-control', 'prompt' => '--Выберте значение--']),
			],
			[
				'attribute' => 'date',
				//'format' => ['datetime', 'php:d.m.Y H:i:s'],
				//'filter' => false,
				'content' => function ($model, $key, $index, $column) { return CustomDateTimeWidget::widget(['datetime' => $model->date]); },
				'filter' => DatePicker::widget(['model' => $searchModel, 'attribute' => 'date', 'language' => 'ru', 'dateFormat' => 'dd/MM/yyyy',]),
				'filter' => 'c '.DatePicker::widget([
					'language' => 'ru',
					'dateFormat' => 'dd/MM/yyyy',
					'attribute' => 'date_from',
					'model' => $searchModel,
				]).' по '.DatePicker::widget([
					'language' => 'ru',
					'dateFormat' => 'dd/MM/yyyy',
					'attribute' => 'date_to',
					'model' => $searchModel,
				]),

            ],
			[
				'attribute' => 'date_create',
				//'format' => ['datetime', 'php:d.m.Y H:i:s'],
				//'filter' => false,
				'content' => function ($model, $key, $index, $column) { return CustomDateTimeWidget::widget(['datetime' => $model->date_create]); },
				'filter' => false,
            ],

			[
				'class' => 'yii\grid\ActionColumn',
				'buttons' => 
				[
					'view' => function ($url, $model) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                            'title' => Yii::t('yii', 'View'),
                            'onclick' => 'getAjaxView('.$model->id.');',
						]);
					},
					
					'update' => function ($url, $model) {
						Url::remember();
						return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['books/update', 'id' => $model->id, 'previous_url' => true ], [
                            'title' => Yii::t('yii', 'Update'),
						]);
					},
				]
			],
			
        ],
    ]); ?>

</div>
<div id="book-ajaxview" title="Книга"></div>

<script type="text/javascript">

	function getAjaxView(id) {
		$.ajax({ url: 'index.php?r=books/ajaxview&id=' + id, }).done(function(data) {
			$('#book-ajaxview').html(data);
			$("#book-ajaxview").dialog ("open");
		})
	}

	$(document).ready(function() {
		$("#book-ajaxview").dialog({
			resizable: false,
			modal: true,
			width: 800,
			autoOpen: false,
			open: function() {
				$(this).dialog("option", "title", $("#book-ajaxview").find('h1:first').html());
			},
			buttons: {
				"Закрыть": function() { $(this).dialog("close"); }
			}
		});
	});
</script>

