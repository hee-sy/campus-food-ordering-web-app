<?php
$order_id = '';
$isDelivery = '';
$readyAt = '';
$address = '';
$stall_id = '';
$date = '';
$time = '';
$total = '';
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Tracking Details</title>
</head>

<body class="md:bg-gray-200 nunito-sans">
    <?php
    include 'header.php';
    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $order_id = $_GET['id'];
        $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
        $query = "SELECT * FROM `order` WHERE order_id='$order_id'";
        $order = ($conn->query($query))->fetch_assoc();
        $isDelivery = $order['isDelivery'];
        $readyAt = substr($order['readyAt'], 0, -3);
        $address = $order['address'];
        $stall_id = $order['stall_id'];
        $date = $order['date'];
        $time = $order['time'];
        $total = number_format($order['total'], 2, '.', '');
    ?>
        <h1 class="text-xl my-5 mx-10 md:mx-0 text-center">ORDER ID: <?php echo $order_id; ?></h1>
        <div class="flex justify-center mb-5">
            <?php echo $isDelivery == 1
                ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-24">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z" />
                    <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z" />
                    <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                    </svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-24">
                    <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 0 0 7.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 0 0 4.902-5.652l-1.3-1.299a1.875 1.875 0 0 0-1.325-.549H5.223Z" />
                    <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 0 0 9.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 0 0 2.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3Zm3-6a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75v-3Zm8.25-.75a.75.75 0 0 0-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-5.25a.75.75 0 0 0-.75-.75h-3Z" clip-rule="evenodd" />
                    </svg>';
            ?>
        </div>
        <p class="text-center">Your order will be ready around</p>
        <h1 class="text-center text-4xl font-bold text-orange-500 my-4"><?php echo $readyAt; ?></h1>
        <h1 class="text-center text-md">
            <?php echo $isDelivery == 1 ? "<p>deliver to &nbsp" : "<p>collect at &nbsp"; ?>
            <span class="text-gray-400 md:text-black md:font-bold md:underline"><?php echo $address; ?></span></p>
        </h1>


        <div class="bg-white px-10 xl:mx-[300px] md:my-10 md:mx-[100px] md:rounded-3xl flex flex-col py-10 md:pt-10 justify-center">
            <?php
            $query2 = "SELECT * FROM `orderitem` WHERE order_id='$order_id'";
            $orderItems = $conn->query($query2);
            $query3 = "SELECT * FROM `stall` WHERE stall_id='$stall_id'";
            $stall = $conn->query($query3)->fetch_assoc();
            ?>
            <div class="flex flex-row items-center mb-5">
                <div class='bg-black rounded-full overflow-clip size-16 mr-2'>
                    <img src='<?php echo $stall['logoPath']; ?>' alt='<?php echo $stall['name']; ?>' class='size-full object-cover'>
                </div>
                <div class='w-4/6 flex flex-col mx-2 justify-start'>
                    <p class='text-sm font-semibold'><?php echo $stall['name']; ?></p>
                    <div class='flex flex-row text-sm font-semibold text-gray-400'>
                        <p class='mr-2'><?php echo $date; ?></p>
                        <p><?php echo $time; ?></p>
                    </div>
                </div>
            </div>
            <?php
            $index = 0;
            while ($orderItem = $orderItems->fetch_assoc()) {
                $food = $conn->query("SELECT * FROM food WHERE food_id='$orderItem[food_id]'")->fetch_assoc();
                $food_name = $food['name'];
                $food_price = number_format($food['price'], 2, '.', '');
                $quantity = $orderItem['quantity'];
                echo "
                <div class='flex flex-row justify-between font-normal'>
                    <p>$quantity x $food_name</p>
                    <p>RM $food_price</p>
                </div>
                ";
                $index++;
            }
            ?>
            <div class='flex flex-row justify-between font-bold'>
                <p>Total Price</p>
                <p>RM <?php echo $total; ?></p>
            </div>


        </div>



    <?php
    }
    ?>
    <div class="my-24"></div>
    <?php
    include 'bottomnavy.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>

</html>