<?php
class acm{
	//OJ信息
	public $oj = array(
		'name'=>'OJ_name',
		'pre_single'=>'',
		'startpage'=>0,
		'endpage'=>0,
		'charset'=>'utf-8'//默认编码
	);
	//单个问题正文的正则信息
	public $preg_problem = '';
	
	//是否继续
	private function can_go_on(){
		return file_exists($this->dir.'lock');
	}
	//加上css和js
	private function wrapper($content,$id){
		return '<!DOCTYPE html><html>
				<head><meta charset='.$this->oj['charset'].' />
				<link rel="stylesheet" type=text/css href="../style.css" />
				<script type="text/javascript" src="../js.js"></script>
				<title>Problem '.$id.'</title></head>
				<body><div id="content">'.$content.'</div></body></html>';
	}
	private $time;//用来统计用时
	public $dir;//工作目录
	public function __construct($s){
		
		set_time_limit(0);
		ignore_user_abort();
		
		$info = explode('|', $s);
		$this->oj['name'] = $info[0];
		$this->oj['pre_single'] = $info[1];
		$this->oj['startpage'] = intval($info[2]);
		$this->oj['endpage'] = intval($info[3]);
		
		$this->dir = dirname(__FILE__).'/'.$this->oj['name'].'/';
		
		$this->time = time();
		file_put_contents($this->dir.'lock.txt', '1');
		if(!file_exists($this->dir))mkdir($this->dir);//生成目录
		
		//生成css和js文件
		if(!file_exists($this->dir.'style.css'))file_put_contents($this->dir.'style.css', '/*style sheet*/');
		if(!file_exists($this->dir.'js.js'))file_put_contents($this->dir.'js.js', '/*Javascript code*/');
	}
	
	public function setPreg($preg){
		$this->preg_problem = $preg;
	}
	public function setCharset($charset){
		$this->oj['charset'] = $charset;
	}
	public function __destruct(){
		$this->time = time() - $this->time;
		echo "Completed,{$this->time}s used";
	}
	
	
	//把所有题目文件都下载下来
	public function local(){
		$files_dir = $this->dir.'files/';
		if(!file_exists($files_dir))mkdir($files_dir);
		for($i = $this->oj['startpage'];$i <= $this->oj['endpage'] && $this->can_go_on();$i++){
			$content = file_get_contents($this->oj['pre_single'].$i);
			if(empty($this->preg_problem))file_put_contents($files_dir.$i.'.html', $content);
			else{
				$match = array();
				$succeed = preg_match($this->preg_problem, $content,$match);
				
				if($succeed && isset($match[0]))file_put_contents($files_dir.$i.'.html', $this->wrapper($match[0],$i));
			}
			
		}
		
	}
	
	public function mkindex(){
		$content = '<div id="index_link">';
		for($i = $this->oj['startpage'];$i <= $this->oj['endpage'];$i++){
			$content.="<a href=\"files/{$i}.html\">{$i}</a>\n";
		}
		$content.='</div>';
		file_put_contents($this->dir.'index.html', $content);
	}
	
}