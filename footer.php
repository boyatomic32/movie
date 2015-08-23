		
		<!-- Slidebars -->
		<div class="sb-slidebar sb-left">
			<nav>
				<ul class="sb-menu">
					<li><img src="images/logo-doonungfree.png" alt="Slidebars" width="200" height="45"></li>
					<li><a href="index.php">หน้าแรก</a></li>
					<li style="display: none;"><a href="category.php?cate=new">หนังมาใหม่</a></li>
					<li style="display: none;"><a href="category.php?cate=top">หนังยอดนิยม</a></li>					
					<?php
					$url = "http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/group/";
					$json = post($url);
					$menu = json_decode($json);	          	
			    	if(isset($menu->total) > 0){
			    		foreach ($menu->items as $k1 => $val1) {		    				
					?>
	    			<li class="sb-close"><a href="category.php?cate=<?=$val1->categoryID;?>"><?=$val1->category_thai;?></a></li>
	    			<?php
						}
					}
					?>
					<li><a href="index.php">ติดต่อเรา</a></li>
					<li class="sb-close"><small>Doo Nung Free &copy; 2014 Boyatomic</small></li>
				</ul>
			</nav>
		</div><!-- /.sb-left -->
		<div class="sb-slidebar sb-right sb-style-overlay" style="display: none;">
			<aside id="about-me">
				<img class="img-circle img-responsive img-me" width="150" height="150" src="images/logo-doonungfree.png" alt="Profile Picture">
				<h3>Hello, I'm Adam.</h3>
				<p>I'm a web designer and front-end developer based in Barcelona. I offer bespoke web tailoring and themes for WordPress. For more info you can:</p>
				<ul class="list-unstyled">
					<li class="sb-close"><a href="http://www.adchsm.me/">Visit my site</a></li>
					<li class="sb-close"><a href="https://twitter.com/adchsm">Follow me on Twitter</a></li>
					<li class="sb-close"><a href="http://www.adchsm.me/mailing.php">Sign up to my mailing list</a></li>
				</ul>
			</aside>
		</div><!-- /.sb-right -->
		<!-- Scripts -->
		<!-- jQuery -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>		
		<!-- Bootstrap -->
		<script src="scripts/bootstrap/js/bootstrap.min.js"></script>		
		<!-- Slidebars -->
		<script src="scripts/slidebars/slidebars.min.js"></script>
		<script>
			(function($) {
				$(document).ready(function() {
					// Initiate Slidebars
					$.slidebars();
				});
			}) (jQuery);		
			
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			  ga('create', 'UA-50928762-2', 'auto');
			  ga('send', 'pageview');


		</script>		
	</body>
</html>