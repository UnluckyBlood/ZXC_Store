<?php
include('connection.php');

// Проверяем, была ли отправлена форма оформления заказа
if (isset($_POST['submit_order'])) {
    // Получаем данные пользователя
    $email = $_POST['email'];
    $vk = $_POST['vk_link']; // Исправлено
    $name = $_POST['fullname']; // Исправлено
    $address = $_POST['address'];
    $postalCode = $_POST['postal_code'];
    $phone = $_POST['phone_number']; // Исправлено

    // Вставляем данные в базу данных
    $sqlInsertOrder = "INSERT INTO orders (email, vk_link, fullname, address, postal_code, phone_number)
                      VALUES ('$email', '$vk', '$name', '$address', '$postalCode', '$phone')";
    $conn->query($sqlInsertOrder);

    // Очищаем корзину пользователя
    $sqlClearCart = "DELETE FROM cart";
    $conn->query($sqlClearCart);

    echo 'Order submitted successfully!';
} else {
    echo 'Error submitting order';
}

$conn->close();
?>
