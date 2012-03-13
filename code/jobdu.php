<?php
include 'acm.php';

$acm = new acm('jobdu|http://ac.jobdu.com/problem.php?id=|1000|1403');
//匹配正文
$acm->setPreg('|<dl class="main-title-mod mb10">([\s\S]*)</dl>|');
//下载
$acm->local();
//生成索引文件
$acm->mkindex();