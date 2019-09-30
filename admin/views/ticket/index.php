<?php

use fedemotta\datatables\DataTables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TicketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">
            <?= Html::a('<i class="fa fa-plus"></i> Create Ticket', ['create'], ['class' => 'btn btn-primary margin']) ?>
            <div class="dataTables_wrapper form-inline dt-bootstrap">
                <?= DataTables::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'column',
                        'row',
                        'amount',
                        'status',
                        [
                            'attribute' => 'block_expired_at',
                            'format'    => 'raw',
                            'value'     => function (\common\models\Ticket $model) {
                                return ($model->block_expired_at != null)? Yii::$app->formatter->asDatetime($model->block_expired_at) :'no';
                            },
                        ],
                        'expired_at:datetime',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]);?>
            </div>
        </div>
    </div>


</div>
