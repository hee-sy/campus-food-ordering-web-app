<?php
$order_id = '';
$quantity = '';
$logoPath = '';
$date = '';
$total = '';
$stall_id = '';
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Order Tracking</title>
</head>

<body class="md:bg-gray-200 nunito-sans">
    <?php
    include 'header.php';
    ?>
    <div class="bg-white xl:mx-[300px] md:mx-10 md:my-10 md:rounded-3xl flex flex-col pt-10 md:pt-10 justify-center">
        <h1 class="text-xl mx-10 md:mx-0 text-center font-bold mb-10">Orders</h1>

        <?php
        $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
        $query = "SELECT * FROM `order` WHERE `order_id` <> '0'";
        $orders = $conn->query($query);
        $index = 0;
        while ($order = $orders->fetch_assoc()) {
            $stall = $conn->query("SELECT * FROM stall WHERE stall_id='$order[stall_id]'")->fetch_assoc();
            $logoPath = $stall['logoPath'];
            $quantity = $order['quantity'];
            $order_id = $order['order_id'];
            $date = $order['date'];
            $total = number_format($order['total'], 2, '.', '');
            echo "
                <a href='trackingdetailscreen.php?id=$order_id'>
                    <div class='flex flex-row h-24 items-center py-3 px-4 md:px-20 shadow-md shadow-gray-200 hover:opacity-70'>
                        <div class='bg-black rounded-full overflow-clip size-20 mr-2'>
                            <img src='$logoPath' alt='$stall[name]' class='size-full object-cover'>
                        </div>
                        <div class='w-3/6 flex flex-col mx-2 justify-start'>
                            <p class='text-sm font-semibold'>$quantity Items</p>
                            <p class='text-sm font-semibold text-gray-500'>Order ID: $order_id</p>
                        </div>
                        <div class='w-2/6 flex flex-col mx-2 items-end'>
                            <p class='text-sm font-semibold'>$date</p>
                            <p class='text-sm font-semibold text-green-500'>RM $total</p>
                        </div>
                    </div>
                </a>
                ";
            $index++;
        }
        ?>

    </div>

    <div class="my-24"></div>
    <?php
    include 'bottomnavy.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>

</html>