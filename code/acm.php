<?php

/**
 * ACM achieves collection
 * @author Slacken(xinkiang@gmail.com)
 * @link http://huntist.cn/acm
 * */

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
	//不存在该页面
	public $preg_error = '';
	//是否继续
	private function can_go_on(){
		return file_exists($this->dir.'lock.txt');
	}
	//加上css和js
	private function wrapper($content,$id){
		return '<!DOCTYPE html><html>
				<head><meta charset='.$this->oj['charset'].' />
				<link rel="stylesheet" type=text/css href="../style.css" />
				<script type="text/javascript" src="../js.js"></script>
				<title>Problem '.$id.'</title></head>
				<body><div id="'.$this->oj['name'].'_c">'.$content.'</div></body></html>';
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
		//文件目录
		$this->dir = dirname(__FILE__).'/';
		
		$this->time = time();
		file_put_contents($this->dir.'lock.txt', '1');
		if(!file_exists($this->dir.'files/'))mkdir($this->dir.'files/');//生成目录
		
		//生成css和js文件
		if(!file_exists($this->dir.'style.css'))file_put_contents($this->dir.'style.css', "/*style sheet*/\n");
		if(!file_exists($this->dir.'js.js'))file_put_contents($this->dir.'js.js', "/*Javascript code*/\n");
	}
	
	public function setPreg($preg){
		$this->preg_problem = $preg;
	}
	public function setCharset($charset){
		$this->oj['charset'] = $charset;
	}
	public function setError($preg){
		$this->preg_error = $preg;
	}
	public function __destruct(){
		$this->time = time() - $this->time;
		if(file_exists($this->dir.'lock.txt'))unlink($this->dir.'lock.txt');
		echo "Completed, {$this->time} seconds used";
	}
	//替换图片
	public function replace_image($url,$current=''){
		$dir = $this->dir.'images/';
		if(!file_exists($dir))mkdir($dir);
		$match = array();
		if(preg_match('#[\S]*?\.(jpg|gif|png|jpeg)$#i', $url,$match)){
			$filetype = $match[1];//图片类型
			$filename = md5($url).'.'.$filetype;
			if(!preg_match('|^http://|i',$url)){
				if($url[0]=='/'){
					//找出根网址
					preg_match('|^(http[s]?://[\S]*?/)|i', $current,$match);
					$url = $match[1].ltrim($url,'/');
				}
				elseif(preg_match('#([\S]*?/)[^/]*$#i', $current,$match)){
					$url = $match[1].$url;
				}
				else return false;//错误的网址
			}
			if(!file_exists($dir.$filename))file_put_contents($dir.$filename, file_get_contents($url));
			return $filename;
		}
		return false;
	}
	
	//把所有题目文件都下载下来
	public function local(){
		$filedir = $this->dir.'files/';
		for($i = $this->oj['startpage'];$i <= $this->oj['endpage'] && $this->can_go_on();$i++){
			//echo '.';
			$content = file_get_contents($this->oj['pre_single'].$i);
			if(!$content || (!empty($this->preg_error) && preg_match($this->preg_error, $content))) continue;
			if(!empty($this->preg_problem)){
				$match = array();
				$succeed = preg_match($this->preg_problem, $content,$match);
				if($succeed && isset($match[0]))$content = $this->wrapper($match[0],$i);
			}
			//something seems wrong
			/*
			function temp_f($matches){
				return $this->replace_image($matches[1],$this->oj["pre_single"].$i);
			}
			//然后对内容里面的图片进行替换
			preg_replace_callback('|^<img[\s\S]*?src="([\S]*?)">|i','temp_f',$content);
			*/
			file_put_contents($filedir.$this->oj['name'].'_'.$i.'.html', $content);
		}
	}
	//生成链接，然后手工汇总
	public function mkindex(){
		$content = '<div id="'.$this->oj['name'].'_i" class="link_i">'."\n";
		for($i = $this->oj['startpage'];$i <= $this->oj['endpage'];$i++){
			$filename = "files/{$this->oj['name']}_{$i}.html";
			if(!file_exists($this->dir.$filename))continue;
			$content.="<a href=\"{$filename}\">{$i}</a>\n";
		}
		$content.='</div>';
		/*
		$handle = fopen($this->dir.'index.html', 'a');
		fwrite($handle, $content);
		fclose($handle);
		*/
		file_put_contents($this->dir.$this->oj['name'].'_index.html', $content);
	}
	
}