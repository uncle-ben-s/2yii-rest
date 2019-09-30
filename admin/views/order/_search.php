<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

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
                    <?= $form->field($model, 'id') ?>
                </div>
                <div class="col-xs-2">
                    <?= $form->field($model, 'client_id') ?>
                </div>
                <div class="col-xs-3">
                    <?= $form->field($model, 'status')->dropDownList([ 'open' => 'Open', 'close' => 'Close', ], ['prompt' => '']) ?>
                </div>
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
