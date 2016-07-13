<?php

class Functions{

	//无限极分类组合一维数组
	static function unlimited($cate,$pid=0,$level=0,$html="-"){
	$str=array();
	foreach ($cate as $key => $value) {
		if($value['attr_pid']==$pid){
			$value['level']=$level+1;
			$value['html']=str_repeat($html,$level);
			$str[]=$value;
			$str=array_merge($str,self::unlimited($cate,$value['attr_id'],$value['level'],$html));
		}
	}
	return $str;

}

// 无限极分类组合多维数组
	static function cate($cate,$pid=0){
	$str=array();
	foreach ($cate as $key => $value) {
		if($value['attr_pid']==$pid){
			$str[$key]=$value;
			if($value['attr_pid']!==0){
				$str[$key]['cate']=self::cate($cate,$value['attr_id']);
			}
			
		}
	}
	return $str;

}

static function curl($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//不自动输出内容
		curl_setopt($curl, CURLOPT_HEADER, 0);//不返回头部信息
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 0); 
		$data = curl_exec($curl);
		//echo $data; 
		//var_dump($data);
		$content="";$title="";
		header('Content-type: text/html;charset=utf-8');
		$html = new simple_html_dom();
		$html->load($data);
		foreach($html->find('h1[id=main_title]') as $r){
			$title= $r->innertext;
		}
		foreach($html->find('div[id=artibody]') as $r){
			$content= $r->innertext;
		}
		//echo $content;
		//$title=mb_convert_encoding($title,"utf-8", "GBK");
		//$content=mb_convert_encoding($content,"utf-8", "GBK");
		unset($data);
		unset($html);
		curl_close($curl);
		return array($title,$content);
	}
}