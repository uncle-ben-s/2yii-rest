<?php

namespace console\controllers;

use common\models\Signup;
use yii\console\Controller;

class TrewController extends Controller
{
    public $user;
    public $email;
    public $pass;

    public function options($actionID){
        return [
            'user',
            'pass',
            'email'
        ];
    }

    public function actionQw(){

        if(!$this->user)
            die(PHP_EOL . "Required param [user] is empty" . PHP_EOL . PHP_EOL);
        if(!$this->pass)
            die(PHP_EOL . "Required param [pass] is empty" . PHP_EOL . PHP_EOL);
        if(!$this->email)
            die(PHP_EOL . "Required param [email] is empty" . PHP_EOL . PHP_EOL);

        $model = new Signup();
        $model->load([
            'username' => $this->user,
            'password' => $this->pass,
            'email'    => $this->email
        ], '');

        $user = $model->signup();


        echo (!is_null($user))? print_r($user->toArray()) : print_r($model->errors);
        die;
    }

    public function actionQa(){
        echo PHP_EOL . PHP_EOL . /*Yii::$app->formatter->asDatetime(*/date('M d, Y H:i:s',time())/*)*/ . PHP_EOL . PHP_EOL;
    }

}
