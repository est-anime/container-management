<?php
session_start();
include 'db.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch containers added by the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM containers WHERE added_by = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Containers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>View Containers Detail</h2>
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Container Number</th>
                        <th>Shipping Line Name</th>
                        <th>Products and Quantities</th>
                        <th>Loading Date</th>
                        <th>Onboard Date</th>
                        <th>Source Location</th>
                        <th>Destination Location</th>
                        <th>Shipper Name</th>
                        <th>Consignee Name</th>
                        <th>Expected Arrival</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()) { 
                        $products = json_decode($row['products'], true);
                        $quantities = json_decode($row['quantities'], true);
                    ?>
                        <tr>
                            <td><?php echo $row['container_number']; ?></td>
                            <td><?php echo $row['shipping_line']; ?></td>
                            <td>
                                <?php
                                // Display each product with its quantity
                                for ($i = 0; $i < count($products); $i++) {
                                    echo $products[$i] . ": " . $quantities[$i] . "<br>";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['loading_date']; ?></td>
                            <td><?php echo $row['onboard_date']; ?></td>
                            <td><?php echo $row['source_location']; ?></td>
                            <td><?php echo $row['destination_location']; ?></td>
                            <td><?php echo $row['shipper_name']; ?></td>
                            <td><?php echo $row['consignee']; ?></td>
                            <td><?php echo $row['expected_arrival']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No containers added yet.</p>
        <?php } ?>
    </div>
</body>
</html>
