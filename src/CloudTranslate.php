<?php

namespace ProcessDrive\LaravelCloudTranslation;

class CloudTranslate {
    
    /**
     * This function used to translation text google cloud
     * [param 1] Your text
     * [param 2] Your text language
     * [param 3] Your translate language
     */
    public function translate ($text, $translate_from, $translate_to){
        $result   = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=".$translate_from."&tl=".$translate_to."&hl=hl&q=".urlencode($text), $_SERVER['DOCUMENT_ROOT']."/transes.html");
        $result   = json_decode($result);
        return $result[0][0][0];
    }
}