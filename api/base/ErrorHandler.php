<?php

namespace api\base;


class ErrorHandler extends \yii\web\ErrorHandler
{
    protected function convertExceptionToArray($exception)
    {
        $array = parent::convertExceptionToArray($exception);

        if($exception instanceof RestClientExtendedException){
            $array['description'] = $exception->getDescription();
        }

        return $array;
    }
}