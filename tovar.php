<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="assets/css/tovar.css" rel="stylesheet">
    <title>Gabimaru</title>
</head>

<body>

<?php
include('connection.php');

// Проверяем, передан ли параметр id в URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Получаем информацию о товаре по ID из базы данных
    $sqlProduct = "SELECT * FROM products WHERE id = $productId";
    $resultProduct = $conn->query($sqlProduct);

    // Проверяем, есть ли результат
    if ($resultProduct->num_rows > 0) {
        $rowProduct = $resultProduct->fetch_assoc();

        // Добавляем товар в корзину
        if (isset($_POST['add_to_cart'])) {
            $productId = $_POST['product_id'];
            $sizeId = $_POST['size_id'];

            // Проверяем, есть ли уже такой товар в корзине
            $sqlCheck = "SELECT * FROM cart WHERE id_product = $productId AND id_size = $sizeId";
            $resultCheck = $conn->query($sqlCheck);

            if ($resultCheck->num_rows > 0) {
                // Если товар уже есть в корзине, увеличиваем количество
                $sqlUpdate = "UPDATE cart SET quantity = quantity + 1 WHERE id_product = $productId AND id_size = $sizeId";
                $conn->query($sqlUpdate);
            } else {
                // Если товара нет в корзине, добавляем его
                $sqlInsert = "INSERT INTO cart (id_product, id_size, quantity) VALUES ($productId, $sizeId, 1)";
                $conn->query($sqlInsert);
            }
        }

        // Отображаем информацию о товаре
        echo '<header>';
        echo '<nav class="navbar">';
        echo '<div class="left">';
        echo '<div class="brand">';
        echo '<ul>';
        echo '<li><a href="index.php">GABIMARU</a></li>';
        echo '</ul>';
        echo '<a href="index.php"><img src="assets/picture/style.png" alt="Gabimaru Logo"></a>';
        echo '</div>';
        echo '</div>';
        echo '<div class="center">';
        echo '<ul>';
        echo '<li><a href="clothing.php">CLOTHING & OTHER</a></li>';
        echo '</ul>';
        echo '</div>';
        echo '<div class="right">';
        echo '<div class="social-icons">';
        echo '<a href="#"><img src="assets/picture/telega.png" alt="telegram"></a>';
        echo '<a href="#"><img src="assets/picture/vk.png" alt="vk"></a>';
        echo '</div>';
        echo '<div class="cart">';
        echo '<a href="cart.php"><img src="assets/picture/korzina.png" alt="Cart"></a>';
        echo '</div>';
        echo '</div>';
        echo '</nav>';
        echo '</header>';

        echo '<main class="container">';
        echo '<div class="left-column">';
        // Отображаем изображения товара
        echo '<img data-image="white" class="active" src="' . $rowProduct["front_image"] . '" alt="">';
        echo '<img data-image="black" src="' . $rowProduct["back_image"] . '" alt="">';
        echo '</div>';

        echo '<div class="right-column">';
        echo '<div class="product-description">';
        echo '<span><div id="txt"></div></span>';
        echo '<h1>' . $rowProduct["name"] . '</h1>';
        echo '<p>' . $rowProduct["description"] . '</p>';
        echo '</div>';

        echo '<div class="product-configuration">';
        echo '<div class="cable-config">';
        echo '<span>Выберите размер:</span>';
        echo '<div class="cable-choose">';

        // Получаем размеры для данного товара
        $sqlSizes = "SELECT sizes.id_size, sizes.size_name FROM product_sizes
                     JOIN sizes ON product_sizes.id_size = sizes.id_size
                     WHERE product_sizes.id_product = $productId";
        $resultSizes = $conn->query($sqlSizes);

        // Проверяем, есть ли размеры
        if ($resultSizes->num_rows > 0) {
            while ($rowSize = $resultSizes->fetch_assoc()) {
                echo '<form method="post">';
                echo '<input type="hidden" name="product_id" value="' . $productId . '">';
                echo '<input type="hidden" class="activeBut" name="size_id" value="' . $rowSize["id_size"] . '">';
                echo '<button type="submit" name="add_to_cart">Size ' . $rowSize["size_name"] . '</button>';
                echo '</form>';
            }
        } else {
            // Если размеры не найдены
            echo '<div>No sizes available</div>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="product-price">';
        echo '<span>' . $rowProduct["price"] . ' р.</span>';
        echo '<a href="#" class="cart-btn">Купить</a>';
        echo '</div>';
        echo '</div>';
        echo '</main>';
    } else {
        // Если товар с указанным ID не найден
        echo '<div>Product not found</div>';
    }
} else {
    // Если не передан параметр id
    echo '<div>No product ID specified</div>';
}

$conn->close();
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
<script src="assets/js/tovar.js" charset="utf-8"></script>
</body>

</html>
