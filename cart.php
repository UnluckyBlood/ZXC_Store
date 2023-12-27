<?php
session_start();
include('connection.php');

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка наличия выбранного размера
if (isset($_POST['size_id'])) {
    $_SESSION['selected_size'] = $_POST['size_id'];
}

// Запрос корзины
$sqlCart = "SELECT 
                cart.id_cart,
                cart.id_product,
                cart.id_size,
                cart.quantity,
                cart.price,
                products.name AS product_name,
                sizes.size_name
            FROM 
                cart
            JOIN 
                products ON cart.id_product = products.id
            JOIN 
                sizes ON cart.id_size = sizes.id_size";

$resultCart = $conn->query($sqlCart);

if ($resultCart === false) {
    die("Error in the cart query: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/main.css" rel="stylesheet">
    <title>Gabimaru</title>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="left">
                <div class="brand">
                    <ul>
                        <li><a href="index.php">GABIMARU</a></li>
                    </ul>
                    <a href="index.php"><img src="assets/picture/style.png" alt="Gabimaru Logo"></a>
                </div>
            </div>
            <div class="center">
                <ul>
                    <li><a href="clothing.php">CLOTHING & OTHER</a></li>
                </ul>
            </div>
            <div class="right">
                <div class="social-icons">
                    <a href="#"><img src="assets/picture/telega.png" alt="telegram"></a>
                    <a href="#"><img src="assets/picture/vk.png" alt="vk"></a>
                </div>
                <div class="cart">
                    <a href="cart.php"><img src="assets/picture/korzina.png" alt="Cart"></a>
                </div>
            </div>
        </nav>
    </header>

    <?php
    // Вывод корзины
    if ($resultCart->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>Product</th><th>Price</th><th>Size</th><th>Quantity</th></tr>';

        $totalPrice = 0;

        while ($rowCart = $resultCart->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($rowCart['product_name']) . '</td>';
            echo '<td>' . $rowCart['price'] . ' р.</td>';
            echo '<td>' . $rowCart['size_name'] . '</td>';
            echo '<td>' . $rowCart['quantity'] . '</td>';
            echo '</tr>';

            $totalPrice += $rowCart['price'] * $rowCart['quantity'];
        }

        echo '</table>';

        echo '<div class="Price">Total Price: ' . $totalPrice . ' р.</div>';
    } else {
        echo '<div>Your cart is empty</div>';
    }

    // Закрываем соединение
    $conn->close();
    ?>

    <!-- Форма для оформления заказа -->
    <form method="post" action="process_order.php">
        <label for="email">Email:</label>
        <input type="text" name="email" required>

        <label for="vk_link">VK Link:</label>
        <input type="text" name="vk_link" required>

        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" required>

        <label for="address">Address:</label>
        <input type="text" name="address" required>

        <label for="postal_code">Postal Code:</label>
        <input type="text" name="postal_code" required>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" required>

        <button type="submit" name="submit_order">Submit Order</button>
    </form>
</body>

</html>