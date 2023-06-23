<?php
require_once "pdo.php";
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $productID = $_POST['productID'];
    $productName = $_POST['productName'];
    $minQuantity = $_POST['minQuantity'];
    $quantity = $_POST['quantity'];
    $costPrice = $_POST['costPrice'];
    $salesPrice = $_POST['salesPrice'];

    // Check if the product ID already exists
    $stmt = $pdo->prepare("SELECT productID FROM inventory WHERE productID = ?");
    $stmt->execute([$productID]);
    $existingProduct = $stmt->fetch();

    if ($existingProduct) {
        $error = "Product ID already exists. Please choose a different ID.";

    } else {
        // Insert the product into the database
        $stmt = $pdo->prepare("INSERT INTO inventory (productID, product_name, minQuantity, quantity, costPrice, salesPrice) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$productID, $productName, $minQuantity, $quantity, $costPrice, $salesPrice]);

        // Redirect back to the inventory table page
        header("Location: inventoryTable.php");
        exit(); // Make sure to exit after redirecting
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>

<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    onclick="javascript:window.location='salesTable.php'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="addProduct.php">
                <div class="form-group">
                    <span class="text-danger">
                        <?php echo $error; ?>
                    </span>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="productID">Product ID:</label>
                    <input type="text" class="form-control" id="productID" name="productID" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="productName">Product Name:</label>
                    <input type="text" class="form-control" id="productName" name="productName" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="minQuantity">Minimum Quantity:</label>
                    <input type="number" class="form-control" id="minQuantity" name="minQuantity" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="costPrice">Cost Price:</label>
                    <input type="text" class="form-control" id="costPrice" name="costPrice" pattern="[0-9]+(\.[0-9]{1,2})?" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="salesPrice">Sales Price:</label>
                    <input type="text" class="form-control" id="salesPrice" name="salesPrice" pattern="[0-9]+(\.[0-9]{1,2})?" required>
                </div>
                <button type="submit" class="btn btn-primary mx-3 mt-3">Add</button>
                <button type="button" class="btn btn-secondary mx-3 mt-3" data-dismiss="modal"
                    onclick="javascript:window.location='inventoryTable.php'"> Cancel </button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#addmodal").modal('show');
    });
</script>