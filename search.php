<?php
include 'config.php';
$cate = $_REQUEST['cate'];
$keyword = $_REQUEST['keyword'];
$pos = 0;
$data = array();				

/*
$url_ = "http://free.appandroidstudio.com/movie4/getvideo2/movie/search.php?txtKeyword='$keyword'";	
$json_ = post($url_,$arDT);
$data = json_decode($json_); print_r($data);
$count = count($data);
*/

$post = array('txtKeyword' =>$keyword,'pos'   => 0); //print_r($pos);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://free.appandroidstudio.com/movie4/getvideo2/movie/search.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
$response = curl_exec($ch);
$data = json_decode($response);
$count = count($data);

/*
$url_2 = 'http://free.appandroidstudio.com/movie4/getvideo2/movie/'.$cate.'.php?pos='.$count;
$json_2 = post($url_2);
$data2 = json_decode($json_2);
$count2 = count($data2);
if($count2 > 0) $pos = $pos+$count;
 
 */
include 'header.php';
?>
<script>
	var pos = 0;
	var cate = '<?=$cate;?>';
</script>
<!-- Site -->
<div id="sb-site">			
	<div class="container theme-showcase" role="main">
		<div class="page-header">
	       <h3><strong>ค้นหา:<?=$keyword;?></strong></h3>
	    </div>
		<div class="row" id="cate-list">
			<?php 
	    	if($count > 0){		
	    		foreach ($data as $k2 => $value) {
	    		$val2 = (array)$value;	    		
			?>
			<div class="col-md-3">
				<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=$val2['category'];?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
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
	        <script>
	        	pos++;
	        </script>
	        <?php					
	        	}
	        }else{
	        ?>	  
	        <center><h3>ไม่พบรายการหนังที่ท่านค้นหา</h3></center>
	        <?}?>      
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
/*	
	var current_page    =   1;
	var loading         =   false;
	var oldscroll       =   0;
	$(document).ready(function(){
	    $(window).scroll(function() {
	        if( $(window).scrollTop() > oldscroll ){ //if we are scrolling down
	            if( ($(window).scrollTop() + ($(window).height()+35) >= $(document).height()  ) ) {
	                   if( ! loading ){
	                        
	                        //console.log(loading);
	                        var html = '';
	                        var data = {'cate':cate,'pos':pos};
	                        $.post('getjson.php', data, function( jsonData ) {
	                        	console.log(jsonData);
	                        	$.each(jsonData, function (index, items) {
	                        		var strname = items.name;
	                        	    strname = strname.replace(" ", "-");
	                        		html += '<div class="col-md-3">'+
											 '<a href="viewvideo.php?id='+items.ImageID+'&cate='+cate+'&name='+strname+'">'+
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
	                        		 pos++;
	                        	});
	                        	if(html){
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
	*/
</script>