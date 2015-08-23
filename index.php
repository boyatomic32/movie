<?php
include 'config.php';
include 'header.php';
$limit = 8;
$url = 'http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/home/perpage/'.$limit.'/';	
$json = post($url);
$data = json_decode($json);

?>		
<!-- Site -->
<div id="sb-site">			
	<div class="container theme-showcase" role="main">
				
		<?php 
    	if(isset($data->status) == 200){
    		foreach ($data->items as $k => $val) {
    			if($val->total > 0){		
		?>
		   	
		<div class="page-header">
	       <h3><strong><?=$val->category_thai;?></strong><?php if($val->cid){ ?><a href="category.php?cate=<?=$val->cid;?>" class="btn btn-success btn-xs pull-right">ดูทั้งหมด</a><?php } ?></h3>
	    </div>
		<div class="row" id="movie-<?=$k;?>">
			<?php 
	    	if(isset($val->items)){		
	    		foreach ($val->items as $k2 => $value) {
	    			$val2 = (array)$value;						
			?>
			<div class="col-md-3">
				<a href="viewvideo.php?id=<?=$val2['movieID'];?>&name=<?=$val2['name'];?>">
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