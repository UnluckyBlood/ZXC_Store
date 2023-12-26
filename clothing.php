<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/manyshop.css">
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
    include('connection.php');

    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo '<div class="nft">';
        echo '<div class="main">';
        echo '<div class="product">';
        echo '<a href="tovar.php?id=' . $row["id"] . '" class="tokenImage" onmouseover="changeImage(this)" onmouseout="restoreImage(this)">';
        echo '<img class="tokenImage front" src="' . $row["front_image"] . '" alt="NFT" />';
        echo '<img class="tokenImage back" src="' . $row["back_image"] . '" alt="NFT" style="display: none;" />';
        echo '</a>';
        echo '<a href="tovar.php?id=' . $row["id"] . '" class="stepInTovar"><h2>' . $row["name"] . '</h2></a>';
        echo '<p class="description">' . $row["description"] . '</p>';
        echo '<div class="tokenInfo">';
        echo '<div class="price">';
        echo '<p>' . $row["price"] . ' р.</p>';
        echo '</div>';
        echo '<div class="duration">';
        echo '<ins>◷</ins>';
        echo '<p><b>Limited edition</b></p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    $conn->close();
    ?>

    <script>
        function changeImage(element) {
            element.querySelector('.front').style.display = 'none';
            element.querySelector('.back').style.display = 'block';
        }

        function restoreImage(element) {
            element.querySelector('.front').style.display = 'block';
            element.querySelector('.back').style.display = 'none';
        }
    </script>

</body>

</html>
