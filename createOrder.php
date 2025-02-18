<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['total-quantity'] <= 0) {
        echo "<script>alert('Please add items to your cart before checking out.')</script>";
        echo "<script>window.location.href='orderscreen.php'</script>";
    } else {
        $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $totalquantity = $_POST['total-quantity'];
        $isDelivery = isset($_POST['isDelivery']);
        $total = $_POST['total'];
        $stallId = "";
        $address = "";

        //get $stallId
        $foodId = $_POST['food_id'][0];
        $result1 = $conn->query("SELECT stall_id FROM food WHERE food_id='$foodId'");
        $stallId = ($result1->fetch_assoc())['stall_id'];

        //get $address
        if ($isDelivery) { //for delivery
            $address = $_POST['address'];
        } else { //for pickup
            $result2 = ($conn->query("SELECT * FROM stall WHERE stall_id='$stallId'"));
            $address = ($result2->fetch_assoc())['address'];
        }

        //get $readyAt
        $foodIds = $_POST['food_id']; // Array of food ids in the cart
        $quantities = $_POST['quantity']; // Array of quantities of each food in the cart
        $totalPrepTime = 0;
        print_r($foodIds);
        print_r($quantities);
        for ($i = 0; $i < count($foodIds); $i++) {
            $foodId = $foodIds[$i];
            $quantity = $quantities[$i];
            $query = "SELECT prepTime FROM food WHERE food_id='$foodId'";
            $prepTime = ($conn->query($query)->fetch_assoc())['prepTime'];
            $totalPrepTime += $prepTime * $quantity;
        }
        //"+$totalPrepTime minutes" is used to add the total preparation time to the current time 
        $readyAt = date('H:i:s', strtotime("+$totalPrepTime minutes"));

        //insert into order table
        $query = "INSERT INTO `order` (date, time, quantity, total, address, readyAt, isDelivery, stall_id) 
        VALUES ('$date', '$time', '$totalquantity', '$total', '$address', '$readyAt', '$isDelivery', '$stallId')";
        $result = $conn->query($query);

        //get order_id
        $orderId = $conn->insert_id; //insert_id is used to get the auto-generated order_id from the previous INSERT query
        //update orderitems's order_id column
        $query = "UPDATE orderitem SET order_id='$orderId' WHERE order_id=0";
        $result = $conn->query($query);

        //clear variables
        $date = '';
        $time = '';
        $totalquantity = '';
        $isDelivery = '';
        $address = '';
        $total = '';
        $readyAt = '';
        $stallId = '';

        //notify user
        echo "<script>
        alert('Order placed successfully!');
        window.location.href='orderscreen.php';
        </script> ";
    }
}
