<?php
/**
 * ACM achieves collection
 * @author Slacken(xinkiang@gmail.com)
 * @link http://huntist.cn/acm
 * */

include 'acm.php';

$acm = new acm('hduoj|http://acm.hdu.edu.cn/showproblem.php?pid=|1000|4206');

//匹配正文
$acm->setPreg('|<tr><td align=center>([\s\S]*)</td></tr>|');
$acm->setError('|No such problem|');
$acm->setCharset('gb2312');
//下载
$acm->local();
//更新索引文件
$acm->mkindex();