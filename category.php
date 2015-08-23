<?php
include 'config.php';
$page = 1;
$cate = $_REQUEST['cate'];
$url_ = 'http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/category/cid/'.$cate.'/page/'.$page.'/perpage/32/';	
$json_ = post($url_);
$data = json_decode($json_);
$count = count($data);
include 'header.php';
?>
<script>
	var page = '<?=$page;?>';
	var cate = '<?=$cate;?>';
</script>
<!-- Site -->
<div id="sb-site">			
	<div class="container theme-showcase" role="main">
		<div class="page-header">
	       <h3><strong><?=$data->category_thai;?></strong></h3>
	    </div>
		<div class="row" id="cate-list">
			<?php 
	    	if(isset($data->total) > 0){		
	    		foreach ($data->items as $k2 => $value) {
	    		$val2 = (array)$value;
						
			?>
			<div class="col-md-3">
				<a href="viewvideo.php?id=<?=$val2['movieID'];?>&name=<?=$val2['name'];?>">
					<div class="panel panel-primary">
			            <div class="panel-heading">
			              <h3 class="panel-title nowrap"><?=$val2['name'];?></h3>
			            </div>
			            <div class="panel-body">
			              <div class="home-group-img" title="<?=$val2['name'];?>" style="background:url(<?=$val2['ImagePath'];?>) top center no-repeat;background-size:100% 100%;"></div>
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
<script>	
	var current_page    =   2;
	var loading         =   false;
	var oldscroll       =   0;
	$(document).ready(function(){
	    $(window).scroll(function() {
	        if( $(window).scrollTop() > oldscroll ){ 
	            if( ($(window).scrollTop() + ($(window).height()+35) >= $(document).height()  ) ) {
	                   if( ! loading ){                        
	                        
	                        var html = '';
	                        var data = {'cid':cate,'page':current_page};
	                        console.log(data);
	                        var apiURL = 'http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/category/perpage/32/';
	                        $.post(apiURL, data, function( jsonData ) {
	                        	if(jsonData.total > 0){
	                        	$.each(jsonData.items, function (index, items) {
	                        		var strname = items.name;
	                        		var url = items.SD;
	                        		if(items.url){
	                        			url  = items.url;
	                        		}
	                        		
	                        		html += '<div class="col-md-3">'+
											 '<a href="viewvideo.php?id='+items.movieID+'&name='+items.name+'" >'+
												'<div class="panel panel-primary">'+
										            '<div class="panel-heading">'+
										              '<h3 class="panel-title nowrap">'+items.name+'</h3>'+
										            '</div>'+
										            '<div class="panel-body">'+
										              '<div class="home-group-img" title="'+items.name+'" style="background:url('+items.ImagePath+') top center no-repeat;background-size:100% 100%;"></div>'+
										            '</div>'+
										        '</div>'+
									       	  '</a>'+
								            '</div>';		                        		
	                        		
	                        	});
	                        	}
	                        	if(html){
	                        		current_page++;
	                        		$('#cate-list').append(html);
	                        	}else{
	                        		loading = true;
	                        	}
	                        	
	                        },'json');
	                   }
	            }
	        }
	    });
	 
	});
</script>