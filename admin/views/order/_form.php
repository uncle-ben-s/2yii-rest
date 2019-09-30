<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <div class="col-lg-6">
        <?php $form = ActiveForm::begin([
            'layout'=>'horizontal',
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
        ]);
        ?>

        <div class="box">
            <div class="box-body">

<!--                --><?php //echo $form->field($model, 'client_id')->textInput() ?>

                <?php
                    $clients = \common\models\Client::find()->orderBy('username')->asArray()->all();

                    echo $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'username'))
                ?>

                <?= $form->field($model, 'status')->dropDownList([ 'open' => 'Open', 'close' => 'Close', ], ['prompt' => '']) ?>

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
