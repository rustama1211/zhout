<?php if (! defined ("BASEPATH")) die ("No Direct Script Access Allowed");

$config['asset'][] = array('file'=>'jquery.elastic.js','location'=>'zhopie','name'=>'elastis');
$config['asset'][] = array('file'=>'jquery.watermarkinput.js','location'=>'zhopie','name'=>'watermark');
/*
$config['asset'][] = array('file'=>'scrollable-horizontal.css','location'=>'zhopie','name'=>'scroll_horisontal');
$config['asset'][] = array('file'=>'scrollable-horizontal.css','location'=>'zhopie','name'=>'scroll_horisontal');
$config['asset'][] = array('file'=>'scrollable-horizontal.css','location'=>'zhopie','name'=>'scroll_horisontal');
$config['asset'][] = array('file'=>'scrollable-horizontal.css','location'=>'zhopie','name'=>'scroll_horisontal');*/
$config['asset_group']['ZHOUT'] = 'elastis|watermark';
?>
