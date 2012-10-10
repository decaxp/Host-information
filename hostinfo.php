<?php

class hostInfo{
	////////////default path`s to GOOGLE PR
	private $prstr="Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE. Yes, I'm talking to you, scammer.";
	private $prresult=0x01020345;
	private $prurl='http://%s/tbr?client=navclient-auto&ch=%s&features=Rank&q=info:%s';
	////////////default path`s to YANDEX TIC
	private $ticurl="http://bar-navig.yandex.ru/u?ver=2&show=32&url=http://";
	
	private $host ;

	function __construct($Host){$this->host=$Host;}
	function getHost(){return $this->host;}

	function getTIC($newsite=NULL){
		$url=(isset($newsite) and !empty($newsite))?$newsite:$this->host;    
		$xml = file_get_contents($this->ticurl.$url);
		$tic = substr(strstr($xml, 'value='), 6);
		return strtok($tic, '"');		
	}		 
	
	function getPageRank($newsite=NULL,$query_host='toolbarqueries.google.com',$context=NULL) {
		$site=(isset($newsite) and !empty($newsite))?$newsite:$this->host;        
        $result = $this->prresult;
        $len = strlen($site);
        for ($i=0; $i<$len; $i++) {
                $result ^= ord($this->prstr{$i%strlen($this->prstr)}) ^ ord($site{$i});
                $result = (($result >> 23) & 0x1ff) | $result << 9;
        }
		if (PHP_INT_MAX != 2147483647) { $result = -(~($result & 0xFFFFFFFF) + 1); }
        $ch=sprintf('8%x', $result);
        $url=sprintf($this->prurl,$query_host,$ch,$site);
        @$pr=file_get_contents($url,false,$context);
        return $pr?substr(strrchr($pr, ':'), 1):false;
	}
}

?>