<?php
/**
 * Created by PhpStorm.
 * User: kai
 * Date: 02.03.2015
 * Time: 12:24
 */

class Suggest {



   // private static $google_endPoint = "http://google.com/complete/search?output=toolbar&q=";
    private static $google_endPoint = "http://suggestqueries.google.com/complete/search?client=firefox&q=";


    private static $fakedUserAgent = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5";

    private static $stopWordsFile = "stopwordsBase.txt";
    private $stopWords = null;


    private $phrase = null;

    private $range = [];

    private $ret = [];

    private $removeWord =false;

    public function __construct($phrase,$startLetter,$endLetter,$removeWord=false){

        $this->range = range(strtolower($startLetter),strtolower($endLetter));

        $this->removeWord = $removeWord;

        $this->stopWords = file(self::$stopWordsFile);

        $this->stopWords=array_map('trim',$this->stopWords);
        $this->stopWords=array_map('strtolower',$this->stopWords);
        $this->phrase = $phrase;

    }


    public function run(){


        if($this->phrase==null){
            return ["no keyword given"];
        }


        foreach($this->range as $letter) {
            $phrase = $this->phrase . " ".$letter;
            $this->ret = array_merge($this->filter($this->suggest($phrase)), $this->ret);
            $this->ret = array_unique($this->ret);
        }
        return true;
    }


    private function filter($suggests){

        $ret=[];

        foreach($suggests as $suggest){

            if($this->removeWord==true){
                $suggest = str_replace(trim($this->getPhrase()),"",$suggest);
            }

            $suggest=preg_split("/\s/",$suggest);

            $temp=[];
            foreach($suggest as $word){

                if(!in_array(strtolower(trim($word)),$this->stopWords)){
                    $temp[]=$word;
                }
            }
            if(count($temp)>0){
                $ret[]=implode(" ",$temp);
            }
        }
        return $ret;
    }


    private function suggest($phrase){

        $phrase = urlencode($phrase);

        $content = self::getWithCurl(self::$google_endPoint.$phrase);
        $content = json_decode($content,true);
       if(is_array($content) && isset($content[1]) && is_array($content[1]) && count($content[1])>=1){
           return $content[1];
       }
        return [];

    }


    public function output(){
        echo json_encode($this->ret);
    }




    /**
     * @return null
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @param null $phrase
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * @return array
     */
    public function getRange()
    {
        return $this->range;
    }

    /**
     * @param array $range
     */
    public function setRange($range)
    {
        $this->range = $range;
    }


    public static function getWithCurl($url,$useProxy=false,$proxyUrl=null){
        $curl = curl_init($url);
        if($useProxy==true) {
            curl_setopt($curl, CURLOPT_PROXY, $proxyUrl);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
        }
        curl_setopt($curl,CURLOPT_USERAGENT,self::$fakedUserAgent);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        usleep(500000);
        return curl_exec($curl);
    }

    /**
     * @return array
     */
    public function getRet()
    {
        return $this->ret;
    }








}