<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['size_id'])) {
        $productId = $_POST['product_id'];
        $sizeId = $_POST['size_id'];

        // Проверяем, есть ли товар в корзине с выбранным размером
        $sqlCheck = "SELECT * FROM cart WHERE id_product = ? AND id_size = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $productId, $sizeId);
        $stmtCheck->execute();

        if ($stmtCheck->get_result()->num_rows > 0) {
            // Если товар уже есть в корзине, увеличиваем количество
            $sqlUpdate = "UPDATE cart SET quantity = quantity + 1 WHERE id_product = ? AND id_size = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $productId, $sizeId);
            $stmtUpdate->execute();
        } else {
            // Если товара нет в корзине, добавляем его
            $sqlInsert = "INSERT INTO cart (id_product, id_size, quantity) VALUES (?, ?, 1)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("ii", $productId, $sizeId);
            $stmtInsert->execute();
        }

        echo 'Товар добавлен в корзину!';
    } else {
        echo 'Ошибка: Не указан идентификатор товара или размера.';
    }
} else {
    echo 'Ошибка: Недопустимый метод запроса.';
}

$conn->close();
?>
