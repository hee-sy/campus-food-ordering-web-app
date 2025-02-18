<?php
$conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");

if (!empty($_POST['items'])) {
    foreach ($_POST['items'] as $item) {
        $foodId = $item['foodId'];
        $quantity = $item['quantity'];

        // Update the database
        $query = "UPDATE orderitem SET quantity = '$quantity' WHERE food_id='$foodId'";
        $conn->query($query);
    }
}