<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TicketSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-search">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'post',
    ]); ?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Ticket Search</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-2">
                    <?= $form->field($model, 'id'/*, ['options' => ['class' => 'form-group pull-left', 'style' => 'max-width:50px; margin:0 15px']]*/) ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'column') ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'row') ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'amount') ?>
                </div>
                <div class="col-xs-4">
                    <?= $form->field($model, 'status') ?>
                </div>
<!--                <div class="col-xs-3">-->
<!--                    --><?php //echo $form->field($model, 'expired_at') ?>
<!--                </div>-->
<!--                <div class="col-xs-3">-->
<!--                    --><?php //echo $form->field($model, 'block_expired_at') ?>
<!--                </div>-->
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <div class="form-group pull-right">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
