<?php

namespace common\models\query;

use common\models\Client;
use yii\db\ActiveQuery;

class ClientQuery extends ActiveQuery
{
    public function active(){
        return $this->andWhere(['status' => Client::STATUS_ACTIVE]);
    }

    /**
     * @param null $db
     * @return array|Client[]
     */
    public function all($db = null){
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return array|null|Client
     */
    public function one($db = null){
        return parent::one($db);
    }
}