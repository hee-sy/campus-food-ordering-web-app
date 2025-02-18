<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Food</title>
</head>

<body class="md:bg-gray-200 nunito-sans">
    <?php
    include 'header.php';
    ?>

    <?php
    $food_id = '';
    $imagePath = '';
    $name = '';
    $rating = '';
    $category = '';
    $prepTime = '';
    $stall_name = '';
    $stall_id = '';
    $description = '';
    $price = 0.0;

    $conn =  mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $food_id = $_GET['id'];
        $food = $conn->query("SELECT * FROM food WHERE food_id='$food_id'")->fetch_assoc();
        $imagePath = $food['imagePath'];
        $name = $food['name'];
        $rating = number_format($food['rating'], 1, '.', '');
        $category = $food['category'];
        $prepTime = strval($food['prepTime']);
        $description = $food['description'];
        $price = $food['price'];


        $stall = $conn->query("SELECT * FROM stall WHERE stall_id = '$food[stall_id]'")->fetch_assoc();
        $stall_name = $stall['name'];
        $stall_id = $stall['stall_id'];
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $orderItemsInCart = $conn->query("SELECT * FROM orderitem WHERE order_id=0");
        $food_id = $_POST['food_id'];
        if ($orderItemsInCart->num_rows == 0) { // If cart is empty
            $quantity = $_POST['quantity'];

            $query = "INSERT INTO orderitem (food_id, quantity, order_id) VALUES ('$food_id', '$quantity', 0)";
            $conn->query($query);

            $food = $conn->query("SELECT * FROM food WHERE food_id='$food_id'")->fetch_assoc();

            echo "<script>alert('Added $quantity $food[name] to cart'); window.location='homescreen.php';</script>";
            exit; // Stop further PHP execution
        } else { // If cart is not empty
            $food_id_in_cart = ($orderItemsInCart->fetch_assoc())['food_id'];
            $stall_id_in_cart = (($conn->query("SELECT stall_id FROM food WHERE food_id='$food_id_in_cart'"))->fetch_assoc())['stall_id'];
            $stall_id = (($conn->query("SELECT stall_id FROM food WHERE food_id='$food_id'"))->fetch_assoc())['stall_id'];
            if ($stall_id != $stall_id_in_cart) {
                echo "<script>
                alert('You can only order from one stall at a time. Please checkout your current cart before ordering from another stall.'); window.location='homescreen.php';
                </script>";
                exit; // Stop further PHP execution
            } else {
                $quantity = $_POST['quantity'];
                $query = "SELECT * FROM orderitem WHERE food_id='$food_id' AND order_id=0";
                $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    $newquantity = ($result->fetch_assoc())['quantity'] + $quantity;
                    $query = "UPDATE orderitem SET quantity = $newquantity WHERE food_id='$food_id' AND order_id=0";
                    $conn->query($query);
                } else {
                    $query = "INSERT INTO orderitem (food_id, quantity, order_id) VALUES ('$food_id', '$quantity', 0)";
                    $conn->query($query);
                }

                $food = ($conn->query("SELECT * FROM food WHERE food_id='$food_id'"))->fetch_assoc();

                echo "<script>alert('Added $quantity $food[name] to cart'); window.location='homescreen.php';</script>";
                exit; // Stop further PHP execution
            }
        }
    }
    ?>

    <form method="post">
        <div class="bg-white overflow-hidden md:mx-32 md:my-10 lg:mx-52 xl:mx-[300px] grid xl:grid-cols-2 gap-0 md:rounded-3xl">
            <img src="<?php echo $imagePath; ?>" alt="<?php echo $food['name']; ?>" class="justify-self-start w-full xl:size-96 object-cover" />
            <div class="p-10 flex flex-col justify-between">
                <div>
                    <h1 class="text-xl font-bold mb-2"><?php echo $name; ?></h1>
                    <div class="flex flex-row justify-between my-2">
                        <!-- rating -->
                        <div class='bg-orange-100  px-2.5 py-0.5 rounded flex items-center'>
                            <div class='pr-2 flex items-center space-x-1 rtl:space-x-reverse'>
                                <svg class='w-3 h-3 text-orange-500' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                    <path d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                </svg>
                            </div>
                            <span class='text-orange-800 text-xs font-semibold'><?php echo $rating; ?></span>
                        </div>
                        <h3 class="text-sm"><?php echo $category; ?></h3>
                        <!-- time or clock icon -->
                        <div class="flex flex-row">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="size-5">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="text-sm"><?php echo $prepTime; ?> min</h3>
                        </div>
                    </div>
                    <a href="stallscreen.php?id=<?php echo $stall_id; ?>" class="hover:text-orange-700 my-2 text-sm text-orange-500 font-bold"><?php echo $stall_name; ?></a>
                    <h2 class="my-2 font-bold">Details</h2>
                    <p class="my-2 text-gray-400 text-sm"><?php echo $description; ?></p>
                </div>
                <!-- quantity editor -->
                <div>
                    <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                    <div class="flex flex-row items-center justify-between my-2.5">
                        <div class="flex flex-row">
                            <button type="button" onclick="decrement()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="size-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 12h14" />
                                </svg>
                            </button>
                            <input id="quantity" name="quantity" value="1" class="w-6 mx-2 text-center bg-transparent"></input>
                            <button type="button" onclick="increment()">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="size-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                        <button type="submit" id="AddToCart" price="<?php echo $price; ?>" class="text-white  bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-semibold rounded-3xl text-sm px-5 py-2.5 text-center">
                            Add to cart (RM 0)
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <div class="my-24"></div>
    <?php
    include 'bottomnavy.php';
    ?>


    <script>
        let quantity = document.getElementById('quantity').value;
        let price = document.getElementById('AddToCart').getAttribute('price');
        let total = quantity * price;
        document.getElementById('AddToCart').innerText = `Add to cart (RM ${total.toFixed(2)})`;

        const increment = () => {
            let quantity = document.getElementById('quantity').value;
            quantity++;
            document.getElementById('quantity').value = quantity;
            updatePrice();
        }

        const decrement = () => {
            let quantity = document.getElementById('quantity').value;
            if (quantity > 1) {
                quantity--;
                document.getElementById('quantity').value = quantity;
                updatePrice();
            }
        }

        const updatePrice = () => {
            let quantity = document.getElementById('quantity').value;
            let price = document.getElementById('AddToCart').getAttribute('price');
            let total = quantity * price;
            document.getElementById('AddToCart').innerText = `Add to cart (RM ${total.toFixed(2)})`;
        }

        document.getElementById('quantity').addEventListener('change', (e) => {
            if (e.target.value < 1 || isNaN(e.target.value)) {
                e.target.value = 1;
            }
            updatePrice();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>

</html>