<?php
    if(isset($_GET['id'])){
        $id = $_GET['id']; 
        
        $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");

        $query = "DELETE FROM orderitem WHERE food_id = '$id'";
        $conn->query($query);

        header("location: orderscreen.php");
        exit;
     }
?>