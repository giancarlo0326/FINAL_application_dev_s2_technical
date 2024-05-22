<?php
// Start a PHP session to store user inputs
session_start();

// Check if form data is submitted and store it in session variables
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the delete all button is clicked
    if (isset($_POST['deleteAll'])) {
        // Clear all stored devices
        unset($_SESSION['devices']);
    } else if (isset($_POST['delete'])) {
        // Check if the delete button for a specific item is clicked
        $index = $_POST['delete']; // Get the index of the item to be deleted
        deleteDevice($index);
    } else if (isset($_POST['editSubmit'])) {
        // Check if the inline edit form is submitted
        $index = $_POST['editIndex']; // Get the index of the device
        $name = isset($_POST['editName']) ? $_POST['editName'] : $_SESSION['devices'][$index]['name'];
        $brand = isset($_POST['editBrand']) ? $_POST['editBrand'] : $_SESSION['devices'][$index]['brand'];
        $price = isset($_POST['editPrice']) ? $_POST['editPrice'] : $_SESSION['devices'][$index]['price'];
        $quantity = isset($_POST['editQuantity']) ? $_POST['editQuantity'] : $_SESSION['devices'][$index]['quantity'];
        updateDevice($index, $name, $brand, $price, $quantity);
    } else if (isset($_POST['buy'])) {
        // Check if the buy button for a specific item is clicked
        $index = $_POST['buy']; // Get the index of the item to be bought
        buyDevice($index);
    } else {
        // Assume a new device is being added
        addDevice($_POST['name'], $_POST['brand'], $_POST['price'], $_POST['quantity']);
    }
}

// Function to add a new device
function addDevice($name, $brand, $price, $quantity) {
    if (!isset($_SESSION['devices'])) {
        $_SESSION['devices'] = [];
    }
    array_push($_SESSION['devices'], [
        'name' => $name,
        'brand' => $brand,
        'price' => $price,
        'quantity' => $quantity
    ]);
}

// Function to update device details
function updateDevice($index, $name, $brand, $price, $quantity) {
    $_SESSION['devices'][$index] = [
        'name' => $name,
        'brand' => $brand,
        'price' => $price,
        'quantity' => $quantity
    ];
}

// Function to delete a device
function deleteDevice($index) {
    unset($_SESSION['devices'][$index]);
}

// Function to buy a device
function buyDevice($index) {
    $device = $_SESSION['devices'][$index];
    echo "<script>";
    echo "alert('You have bought ".$device['name']." with ".$device['brand']." for ".$device['price']." each and ".$device['quantity']." quantity.');";
    echo "</script>";
    // Delete the bought item
    deleteDevice($index);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Devices - HJT ELECTRONIC DEVICES STORE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://i.postimg.cc/mD3gP4TS/polotno-4-removebg.png" class="header-img" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="device.php">View Devices</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="centered-container">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>View Devices</h2>
                            <div>
                                <!-- PHP code to display user inputs -->
                                <?php
                                // Check if there are stored devices in session
                                if (isset($_SESSION['devices']) && !empty($_SESSION['devices'])) {
                                    // Loop through stored devices and display them
                                    foreach ($_SESSION['devices'] as $index => $device) {
                                        echo "<p><strong>Name:</strong> " . $device['name'] . "</p>";
                                        echo "<p><strong>Brand:</strong> " . $device['brand'] . "</p>";
                                        echo "<p><strong>Price:</strong> " . $device['price'] . "</p>";
                                        echo "<p><strong>Quantity:</strong> " . $device['quantity'] . "</p>";

                                        // Buttons for edit, delete, and buy
                                        echo '<form action="device.php" method="post">';
                                        echo '<button type="submit" class="btn btn-danger" name="delete" value="' . $index . '">Delete</button>';
                                        echo '<button type="button" class="btn btn-primary" onclick="showEditForm(' . $index . ')">Edit</button>';
                                        echo '<button type="submit" class="btn btn-success" name="buy" value="' . $index . '">Buy</button>';
                                        echo '</form>';

                                        // Inline edit form
                                        echo '<div id="editForm' . $index . '" style="display: none;">';
                                        echo '<form action="device.php" method="post">';
                                        echo '<input type="hidden" name="editIndex" value="' . $index . '">';
                                        echo 'Name: <input type="text" name="editName" value="' . $device['name'] . '"><br>';
                                        echo 'Brand: <input type="text" name="editBrand" value="' . $device['brand'] . '"><br>';
                                        echo 'Price: <input type="number" name="editPrice" value="' . $device['price'] . '"><br>';
                                        echo 'Quantity: <input type="number" name="editQuantity" value="' . $device['quantity'] . '"><br>';
                                        echo '<button type="submit" class="btn btn-success" name="editSubmit">Confirm Edit</button>';
                                        echo '</form>';
                                        echo '</div>';

                                        // Buy form (hidden)
                                        echo '<form id="buyForm'.$index.'" action="device.php" method="post" style="display:none;">';
                                        echo '<input type="hidden" name="buy" value="' . $index . '">';
                                        echo '</form>';

                                        echo "<hr>"; // Add a horizontal line for separation
                                    }
                                } else {
                                    echo "<p>No devices added yet.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete All Button -->
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form action="device.php" method="post">
                    <button type="submit" class="btn btn-danger" name="deleteAll">Delete All</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function showEditForm(index) {
            var editForm = document.getElementById("editForm" + index);
            if (editForm.style.display === "none") {
                editForm.style.display = "block";
            } else {
                editForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
