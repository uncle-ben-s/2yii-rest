<?php

namespace api\base;


class RestClientExtendedException extends RestClientException
{
    private $messages = [];
    private $description;

    /**
     * Constructor.
     * @param string $message error info message
     * @param array $messages error messages
     * @throws RestClientException
     */
    public function __construct($message = null, $messages = [])
    {
        $this->messages = $messages;
        $this->description = $this->arrayToString($messages);
        parent::__construct($message);
    }

    public function getDescription(): string {
        return $this->description;
    }

    private function arrayToString(array $messages): string {
        $strArray = [];
        foreach($messages as $itemKey => $item){
            if(is_array($item)){
                foreach($item as $message){
                    $strArray[] = /*'[' . $itemKey . '] ' .*/ $message;
                }
            }elseif(is_string($item)){
                $strArray[] = /*'[' . $itemKey . '] ' .*/ $item;
            }else{
                throw new RestClientException('Invalid message array structure.');
            }
        }

        return implode(' ', $strArray);
    }

}