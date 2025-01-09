<?php
session_start();
include 'db.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $container_number = $_POST['container_number'];
    $products = json_encode($_POST['product']); // Storing products as a JSON string
    $quantities = json_encode($_POST['quantity']); // Storing quantities as a JSON string
    $loading_date = $_POST['loading_date'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $shipper_name = $_POST['shipper_name'];
    $consignee = $_POST['consignee'];
    $shipping_line = $_POST['shipping_line'];
    $expected_arrival = $_POST['expected_arrival'];
    $onboard_date = $_POST['onboard_date'];
    $added_by = $_SESSION['user_id'];

    // Sanitize the inputs
    $container_number = $conn->real_escape_string($container_number);
    $loading_date = $conn->real_escape_string($loading_date);
    $source = $conn->real_escape_string($source);
    $destination = $conn->real_escape_string($destination);
    $shipper_name = $conn->real_escape_string($shipper_name);
    $shipping_line = $conn->real_escape_string($shipping_line);
    $expected_arrival = $conn->real_escape_string($expected_arrival);
    $consignee = $conn->real_escape_string($consignee);
    $onboard_date = $conn->real_escape_string($onboard_date);

    // Insert container data into the database
    $sql = "INSERT INTO containers (container_number, products, quantities, loading_date, source_location, destination_location, shipper_name, expected_arrival, added_by, consignee, shipping_line, onboard_date)
            VALUES ('$container_number', '$products', '$quantities', '$loading_date', '$source', '$destination', '$shipper_name', '$expected_arrival', $added_by, '$consignee', '$shipping_line', '$onboard_date')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Container added successfully!";
        header("Location: add_container.php"); // Redirect to the same page
        exit(); // Stop further execution
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Container Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Function to dynamically add product and quantity fields
        function addProductField() {
            var productFieldsContainer = document.getElementById("productFields");
            var newProductField = document.createElement("div");
            newProductField.classList.add("row", "mb-3");
            newProductField.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="product[]" class="form-control" placeholder="Product Name" required>
                </div>
                <div class="col-md-5">
                    <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeProductField(this)">Remove</button>
                </div>
            `;
            productFieldsContainer.appendChild(newProductField);
        }

        // Function to remove a product field
        function removeProductField(button) {
            button.closest('.row').remove();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Add Container Details</h2>
        <?php if (isset($_SESSION['success_message'])) { ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); // Remove the success message ?>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="container_number" class="form-label">Container Number</label>
                <input type="text" name="container_number" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="shipping_line" class="form-label">Shipping Line Name</label>
                <input type="text" name="shipping_line" class="form-control" required>
            </div>

            <!-- Dynamic product fields -->
            <div id="productFields">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" name="product[]" class="form-control" placeholder="Product Name" required>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger" onclick="removeProductField(this)">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProductField()">Add Another Product</button>

            <div class="mb-3">
                <label for="loading_date" class="form-label">Loading Date</label>
                <input type="date" name="loading_date" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="onboard_date" class="form-label">Onboard Date</label>
                <input type="date" name="onboard_date" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="source" class="form-label">Source Location</label>
                <input type="text" name="source" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="destination" class="form-label">Destination Location</label>
                <input type="text" name="destination" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="shipper_name" class="form-label">Shipper Name</label>
                <input type="text" name="shipper_name" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="consignee" class="form-label">Consignee Name</label>
                <input type="text" name="consignee" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="expected_arrival" class="form-label">Expected Arrival Date</label>
                <input type="date" name="expected_arrival" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Container</button>
        </form>
    </div>
</body>
</html>
