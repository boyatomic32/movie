<?php
include 'config.php';
$cate = $_REQUEST['cate'];
$pos = 0;
$data = array();				
$menu = array(  'moviethai'=>'หนังไทย',
				'movieaction'=>'หนังแอ็คชั่น',
				'moviegation'=>'หนังลึกลับและสืบสวน',
				'moviefantasy'=>'นวนิยายแฟนตาซี',
				'moviejoke'=>'หนังตลก',
				'movielove'=>'หนังรัก/โรแมนติก',
				'moviefamily'=>'หนังครอบครัว',
				'movieghost'=>'หนังสยองขวัญ',
				'moviedrama'=>'หนังดราม่า',
				'moviecartoons'=>'การ์ตูน/อนิเมชั่น',
				'moviesound'=>'sound track',
				'getkorea'=>'ซรีย์เกาหลี/เอเชีย',
				'getseries'=>'ซีรีย์ฝรั่ง',
				'movietop'=>'หนังยอดนิยม',
				'movienew'=>'หนังมาใหม่'
			  );

$url_ = 'http://free.appandroidstudio.com/movie4/getvideo2/movie/'.$cate.'.php?pos='.$pos;	
//echo $url_;
$json_ = post($url_);
$data = json_decode($json_);
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
	       <h3><strong><?=$menu[$cate];?></strong></h3>
	    </div>
		<div class="row" id="cate-list">
			<?php 
	    	if(isset($data)){		
	    		foreach ($data as $k2 => $value) {
	    		$val2 = (array)$value;	    		
			?>
			<div class="col-md-3">
				<a href="viewvideo.php?id=<?=$val2['ImageID'];?>&cate=<?=$cate;?>&name=<?=str_replace(' ', '-', $val2['name']);?>">
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
</script>