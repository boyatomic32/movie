<?php
include 'config.php';
$data = array();
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

exit;
foreach ($cate_home as $key => $value) {
	$url_ = 'http://free.appandroidstudio.com/movie4/home/moviehome/'.$key.'.php';	
	$json_ = post($url_);
	$data_ = json_decode($json_);
	$dt = array('name'=>$value,'key'=>$key,'data'=>$data_);	
	array_push($data,$dt);
}
include 'header.php';

$url_new = 'http://free.appandroidstudio.com/movie4/getvideo2/movie/movienew.php';	
$json_new = post($url_new);
$data_new = json_decode($json_new);

$url_hot = 'http://free.appandroidstudio.com/movie4/getvideo2/movie/movietop.php';	
$json_hot = post($url_hot);
$data_hot = json_decode($json_hot);





$limit = 12;
?>		
<!-- Site -->
<div id="sb-site">			
	<div class="container theme-showcase" role="main">
		
		<div class="page-header">
	       <h3><strong>หนังมาใหม่</strong><a href="category.php?cate=movienew" class="btn btn-success btn-xs pull-right">ดูทั้งหมด</a></h3>
	       
	    </div>
		<div class="row" id="movie-2">
			<?php 
	    	if(isset($data_new)){		
	    		foreach ($data_new as $k2 => $value) {
	    		$val2 = (array)$value;
					if($k2 < $limit){	    		
			?>
				<div class="col-md-3">
					<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=str_replace(' ', '-', $val2['category']);?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
						<div class="panel panel-primary">
				            <div class="panel-heading">
				              <h3 class="panel-title nowrap"><?=$val2['name'];?></h3>
				            </div>
				            <div class="panel-body">		              
				              <div class="home-group-img lazy" title="<?=$val2['name'];?>" style="background:url(<?=$val2['ImagePath'];?>) top center no-repeat;background-size:100% 100%;"></div>
				            </div>
				        </div>
			        </a>
		         </div>
	         <?php
					}
				}				
			}
			?>
		</div>
		
		<div class="page-header">
	       <h3><strong>หนังยอดนิยม</strong><a href="category.php?cate=movietop" class="btn btn-success btn-xs pull-right">ดูทั้งหมด</a></h3>
	    </div>
		<div class="row" id="movie-2">
			<?php 
	    	if(isset($data_hot)){		
	    		foreach ($data_hot as $k2 => $value) {
	    		$val2 = (array)$value;
					if($k2 < $limit){	    		
			?>
				<div class="col-md-3">
					<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=str_replace(' ', '-', $val2['category']);?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
						<div class="panel panel-primary">
				            <div class="panel-heading">
				              <h3 class="panel-title nowrap"><?=$val2['name'];?></h3>
				            </div>
				            <div class="panel-body">		              
				              <div class="home-group-img lazy" title="<?=$val2['name'];?>" style="background:url(<?=$val2['ImagePath'];?>) top center no-repeat;background-size:100% 100%;"></div>
				            </div>
				        </div>
			        </a>
		         </div>
	         <?php
					}
				}				
			}
			?>
		</div>
		
		<?php 
    	if(isset($data)){
    		foreach ($data as $k => $val) {		
		?>
		   	
		<div class="page-header">
	       <h3><strong><?=$val['name'];?></strong><a href="category.php?cate=<?=$val['key'];?>" class="btn btn-success btn-xs pull-right">ดูทั้งหมด</a></h3>
	    </div>
		<div class="row" id="movie-<?=$k;?>">
			<?php 
	    	if(isset($val['data'])){		
	    		foreach ($val['data'] as $k2 => $value) {
	    		$val2 = (array)$value;	    		
			?>
			<div class="col-md-3">
				<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=str_replace(' ', '-', $val2['category']);?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
					<div class="panel panel-primary">
			            <div class="panel-heading">
			              <h3 class="panel-title nowrap"><?=$val2['name'];?></h3>
			            </div>
			            <div class="panel-body">		              
			              <div class="home-group-img lazy" title="<?=$val2['name'];?>" style="background:url(<?=$val2['ImagePath'];?>) top center no-repeat;background-size:100% 100%;"></div>
			            </div>
			        </div>
		       </a>
	        </div>
	        <?php					
	        	}
	        }
	        ?>	        
		</div>		
		<?php 
			} 
		}
		?> 	      
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