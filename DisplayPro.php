<?php
include('connection.php');

if (isset($_POST['code']) && $_POST['code']!=""){
$code = $_POST['code'];
$sql = "SELECT * FROM products";

/*sql = "SELECT * FROM products INNER JOIN products_images ON products.code = products_images.codem AND products.code = '".$code."'" ;*/
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$name = $row['name'];
$code = $row['code'];
$price = $row['price'];
$image = $row['filename'];

$cartArray = array(
	$code=>array(
	'name'=>$name,
	'code'=>$code,
	'price'=>$price,
	'quantity'=>1,
	'image'=>$image)
);
if(!empty($_SESSION["username"])) {
if(empty($_SESSION["shopping_cart"])) {
	$_SESSION["shopping_cart"] = $cartArray;
	$status = "Product is added to your cart! 1111";
	 echo "<script>alert('$status');</script>";
}else{
	$array_keys = array_keys($_SESSION["shopping_cart"]);
	if(in_array($code,$array_keys)) {
		$status = "Product is already added to your cart!";	
    echo "<script>alert('$status');</script>"; 
	

	} else {
	$_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"],$cartArray);
	$status = "Product is added to your cart!";

    echo "<script>alert('$status');</script>"; 
	//header("Location: index.php");
	
	}

	}
}	else { header("Location: indexlogin.php"); }
}

?>
<html>
<head>
<title>IceCream</title>
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
</head>
<body>
<div style="width:80%; margin:50 auto;">
  


<?php


$sql2 = "SELECT * FROM products";
$result = $connection->query($sql2);
while($row = $result->fetch_assoc()){
		echo "<div class='product_wrapper'>
			  <form method='post' action=''>
			  <input type='hidden' name='code' value=".$row['code'].
/*print_r($row);*/
" />

			  <div class='image'><img src='".$row['filename']."' /></div>
			  <div class='name'>".$row['name']."</div>
		   	  <div class='price'>Rs ".$row['price']."</div>
			  <button type='submit' id='submit' name='submit' class='buy'>Order Now</button>
			  </form>
		   	  </div>";
        }
//mysqli_close($connection);
?>

<div style="clear:both;"></div>

<br /><br />

</div>
</body>
</html>