<html>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="referrer" content="no-referrer" />
<?php
header("Content-type: text/html; charset=utf-8"); 
error_reporting(E_ALL ^ E_NOTICE);
$aid = $_GET["aid"];
$bvid = $_GET["bvid"];

echo "<br>";
if ($aid == null && $bvid == null) {
		echo "选择av时  输入视频av号"."<br>";
		echo "选择bv时  输入视频bv号";
}else{
	$isav = false;
	if($aid != null){
		$_aid = str_ireplace("av","",$aid);
		$isav = true;
	}else if($bvid != null){
		if(stripos($bvid,'BV') !== false){ 
			$_bvid = $bvid; 
		}else{
			$_bvid = "BV".$bvid;
		}
		$isav = false;
	}
	if($isav){
		if($_aid > 0){
			echo "查询的av号：av" . $_aid . "<br>";
    		echo "bv号：<font color='red'>" . $bv=enc($_aid) . "</font><br>";
    		$file_contents = curl_get_https('https://api.bilibili.com/x/web-interface/view?aid=' . $_aid);
		}
	}else{
		echo "查询的bv号：" . $_bvid . "<br>";
    	echo "av号：<font color='red'>av" . $av=dec($_bvid) . "</font><br>";
    	$file_contents = curl_get_https('https://api.bilibili.com/x/web-interface/view?bvid=' . $_bvid);
	}
	$arr = json_decode($file_contents,true);
	if($arr['code'] == -400){
    	echo "参数输入有误"."<br>";
    	echo "选择av时  输入视频av号"."<br>";
		echo "选择bv时  输入视频bv号";
    }else if($arr['code'] == 62002){
    	echo "稿件不可见";
    }else if($arr['code'] == -404){
    	echo "你查找的视频不存在";
    }else if($arr['code'] == -403){
    	echo "访问权限不足";
    }else if($arr['code'] == 0){
    	$pic = $arr['data']['pic'];
    	$title = $arr['data']['title'];
    	echo "视频标题：" . $title . "<br>";
    	echo "UP主：" . "<a href='https://space.bilibili.com/" . $arr['data']['owner']['mid'] . "' target='_blank'>" . $arr['data']['owner']['name'] . "</a><br>"; 
    	if($isav){
    		echo "<a href='https://www.bilibili.com/video/". strip_tags($bv) ."' target='_blank'>点击跳转到该视频</a><br>";
    	}else{
    		echo "<a href='https://www.bilibili.com/video/av". strip_tags($av) ."' target='_blank'>点击跳转到该视频</a><br>";
    	}
    	echo "<a href=". $pic ." title='". $title ."' target='_blank'><img src='" . $pic . "' width='192' height='108'></a>";
    }else{
    	echo "发生意料之外的错误";
    }
}

function curl_get_https($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $tmpInfo = curl_exec($curl);
    curl_close($curl);
    return $tmpInfo;
}
function dec($x){
	$table = 'fZodR9XQDSUm21yCkr6zBqiveYah8bt4xsWpHnJE7jL5VG3guMTKNPAwcF';
	$tr = array();
	for ($i=0;$i<58;$i++) {
		$tr[$table[$i]]=$i;
	}
	$s = array(11,10,3,8,4,6);
	$xor=177451812;
	$add=8728348608;
	$r = 0;
	for ($i=0;$i<6;$i++) {
		$r += $tr[$x[$s[$i]]]*pow(58,$i);
	}
	$av = $r-$add^$xor;
	return $av;
}
function enc($x){
	$table = 'fZodR9XQDSUm21yCkr6zBqiveYah8bt4xsWpHnJE7jL5VG3guMTKNPAwcF';
	$tr = array();
	for ($i=0;$i<58;$i++) {
		$tr[$table[$i]]=$i;
	}
	$s = array(11,10,3,8,4,6);
	$xor=177451812;
	$add=8728348608;
	$x=($x^$xor)+$add;
	$r="BV1  4 1 7  ";
	for ($i=0;$i<6;$i++) {
		$r[$s[$i]]=$table[floor($x/pow(58,$i))%58];
	}
	$bv = $r;
	return $bv;
}
?>
</html>