<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ticket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ticket-form row">

    <div class="col-lg-6">
        <?php $form = ActiveForm::begin([
            'layout'=>'horizontal',
//            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'offset' => 'col-sm-offset-2',
                    'wrapper' => 'col-sm-10',
                    'error' => 'help-block',
                    'hint' => '',
                ],
            ],
        ]); ?>
        <div class="box">
            <div class="box-body">

                <?= $form->field($model, 'column')->textInput() ?>

                <?= $form->field($model, 'row')->textInput() ?>

                <?= $form->field($model, 'amount')->textInput() ?>

                <?= $form->field($model, 'status')->dropDownList([ 'open' => 'Open', 'close' => 'Close', 'reserve' => 'Reserve', ], ['prompt' => '']) ?>

                <?= $form->field($model, 'expired_at_formatted', [
                    'inputOptions' => [
                        'autocomplete' => 'off',
                    ]
                ])->widget(DateTimePicker::class, [
                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                    'options' => [
                        'value' => $model->expired_at_formatted,
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'M dd, yyyy hh:ii:ss',
                        'startDate' => date('Y-m-d'),
                    ]
                ]) ?>

            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
