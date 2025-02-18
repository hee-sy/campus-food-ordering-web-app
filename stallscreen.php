<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Stall</title>
</head>

<body class="flex flex-col max-w-full overflow-x-hidden md:bg-gray-200 static nunito-sans">
    <?php
    include 'header.php';
    ?>

    <?php
    $conn =  mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $stall_id = $_GET['id'];
        $stall = $conn->query("SELECT * FROM stall WHERE stall_id='$stall_id'")->fetch_assoc();
        $logoPath = $stall['logoPath'];
        $imagePath = $stall['imagePath'];
        $videoPath = $stall['videoPath'];
        $name = $stall['name'];
        $rating = number_format($stall['rating'], 1, '.', '');
        $about = $stall['about'];
        $openingHours = strval($stall['openingHours']);
        $address = $stall['address'];

        $foods = $conn->query("SELECT * FROM food WHERE stall_id = '$stall_id'");
    }
    ?>
    <img class="absolute z-0 top-0 w-full h-[200px] md:h-[760px] object-cover" src="<?php echo $imagePath; ?>" alt="<?php echo $name; ?>">

    <img class="z-30 absolute top-20 md:top-60 rounded-full size-44 self-center object-cover shadow-lg" src="<?php echo $logoPath; ?>" alt="<?php echo $name; ?>">
    <div class="z-10 pt-20 relative top-44 bg-white xl:mx-[300px] md:pb-20 md:pt-28 md:mb-20 md:mt-36 md:rounded-full flex flex-col md:p-10 items-center justify-center">
        <div class="md:px-48 w-full">
            <h1 class="text-3xl font-bold text-center my-10"><?php echo $name; ?></h1>
            <div class="flex flex-row justify-evenly">
                <!-- openingHours -->
                <!-- time or clock icon -->
                <div class="flex flex-row items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="fill-orange-500 mr-2.5 size-6">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg"><?php echo $openingHours; ?></h3>
                </div>
                <!-- rating -->
                <div class='rounded flex items-center'>
                    <div class='mr-2.5 flex items-center space-x-1 rtl:space-x-reverse'>
                        <svg class='size-5 text-orange-500' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 22 20'>
                            <path d='M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z' />
                        </svg>
                    </div>
                    <span class='text-orange-800 text-lg font-semibold'><?php echo $rating; ?></span>
                </div>
            </div>
        </div>
        <!-- address -->
        <div class="px-4 flex flex-row items-center my-5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mr-2.5 size-6 fill-orange-500">
                <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
            </svg>
            <h3 class="text-lg"><?php echo $address; ?></h3>
        </div>
        <!-- video -->
        <iframe allowfullscreen width="560" height="315" src="<?php echo $videoPath; ?>&mute=1" class="w-full md:w-[60%] my-10 md:rounded-3xl" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
    <h1 class="mt-44 text-2xl font-bold text-center">Menu</h1>
    <div class="my-10 mb-32 mx-2 lg:mx-10 xl:mx-[300px]">
        <div class="grid grid-cols-2 place-items-center sm:grid-cols-3 md:grid-cols-4 gap-4">
            <?php
            $index = 0;
            while ($food = $foods->fetch_assoc()) {
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
                                        <h4 class='text-xs text-left font-semibold tracking-tight text-gray-400'>$name</h4>
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