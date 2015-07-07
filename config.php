<?php
include 'function.php';
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
				'getseries'=>'ซีรีย์ฝรั่ง'
			  );
$cate_home = array( 'action'=>'หนังแอ็คชั่น',
					'ghost'=>'หนังสยองขวัญ',
					'drama'=>'หนังดราม่า',
					'family'=>'หนังครอบครัว',
					'love'=>'หนังรัก/โรแมนติก',
					'joke'=>'หนังตลก',
					'cartoons'=>'การ์ตูน/อนิเมชั่น',
					'fantasy'=>'นวนิยายแฟนตาซี',
					'gation'=>'หนังลึกลับและสืบสวน',
					'soundtrack'=>'sound track',
					'getHomekorea'=>'ซรีย์เกาหลี/เอเชีย',
					'getHomeseries'=>'ซีรีย์ฝรั่ง',
				);

/*


		 // Open the file for reading 
		$fp = fopen("countfile.txt", "r"); 
		
		// Get the existing count 
		$count = fread($fp, 1024); 
		
		// Close the file 
		fclose($fp); 
		
		// Add 1 to the existing count 
		$count = $count + 1; 
		
		// Display the number of hits 
		// If you don't want to display it, comment out this line 
		//echo "<p>Page views:" . $count . "</p>"; 
		
		// Reopen the file and erase the contents 
		$fp = fopen("countfile.txt", "w"); 
		
		// Write the new count to the file 
		fwrite($fp, $count); 
		
		// Close the file 
		fclose($fp); 
*/

?>