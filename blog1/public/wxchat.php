<?php
    define('TOKEN','wxjinhui');
    $obj = new wxjh();
    $obj->valid();
    class wxjh{
        public function valid()
        {
            $echostr = $_GET["echostr"];
            if($this->checkSignature()){
                echo  $echostr;
            }
        }
        private function checkSignature()
        {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $token = TOKEN;
            $tmpArr = array($token,$timestamp, $nonce);
            $tmp = sort($tmpArr);
            $str = implode( $tmp );
            $tmpStr = sha1( $str );

            if( $signature ){
                return true;
            }else{
                return false;
            }
        }
    }
?>