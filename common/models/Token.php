<?php

namespace common\models;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property int $client_id
 * @property string $token
 * @property int $expired_at
 *
 * @property Client $client
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token';
    }

    public function generateToken($expire)
    {
        $this->expired_at = $expire;
        $this->token = \Yii::$app->security->generateRandomString();
    }
    public function fields()
    {
        return [
            'token' => 'token',
            'expired' => function () {
                return date(DATE_RFC3339, $this->expired_at);
            },
        ];
    }
}
