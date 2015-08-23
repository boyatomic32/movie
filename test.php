<html>
	<title>test</title>
	<header>
		<script language="javascript" src="http://www.ninenik.com/js/jquery-1.7.1.min.js"></script>  
	</header>
	<body>		
		<script type="text/javascript"> 		
		  function getdata(){
	  		var url="http://fmkonkhonradio.com/movies/api/index/key/boyatomic/type/home/";
			var dataSet = {};
			$.post(url, dataSet, function(data) {
				console.log(data);
			},'json');
		  }
		</script>  
	</body>
</html>