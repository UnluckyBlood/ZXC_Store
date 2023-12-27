<?php
include('connection.php');

// Проверяем, была ли отправлена форма оформления заказа
if (isset($_POST['submit_order'])) {
    // Получаем данные пользователя
    $email = $_POST['email'];
    $vk = $_POST['vk_link'];
    $name = $_POST['full_name'];
    $address = $_POST['address'];
    $postalCode = $_POST['postal_code'];
    $phone = $_POST['phone_number'];

    // Вставляем данные пользователя в базу данных
    $sqlInsertOrder = "INSERT INTO orders (email, vk_link, fullname, address, postal_code, phone)
    VALUES ('$email', '$vk', '$name', '$address', '$postalCode', '$phone')";
    $conn->query($sqlInsertOrder);

    // Получаем идентификатор только что вставленного заказа
    $orderId = $conn->insert_id;

    // Получаем содержимое корзины пользователя
    $sqlCart = "SELECT 
                    cart.id_product,
                    cart.id_size,
                    sizes.size_name,
                    cart.quantity,
                    products.price
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

    // Вставляем каждый товар из корзины в таблицу order_items
    while ($rowCart = $resultCart->fetch_assoc()) {
        $productId = $rowCart['id_product'];
        $sizeId = $rowCart['id_size'];
        $sizeName = $rowCart['size_name'];
        $quantity = $rowCart['quantity'];
        $price = $rowCart['price'];

        $sqlInsertOrderItem = "INSERT INTO order_items (order_id, product_id, size_id, size_name, quantity, price)
        VALUES ('$orderId', '$productId', '$sizeId', '$sizeName', '$quantity', '$price')";
        $conn->query($sqlInsertOrderItem);
    }

    // Очищаем корзину пользователя
    $sqlClearCart = "DELETE FROM cart";
    $conn->query($sqlClearCart);

    echo 'Заказ успешно оформлен!';
    echo '<br>';
    echo 'Номер заказа: ' . $orderId;
    echo '<br>';
    echo '<a href="clothing.php">Продолжить покупки...</a>';
} else {
    echo 'Ошибка оформления заказа';
}

$conn->close();
?>
