
<?php
include_once("connection.php");
$id=$_REQUEST['id'];
$query = "DELETE FROM products WHERE id=$id"; 
$query2 = "DELETE FROM products_images WHERE product_id=$id"; 
if ($connection->query($query) === TRUE) {
	if ($connection->query($query2) === TRUE) {
     echo "Record deleted successfully";
     header("Location: products_table.php");
	}  
} else {
  echo "Error deleting record: " . $connection->error;
}

?>