<?php
require_once "pdo.php";
session_start();

// Check if product ID is provided in the URL
if (!isset($_GET['product_id'])) {
    header("Location: inventoryTable.php");
    exit();
}

$productID = $_GET['product_id'];

// Retrieve the product details from the database
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE productID = ?");
$stmt->execute([$productID]);
$product = $stmt->fetch();

// Check if the product exists
if (!$product) {
    header("Location: inventoryTable.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $productID = $_POST['productID'];
    $product_name = $_POST['product_name'];
    $minQuantity = $_POST['minQuantity'];
    $quantity = $_POST['quantity'];
    $costPrice = $_POST['costPrice'];
    $salesPrice = $_POST['salesPrice'];

    // Update the product in the database
    $stmt = $pdo->prepare("UPDATE inventory SET product_name = ?, minQuantity = ?, quantity = ?, costPrice = ?, salesPrice = ? WHERE productID = ?");
    $stmt->execute([$product_name, $minQuantity, $quantity, $costPrice, $salesPrice, $productID]);

    // Redirect back to the inventory table page
    header("Location: inventoryTable.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="exampleModalLabel">Edit Product</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location='salesTable.php'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="">
          <div class="form-group">
              <span class="text-danger">
                  <?php echo $error; ?>
              </span>
          </div>
          
              <input  type="hidden" type="text" class="form-control" id="productID" name="productID" value="<?php echo $product['productID']; ?>" required>
          
          <div class="form-group mx-3 mt-3">
              <label for="product_name">Product Name:</label>
              <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
          </div>
          <div class="form-group mx-3 mt-3">
              <label for="minQuantity">Minimum Quantity:</label>
              <input type="number" class="form-control" id="minQuantity" name="minQuantity" value="<?php echo $product['minQuantity']; ?>" required>
          </div>
          <div class="form-group mx-3 mt-3">
              <label for="quantity">Quantity:</label>
              <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
          </div>
          <div class="form-group mx-3 mt-3">
              <label for="costPrice">Cost Price:</label>
              <input type="number" class="form-control" id="costPrice" name="costPrice" value="<?php echo $product['costPrice']; ?>" required>
          </div>
          <div class="form-group mx-3 mt-3">
              <label for="salesPrice">Sales Price:</label>
              <input type="number" class="form-control" id="salesPrice" name="salesPrice" value="<?php echo $product['salesPrice']; ?>" required>
          </div>
          <button type="submit" class="btn btn-primary mx-3 mt-3">Update</button>
          <button type="button" class="btn btn-secondary mx-3 mt-3" data-dismiss="modal" onclick="javascript:window.location='inventoryTable.php'">Cancel</button>
      </form>
      </div>
    </div>
  </div>

  <script>
      $(document).ready(function(){
          $("#editmodal").modal('show');
      });
  </script>