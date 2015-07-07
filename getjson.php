<?php
include 'function.php';
$cate = $_REQUEST['cate'];
$pos = $_REQUEST['pos'];
$url_ = 'http://free.appandroidstudio.com/movie4/getvideo2/movie/'.$cate.'.php?pos='.$pos;	
$json_ = post($url_);
echo $json_;
?>