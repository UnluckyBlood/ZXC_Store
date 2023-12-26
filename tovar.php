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
session_start();
include('connection.php');

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $sqlProduct = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $sqlProduct->bind_param("i", $productId);
    $sqlProduct->execute();
    $resultProduct = $sqlProduct->get_result();

    if ($resultProduct->num_rows > 0) {
        $rowProduct = $resultProduct->fetch_assoc();

        if (isset($_POST['add_to_cart'])) {
            $productId = $_POST['product_id'];
            $sizeId = $_POST['size_id'];

            $sqlCheck = "SELECT * FROM cart WHERE id_product = ? AND id_size = ?";
            $resultCheck = $conn->prepare($sqlCheck);
            $resultCheck->bind_param("ii", $productId, $sizeId);
            $resultCheck->execute();

            if ($resultCheck->get_result()->num_rows > 0) {
                $sqlUpdate = "UPDATE cart SET quantity = quantity + 1 WHERE id_product = ? AND id_size = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ii", $productId, $sizeId);
                $stmtUpdate->execute();
            } else {
                $sqlInsert = "INSERT INTO cart (id_product, id_size, quantity) VALUES (?, ?, 1)";
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bind_param("ii", $productId, $sizeId);
                $stmtInsert->execute();
            }

            // Устанавливаем выбранный размер в текущий
            $_SESSION['selected_size'] = $sizeId;
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
        echo '<div class="cable-choose" id="sizeButtons">';

        $sqlSizes = "SELECT sizes.id_size, sizes.size_name FROM product_sizes
                    JOIN sizes ON product_sizes.id_size = sizes.id_size
                    WHERE product_sizes.id_product = ?";
        $stmtSizes = $conn->prepare($sqlSizes);
        $stmtSizes->bind_param("i", $productId);
        $stmtSizes->execute();
        $resultSizes = $stmtSizes->get_result();

        if ($resultSizes->num_rows > 0) {
            while ($rowSize = $resultSizes->fetch_assoc()) {
                $isSelected = ($rowSize["id_size"] == $_SESSION['selected_size']) ? 'activeBut' : '';

                echo '<form method="post">';
                echo '<input type="hidden" name="product_id" value="' . $productId . '">';
                echo '<input type="hidden" class="' . $isSelected . '" name="size_id" value="' . $rowSize["id_size"] . '">';
                echo '<button type="submit" class="' . $isSelected . '" name="add_to_cart" value="1">' . $rowSize["size_name"] . '</button>';
                echo '</form>';
            }
        } else {
            echo '<div>No sizes available</div>';
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="product-price">';
        echo '<span>' . $rowProduct["price"] . ' р.</span>';
        echo '<button id="buyBtn" class="cart-btn">Купить</button>';
        echo '</div>';
        echo '</div>';
        echo '</main>';
    } else {
        echo '<div>Product not found</div>';
    }
} else {
    echo '<div>No product ID specified</div>';
}

$conn->close();
?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    $("#sizeButtons button").click(function() {
        $("#sizeButtons button").removeClass("activeBut");
        $(this).addClass("activeBut");
    });
});
</script>

</body>

</html>