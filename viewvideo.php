<?php
include 'config.php';
$rs = array();
$id = $_REQUEST['id'];
$cate = $_REQUEST['cate'];
$name = $_REQUEST['name'];
$type = $_REQUEST['type'];
if(empty($type)) $type = 'hd';
if(!empty($id)){
	//$id = base64_decode($id);	
	$url = 'http://free.appandroidstudio.com/movie4/getvideo2/videoview.php';
	$params = array('imageid'=>$id);
	$json = post($url,$params);
	$rs = (array)json_decode($json);	
}

$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/offer.php';	
$json_ = post($url_);
$data1 = json_decode($json_);
$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/offer.php';	
$json_ = post($url_);
$data2 = json_decode($json_);
$result = array_merge($data1, $data2);
$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/offer.php';	
$json_ = post($url_);
$data3 = json_decode($json_);
$result = array_merge($result, $data3);
$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/offer.php';	
$json_ = post($url_);
$data4 = json_decode($json_);
$result = array_merge($result, $data4);
$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/offer.php';	
$json_ = post($url_);
$data5 = json_decode($json_);
$data = array_merge($result, $data5);
include 'header.php';
?>
		
<link href="video-js/video-js.css" rel="stylesheet" type="text/css">
<script src="video-js/video.js"></script>

<script>
    videojs.options.flash.swf = "../video-js/video-js.swf";
</script>
<style>
	.video-js {padding-top: 50%}
	.vjs-fullscreen {padding-top: 0px}
</style>
<!-- Site -->
<div id="sb-site">			
	<div class="container theme-showcase" role="main">
		
    	<div class="page-header">
    		<h3><strong><?=$name;?></strong></h3>
    	</div>
		<div class="row">			
			<div class="col-md-12">				
				<?php if(!empty($rs['urlhd']) && $type == 'hd'){ ?>
					<video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered video-js-fullscreen" controls preload="none" width="auto" height="auto" poster="" data-setup='{ "controls": true, "autoplay": true, "preload": "auto" }'>
					    <source src="<?=$rs['urlhd'];?>" type='video/mp4' />
					    <source src="<?=$rs['urlhd'];?>" type='video/webm' />
					    <source src="<?=$rs['urlhd'];?>" type='video/ogg' />				    
					</video>					
				<?php }else{ ?>	
					<video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered video-js-fullscreen" controls preload="none" width="auto" height="auto" poster="" data-setup='{ "controls": true, "autoplay": true, "preload": "auto" }'>
					    <source src="<?=$rs['urlsd'];?>" type='video/mp4' />
					    <source src="<?=$rs['urlsd'];?>" type='video/webm' />
					    <source src="<?=$rs['urlsd'];?>" type='video/ogg' />				    
					</video>
				<?php } ?>
	        </div>	        	        	        
		</div>
		<div class="page-header">
	       <h1><strong>หนังแนะนำ</strong></h1>
	    </div>
	    <div class="row">
	    	<?php 
	    	if(isset($data)){		
	    		foreach ($data as $k2 => $value) {
	    		$val2 = (array)$value;	    		
			?>
			<div class="col-md-4">
				<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=str_replace(' ', '-', $val2['category']);?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
					<div class="panel panel-primary">
			            <div class="panel-heading">
			              <h3 class="panel-title nowrap"><?=$val2['name'];?></h3>
			            </div>
			            <div class="panel-body">
			              <div class="home-group-img" style="background:url(<?=$val2['ImagePath'];?>) top center no-repeat;background-size:100% 100%;"></div>
			            </div>
			        </div>
		       </a>
	        </div>
	        <?php					
	        	}
	        }
	        ?>
	    </div>		      
	    <footer>
			<div class="row">
				<div class="col-xs-12">
					<small>Doo Nung Free &copy; 2015 <a href="">boyatomic</a></small>
				</div>
			</div>
		</footer>
	</div><!-- /.container -->
</div><!-- /#sb-site -->
<?php
include 'footer.php';
?>