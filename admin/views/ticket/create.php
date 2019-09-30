<?php

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */

$this->title = 'Create Ticket';
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
