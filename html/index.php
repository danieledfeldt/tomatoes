<?php 
	if(isset($_GET['action']) && isset($_POST['delete_pictures'])){
		if(is_array($_POST['delete_pictures'])){
			foreach ($_POST['delete_pictures'] as $key => $entry) {
				if ($entry != "." && $entry != ".." && $entry != "camera.sh") {
		        	$ext = substr($entry, strrpos($entry, '.') + 1);
				    if(in_array($ext, array("jpg","jpeg","png","gif"))){
						unlink("tomatoes/" . $entry); 
				    }
	        	}
			}
		}
		header("Location: " .  $_SERVER['PHP_SELF']);
		die();
	}

	if($_GET['action'] == 'takephoto'){
		$ok = shell_exec("sh tomatoes/camera.sh"); 
		if($ok = "ok"){
			header("Location: " .  $_SERVER['PHP_SELF']);
			die();
		}else{
			// Might have to run sudo chmod 777 /dev/vchiq
		}
	}else{
	//	No action performed 
	}
?><!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="stylesheet" href="css/milligram.css">
	<link rel="stylesheet" href="css/dh.css?v=0.11">
	<script src="https://cdn.plyr.io/2.0.4/plyr.js"></script>
	<link rel="stylesheet" href="https://cdn.plyr.io/2.0.4/plyr.css">
	<script src="js/jquery.swipebox.js"></script>
	<link rel="stylesheet" href="css/swipebox.css">
	<script src="js/unveil.js"></script>
	<script src="js/dh.js"></script>
	<title>Tomater</title>
</head>
<body>
	<div class="container">

		<div class="row">
			<div class="column center intro-paragraph"><h1>TOMATER!</h1></div>
		</div>
		<?php
		//Read Files and collect. 
		$imgFiles = array(); 
		if ($handle = opendir('./tomatoes')) {

		    while (false !== ($entry = readdir($handle))) {

		        if ($entry != "." && $entry != ".." && $entry != "camera.sh") {

		        	$ext = substr($entry, strrpos($entry, '.') + 1);
				    if(in_array($ext, array("jpg","jpeg","png","gif"))){

				    	//Only thumbphotos 
				    	if (strpos($entry, 'thumb_') !== false)
	    					array_push($imgFiles, $entry);
				    }
		        }
		    }

		    closedir($handle);
		}

		rsort($imgFiles); 

		if($_GET['action'] == "deletephotos" && $_GET['sure'] == "yes" && count($imgFiles) > 0){
			?>
		   <div class="row">
				<div class="column">
					<h3>Delete pictures</h3>
					<form action="#" method="POST">
			  			<fieldset>
			  			<?php 
			  			foreach ($imgFiles as $key => $value) {
			  				?>
							<input type="checkbox" id="<?php echo $value; ?>" name="delete_pictures[]" value="<?php echo $value; ?>">
			     		 	<label class="label-inline" for="<?php echo $value; ?>">
			     		 	<?php 
			     		 	echo $value; 

			     		 	?>
			     		 	</label><br>
			  				<?php 
			  			}
			  			?>
			     		 <button style="margin-top: 20px;">Delete pictures entirerly</button>
			  			</fieldset>
			  		</form>
				</div>
			</div>
			<?php
		}
		?>
		<div class="row">
			<div class="column video-container">
				<video class="plyr" width="320" height="240">
				  <source src="tomatoes/latest.mp4" type="video/mp4">
				</video>
			</div>
		</div>
		<?php
		$count = 0; 
		foreach ($imgFiles as $key => $value) {
		?> 
		   <div class="row">
				<div class="column align-center">
					<?php 

					$bigimg = str_replace("thumb_800_", "", $value); 

					$value = str_replace("thumb_800_", "", $value); 
					$pictureString = substr($value, 0, -9);
					$time  = substr($value, 11, -4);
					$time = substr_replace($time, ":", 2, 0);
					$pictureString .= " " . str_replace("_", ":", $time); 

					if($count >= 10){
						?>
						<a href="tomatoes/<?php echo $bigimg; ?>" class="swipebox" title="<?php echo $pictureString;  ?>">
							<img src="onepixel.png" class="unveil" data-src='/tomatoes/<?php echo $value; ?>'>
						</a>
						<?php 
					}else{
						?>
						<a href="tomatoes/<?php echo $bigimg; ?>" class="swipebox" title="<?php echo $pictureString;  ?>">
							<img src="/tomatoes/<?php echo $value; ?>" class="unveil">
						</a>
						<?php 
					}
					?>
					<p>
						Picture taken <?php echo $pictureString;  ?>
					</p>
				
				</div>
			</div>
		
			<?php
			$count++; 
		}

		?>
	</div> <!-- End of container -->
</body>
</html>