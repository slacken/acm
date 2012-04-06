<?php
/**
 * ACM achieves collection
 * @author Slacken(xinkiang@gmail.com)
 * @link http://huntist.cn/acm
 * */

include 'acm.php';

$acm = new acm('jobdu|http://ac.jobdu.com/problem.php?id=|1000|1420');
//匹配正文
$acm->setPreg('|<dl class="main-title-mod mb10">([\s\S]*)</dl>|');
$acm->setError('|请输入正确的题目ID！|');
//下载
$acm->local();
//更新索引文件
$acm->mkindex();