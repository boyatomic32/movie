<?php
include 'config.php';
$rs = array();
$id = $_REQUEST['id'];

$url = 'http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/play/mid/'.$id.'/perpage/'.$limit.'/';	
$json = post($url);
$data = json_decode($json);
$name = $data->items->name;
$file = $data->items->urlSD;
if($data->items->urlHD){
	$file = $data->items->urlHD;
}

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
				<video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered video-js-fullscreen" controls preload="none" width="auto" height="auto" poster="" data-setup='{ "controls": true, "autoplay": true, "preload": "auto" }'>
				    <source src="<?=$file;?>" type='video/mp4' />
				    <source src="<?=$file;?>" type='video/webm' />
				    <source src="<?=$file;?>" type='video/ogg' />				    
				</video>
	        </div>	        	        	        
		</div>
		<div class="page-header">
	       <h1><strong>หนังแนะนำ</strong></h1>
	    </div>
	    <div class="row">
	    	<?php 
	    	$url = "http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/random/page/1/perpage/12/";
			$json = post($url);
			$data = json_decode($json);
	    	if(isset($data->status) == 200){		
	    		foreach ($data->items as $k2 => $value) {
	    		$val2 = (array)$value;				   		
			?>
			<div class="col-md-4">
				<a href="viewvideo.php?id=<?=$val2['movieID'];?>&name=<?=$val2['name'];?>">
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