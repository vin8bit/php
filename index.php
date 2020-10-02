

<!DOCTYPE html>
<html>
    <head>
        <title>Icecream Shop </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
        
		<link rel="stylesheet" href="index.css" type="text/css">
		<link rel="stylesheet" href="slider/slider.css" type="text/css">
		<link rel="stylesheet" href="css/footer.css" type="text/css">
        <link rel="stylesheet" href="productstyle.css" type="text/css">
        
    </head>
    <body>
        <div class='nav001'>
		   <?php
            require 'header2.php';
           ?> 
		   
        </div>
		<div class ="slider1">
           <?php
            require 'slider/slider.php';
           ?> 
		<script type="text/javascript" src="slider/slider.js"></script>   
        </div>
        
		<div class ="productdiv">
           <?php
            require 'producttest.php';
           ?> 
        </div>
		
	<!--	<div class = "product1">
           
		 <script type="text/javascript" src="style.js"></script>    
        </div> -->
	
    </body>
</html>