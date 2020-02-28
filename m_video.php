<?php
$v=$_GET[v];
//判断设备
if(empty($v)){
$g_et='true';
}
function isMobile(){    
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';    
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';      
    function CheckSubstrs($substrs,$text){    
        foreach($substrs as $substr)    
            if(false!==strpos($text,$substr)){    
                return true;    
            }    
            return false;    
    }  
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');  
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');    
                
    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||    
              CheckSubstrs($mobile_token_list,$useragent);    
                
    if ($found_mobile){    
        return true;    
    }else{    
        return false;    
    }    
}  
if (isMobile()){}
else{
     //如果电脑访问
header("Location: video.php?v=$v"); 
//确保重定向后，后续代码不会被执行 
exit;
}
//获取原始下载地址
//require 'inc/parser.php';
$parserurl='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
$parserurl=dirname($parserurl);


$geturl= $parserurl.'/parser/index.php?videoid='."$v";

$w=file_get_contents($geturl);

$cv=json_decode($w); 

//print_r($cv);

//echo $cv[Download][1][url];
function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}
$rr=object_array($cv);
$aaaks=array_reverse(($rr[Download]));
$vname=$rr[title];//视频名称


$furl=$aaaks[3][url];//flv视频地址
$murl=$aaaks[3][url];//mp4视频地址
$pagetitle=$vname;

//加密传输视频
// Declare the class
class GoogleUrlApi {
	
	// Constructor
	function GoogleURLAPI($key,$apiURL = 'https://www.googleapis.com/urlshortener/v1/url') {
		// Keep the API Url
		$this->apiURL = $apiURL.'?key='.$key;
	}
	
	// Shorten a URL
	function shorten($url) {
		// Send information along
		$response = $this->send($url);
		// Return the result
		return isset($response['id']) ? $response['id'] : false;
	}
	
	// Expand a URL
	function expand($url) {
		// Send information along
		$response = $this->send($url,false);
		// Return the result
		return isset($response['longUrl']) ? $response['longUrl'] : false;
	}
	
	// Send information to Google
	function send($url,$shorten = true) {
		// Create cURL
		$ch = curl_init();
		// If we're shortening a URL...
		if($shorten) {
			curl_setopt($ch,CURLOPT_URL,$this->apiURL);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
			curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
		}
		else {
			curl_setopt($ch,CURLOPT_URL,$this->apiURL.'&shortUrl='.$url);
		}
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		// Execute the post
		$result = curl_exec($ch);
		// Close the connection
		curl_close($ch);
		// Return the result
		return json_decode($result,true);
	}		
}
require 'pheader.php';

// Create instance with key
$key = $gurl_api;
$googer = new GoogleURLAPI($key);

// Test: Shorten a URL
//加密后的视频流链接
$flvurl1 = $googer->shorten("$furl");//flv
$mp4url1 = $googer->shorten("$murl");//mp4
//获取视频列表

$flvurl= $parserurl.'/ytproxy/browse.php?u='.$flvurl1;
$mp4url= $parserurl.'/ytproxy/browse.php?u='.$mp4url1;
$vname1=$vname;

$API_key=$youtube_api;
$jsonurl='https://www.googleapis.com/youtube/v3/search?part=snippet&order=relevance&amp;regionCode=lk&key='.$API_key.'&part=snippet&maxResults=20&relatedToVideoId='.$v.'&type=video';
//To try without API key: $video_list = json_decode(file_get_contents(''));
$video_list = json_decode(file_get_contents($jsonurl));
$video_list1=object_array($video_list);
require 'header.php';
?>
<script src="js/jquery.js"></script>
<script src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<div class="wrapper container">
<?php
if($g_et!=true){ 
echo <<<EOT
<div class="row">
        <div class="col-xs-12" id="video" style="z-index:-1000">
      <!--ckplayer配置开始-->
      <video controls="controls" poster="./thumbnail.php?vid=$v" autoplay="autoplay" width="100%" height="100%">
  <source src="$mp4url" type="video/mp4" />
您的浏览器不支持HTML5播放MP4.
</video>
   
<!--ckplayer配置结束--> 
</div>
<div class="col-xs-12">
<h3>$vname</h3> 
<p>无法播放？<a href="./bakpay.php?v=$v">点此重试</a></p>
<!-- UY BEGIN -->
<div id="uyan_frame"></div>
<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js"></script>
<!-- UY END -->
EOT;
}else{
    echo '<div class="alert alert-danger">错误！非法请求。</div>';
}
?>

</div>
        <div class="col-xs-12">
            <a href="#" 

class="list-group-item active">
  相关视频
</a>
<?php
for($i=0;

$i<=20;$i++){
   echo'<a href="video.php?v='.$video_list1[items]

[$i][id][videoId] .'"target="_blank" class="list-group-item">'.

$video_list1[items][$i][snippet][title].'</a>'; 
    
    
}
?>     

</div>

    </div> 

</div>
<?php require 'footer.php';?>