<?php
require_once "pdo.php";
session_start();

// Check if sales ID is provided in the URL
if (!isset($_GET['sales_id'])) {
    header("Location: salesTable.php");
    exit();
}

$salesID = $_GET['sales_id'];

// Get product names from the inventory table
$stmt = $pdo->query("SELECT productID, product_name FROM inventory");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve the sales details from the database
$stmt = $pdo->prepare("SELECT * FROM sales WHERE salesID = ?");
$stmt->execute([$salesID]);
$sales = $stmt->fetch();

// Check if the sales exists
if (!$sales) {
    header("Location: salesTable.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $salesID = $_POST['salesID'];
    $productID = $_POST['productID'];
    $salesDate = $_POST['salesDate'];
    $quantity = $_POST['quantity'];

    // Update the product in the database
    $stmt = $pdo->prepare("UPDATE sales SET productID = ?, salesDate = ?, quantity = ?WHERE salesID = ?");
    $stmt->execute([$productID, $salesDate, $quantity, $salesID]);

    // Redirect back to the inventory table page
    header("Location: salesTable.php");
    exit();
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

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Edit Sales</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    onclick="javascript:window.location='salesTable.php'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <span class="text-danger">
                        <?php echo $error; ?>
                    </span>
                </div>
                <input type="hidden" type="text" class="form-control" id="salesID" name="salesID"
                    value="<?php echo $sales['salesID']; ?>" required>
                <div class="form-group mx-3 mt-3">
                    <label for="productID">Product:</label>
                    <select class="form-control" id="productID" name="productID">
                        <option value="">Select Product</option>
                        <?php
                        foreach ($products as $product) {
                            $selected = ($sales['productID'] == $product['productID']) ? 'selected' : '';
                            echo '<option value="' . $product['productID'] . '" ' . $selected . '>' . $product['product_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="salesDate">Sales Date:</label>
                    <input type="date" class="form-control" id="salesDate" name="salesDate"
                        value="<?php echo $sales['salesDate']; ?>" required>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="<?php echo $sales['quantity']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary mx-3 mt-3">Update</button>
                <button type="button" class="btn btn-secondary mx-3 mt-3" data-dismiss="modal"
                    onclick="javascript:window.location='salesTable.php'">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#editmodal").modal('show');
    });
</script>