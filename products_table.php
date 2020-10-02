
<?php
//including the database connection file
include_once("connection.php");
 
//fetching data in descending order (lastest entry first)
//$result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
//$result = mysqli_query($mysqli, "SELECT * FROM users ORDER BY id DESC"); // using mysqli_query instead
//$sql = "SELECT products.* , products_images.* FROM products, products_images WHERE products.id = product_images.products_id";
$sql = "SELECT * FROM products INNER JOIN products_images ON products.id = products_images.product_id";
$result = $connection->query($sql);
?>
 
<html>
<head>    
    <title>Homepage</title>
	<link rel="stylesheet" href="css/editTable.css" type="text/css">
</head>
 
<body>
    <div>
    <table>
        <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Category</th>
			<th>Image</th>
            <th>Price</th>
			<th>Description</th>
			<th>Edit & Delete</th>
        </tr>
        <?php 
        //while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
        while($res = $result->fetch_assoc()) {         
            echo "<tr>";
            echo "<td>".$res['name']."</td>";
            echo "<td>".$res['quantity']."</td>";
            echo "<td>".$res['category']."</td>";
			echo "<td><img src='".$res['filename']."'></td>";
			echo "<td>".$res['price']."</td>";
            echo "<td>".$res['description']."</td>";
            echo "<td><a href=\"edit_page.php?id=$res[id]\">Edit</a> | <a href=\"delete_product_page.php?id=$res[id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
			echo "</tr>";
        }
        ?>
    </table>
	</div>
</body>
</html>