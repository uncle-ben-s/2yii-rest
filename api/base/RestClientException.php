<?php

namespace api\base;


use yii\web\HttpException;

class RestClientException extends HttpException
{
    public $statusCode = 400;

    /**
     * Constructor.
     * @param string $message error info message
     */
    public function __construct($message = null)
    {
        parent::__construct($this->statusCode, $message);
    }

}