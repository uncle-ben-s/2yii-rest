<?php

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = 'Update Ticket: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ticket-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
