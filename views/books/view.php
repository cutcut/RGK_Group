<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Authors;
use app\components\CustomDateTimeWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$autor = Authors::findOne($model->author_id);
?>
<div class="books-view">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?php if (!isset($ajax)): ?>
	
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
	
	<?php endif; ?>
	
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
			[
				'attribute' => 'date_create',
				'format' => 'raw',
				'value' => CustomDateTimeWidget::widget(['datetime' => $model->date_create]),
			],
			[
				'attribute' => 'date_create',
				'format' => 'raw',
				'value' => CustomDateTimeWidget::widget(['datetime' => $model->date_create]),
			],
            [
				'attribute' => 'preview',
				'format' => 'raw',
				'value' => $model->preview ? Html::img('@web/upload/prev_'.$model->preview) : '',
			],
			[
				'attribute' => 'date',
				'format' => 'raw',
				'value' => CustomDateTimeWidget::widget(['datetime' => $model->date]),
			],
            [
				'attribute' => 'author_id',
				'format' => 'raw',
				'value' => Html::a($autor->firstname.' '.$autor->lastname, ['/authors/view', 'id' => $model->author_id]),
			],
        ],
    ]) ?>

</div>
