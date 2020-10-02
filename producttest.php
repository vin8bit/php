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
<?php


$sql2 = "SELECT * FROM products";
$result = $connection->query($sql2);
echo "<div class='productcontainer'>";
while($row = $result->fetch_assoc()){
    echo " <div class='card'>
    <form method='post' action=''>
			  <input type='hidden' name='code' value=".$row['code'].
/*print_r($row);*/
" />
                <div class='imgBx'><img src='".$row['filename']."' alt=''>
                </div>
                <div class='content'><h2>".$row['name']." </h2>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Neque quasi aliquam, iusto cumque aliquid at dolorem repellat</p></div>
            <div class='button'><button type='submit' id='submit' name='submit' class='buy'> ₹".$row['price']."</button></div>

            </div>";
}
      echo  "</div>";