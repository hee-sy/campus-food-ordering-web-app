<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet">
    <title>Order</title>
</head>

<body class="md:bg-gray-200 nunito-sans">
    <?php
    include 'header.php';
    ?>

    <form action="/MApp_FinalAsg/createOrder.php" method="post">
        <div class="bg-white xl:mx-[300px] md:my-10 md:rounded-3xl flex flex-col px-2 py-10 md:p-10 justify-center">
            <h1 class="text-xl mx-10 md:mx-0">You have <span id="total-quantity">0</span> items.</h1>
            <div class="flex items-center my-4 mx-10 md:mx-0">
                <input id="default-checkbox" onchange="toggleAddressInput()" type="checkbox" name="isDelivery" value="1" class="w-4 h-4 my-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 my-4">Delivery</label>
                <input type="hidden" name="address" placeholder="Enter your address" class="mx-4 text-sm border-none focus:ring-orange-500 w-full h-7">
            </div>

            <?php
            $food_imagePath = '';
            $food_name = '';
            $food_price = '';
            $quantity = '';
            $conn = mysqli_connect("localhost", "root", "", "eatxiaswe2109768", "4306");
            $orderItemsInCart = $conn->query("SELECT * FROM orderitem WHERE order_id=0");
            $index = 0;
            while ($orderItem = $orderItemsInCart->fetch_assoc()) {
                $food = $conn->query("SELECT * FROM food WHERE food_id='$orderItem[food_id]'")->fetch_assoc();
                $food_imagePath = $food['imagePath'];
                $food_name = $food['name'];
                $food_price = number_format($food['price'], 2, '.', '');
                $quantity = $orderItem['quantity'];
                echo "
                <div class='flex flex-row items-center my-2'>
                    <input type='hidden' name='food_id[]' value='$food[food_id]'>
                    <div class='w-4/6 flex flex-row items-center'>
                        <img class='rounded-3xl size-20 object-cover' src='$food_imagePath' alt='$food_name'>
                        <div class='flex flex-col px-4'>
                            <div>$food_name</div>
                            <div>RM $food_price</div>
                        </div>
                    </div>
                    <div class='w-1/6 flex flex-row justify-center'>
                        <div class='flex flex-row items-center justify-between my-2.5'>
                            <div class='flex flex-col justify-center md:flex-row'>
                                <button id='d_$food[food_id]' type='button' onclick='decrement(this)' class='flex justify-center'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' strokeWidth={1.5} stroke='currentColor' class='size-6 hover:text-orange-500'>
                                        <path strokeLinecap='round' strokeLinejoin='round' d='M5 12h14' />
                                    </svg>
                                </button>
                                <input id='$food[food_id]' name='quantity[]' price='$food_price' value='$quantity' onchange='validate(this)' class='w-6 mx-2 text-center bg-transparent'></input>
                                <button id='i_$food[food_id]' type='button' onclick='increment(this)' class='flex justify-center'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' strokeWidth={1.5} stroke='currentColor' class='size-6 hover:text-orange-500'>
                                        <path strokeLinecap='round' strokeLinejoin='round' d='M12 4.5v15m7.5-7.5h-15' />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <a href='deleteOrderItem.php?id=$food[food_id]' class='w-1/6 text-center text-red-500 hover:text-red-700 font-medium text-sm'>remove</a>
                </div>
                ";
                $index++;
            }
            ?>
            <input type="hidden" name="total-quantity" value="0">
            <input type="hidden" name="total" value="">
            <button id="ProceedToPay" type="submit" class="mt-10 text-white  bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-semibold rounded-3xl text-sm px-5 py-2.5 text-center">
                Proceed to pay (RM 0)
            </button>
        </div>
    </form>
    <div class="my-24"></div>
    <?php
    include 'bottomnavy.php';
    ?>

    <script>
        let quantities = document.getElementsByName('quantity[]');
        let total = 0;
        let totalQuantity = 0;
        for (let i = 0; i < quantities.length; i++) {
            let quantity = quantities[i].value;
            let price = quantities[i].getAttribute('price');
            total += quantity * price;
            totalQuantity += parseInt(quantity);
        }
        //get the hidden input tag with name 'total' and set its value to the total price
        document.querySelector('span[id="total-quantity"]').innerText = `${totalQuantity}`;
        document.querySelector('input[name="total-quantity"]').value = totalQuantity;
        document.querySelector('input[name="total"]').value = total.toFixed(2);
        document.getElementById('ProceedToPay').innerText = `Proceed to pay (RM ${total.toFixed(2)})`;

        const increment = (elmt) => {
            let food_id = elmt.getAttribute('id').substring(2); // .substring returns the string after the first 2 characters
            let quantity = document.getElementById(food_id).value;
            quantity++;
            document.getElementById(food_id).value = quantity;
            updatePrice();
        };

        const decrement = (elmt) => {
            let food_id = elmt.getAttribute('id').substring(2); // .substring returns the string after the first 2 characters
            let quantity = document.getElementById(food_id).value;
            if (quantity > 1) {
                quantity--;
                document.getElementById(food_id).value = quantity;
                updatePrice();
            }
        };

        const updatePrice = () => {
            let quantities = document.getElementsByName('quantity[]');
            let total = 0;
            let totalQuantity = 0;
            for (let i = 0; i < quantities.length; i++) {
                let quantity = quantities[i].value;
                let price = quantities[i].getAttribute('price');
                total += quantity * price;
                totalQuantity += parseInt(quantity);
            }
            document.querySelector('span[id="total-quantity"]').innerText = `${totalQuantity}`;
            document.querySelector('input[name="total-quantity"]').value = totalQuantity;
            document.querySelector('input[name="total"]').value = total.toFixed(2);
            document.getElementById('ProceedToPay').innerText = `Proceed to pay (RM ${total.toFixed(2)})`;
        };

        const validate = (elmt) => {
            let quantity = elmt.value;
            if (quantity < 1 || isNaN(quantity)) {
                elmt.value = 1;
            }
            updatePrice();
        };

        const toggleAddressInput = () => {
            let addressInput = document.querySelector('input[name="address"]');
            addressInput.getAttribute('type') === 'hidden' ? addressInput.setAttribute('type', 'text') : addressInput.setAttribute('type', 'hidden');

        };


        window.addEventListener('beforeunload', (event) => {
            // Prevent the event from firing multiple times
            event.preventDefault();

            // Create a new FormData object
            let formData = new FormData();

            // Collect data and store in formData
            document.querySelectorAll('input[name="quantity[]"]').forEach((inputElement, index) => {
                let foodId = inputElement.id;
                let quantity = inputElement.value;
                formData.append(`items[${index}][foodId]`, foodId);
                formData.append(`items[${index}][quantity]`, quantity);
            });

            // Send data to PHP script
            navigator.sendBeacon('updateOrderItems.php', formData); // send a POST request to updateOrderItems.php
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>

</body>

</html>