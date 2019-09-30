<?php

use fedemotta\datatables\DataTables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">
            <?= Html::a('<i class="fa fa-plus"></i> Create Order', ['create'], ['class' => 'btn btn-primary margin']) ?>
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <?= DataTables::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'client_id',
                        'client.username',
                        'status',
                        [
                            'attribute' => 'amount',
                            'format'    => 'raw',
                            'value'     => function (\common\models\Order $model) {
                                return ($model->amount != null)? $model->amount : 0;
                            },
                        ],

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]);?>
            </div>
        </div>
    </div>


</div>
