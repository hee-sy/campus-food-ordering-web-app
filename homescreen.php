<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Home</title>
</head>

<body class="bg-gray-200 nunito-sans">
    <?php
    include 'header.php';
    ?>
    <div class="mx-2 lg:mx-10 xl:mx-[300px]">

        <div class="grid grid-cols-2 place-items-center sm:grid-cols-3 md:grid-cols-4 gap-4">
            <?php
            $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
            $foods = $conn->query("SELECT * FROM food");
            $stalls = $conn->query("SELECT * FROM stall");

            $index = 0;
            while ($food = $foods->fetch_assoc()) {
                $stall_name = "";
                foreach ($stalls as $stall) {
                    if ($food['stall_id'] == $stall['stall_id']) {
                        $stall_name = $stall['name'];
                    }
                }
                $rating = number_format($food['rating'], 1, '.', '');
                $price = number_format($food['price'], 2, '.', '');
                echo "
    
                <div class='shrink hover:opacity-70 w-full min-w-40 max-w-[220px] bg-white border border-gray-200 rounded-3xl shadow'>
                    <a href='foodscreen.php?id=$food[food_id]' title='foodscreen/$food[name]'>
                        <div class='h-28 overflow-clip'>
                            <img class='object-cover object-center rounded-t-3xl' src='$food[imagePath]' alt='product image' />
                        </div>
                        <div class='px-5 py-5'>
                            <h5 class='text-sm text-left font-semibold tracking-tight text-gray-900'>$food[name]</h5>
                            <div class='flex flex-row justify-between'>
                                <h4 class='text-xs text-left font-semibold tracking-tight text-gray-400'>$stall_name</h4>
                                <h4 class='text-xs font-semibold tracking-tight text-gray-400'>($food[prepTime] min)</h4>
                            </div>
                            <div class='flex flex-row items-center justify-between my-2.5'>
                                <span class='text-md font-bold text-gray-900'>RM $price</span>
                                <div class='bg-orange-100  px-2 py-0.5 rounded flex items-center'>
                                    <div class='pr-2 flex items-center space-x-1 rtl:space-x-reverse'>
                                        <svg class='size-2.5 text-orange-500' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                                            <path d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                                        </svg>
                                    </div>
                                    <span class=' text-orange-800 text-xs font-semibold'>$rating</span>
                                </div>
                                <!-- <a href='#' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'>Add to cart</a> -->
                            </div>
                        </div>
                    </a>
                </div>
                        ";
                $index++;
            }
            ?>
        </div>
    </div>
    <div class="my-24"></div>
    <?php
    include 'bottomnavy.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>



</body>

</html>