<?php
session_start();
foreach ($_SESSION['cart'] as $key => $value) {
    foreach ($_POST as $i => $item) {
        if (in_array($value['name'],$item)) {
            unset($_SESSION['cart'][$key]);
        }
    }
}

$items = $_SESSION["cart"];
$cost = 0;
foreach($items as $data) {
    $cost += $data['price'] * $data['quantity'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./index.css">
    <title>Cart | Badger Bytes</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>

<!-- Navigation -->
<?php
    if ($_SESSION['accounttype'] == "admin") {
        include "../navbar/navbar-admin.php";
    } else if ($_SESSION['accounttype'] == "staff") {
        include "../navbar/navbar-restaurant_staff.php";
    } else {
        include "../navbar/navbar-customer.php";
    }
?>
<body class="bg-light">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
        </script>
    <div class="hero w-100 text-white bg-dark d-flex flex-column mb-3 hero-size"
        style="background: url('cart.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <h1>
                Cart
            </h1>
        </div>
    </div>
    <div class="container">
        <div class="wrapper table-responsive" style="margin-bottom: 10rem;">
            <table class="table bg-white">
                <?php if (count($items) > 0) { ?>
                <section class="pt-2 pb-2">
                    <div class="container">
                        <div class="row w-100">
                            <div class="col-lg-12 col-md-12 col-12">
                                <table id="shoppingCart" class="table table-condensed table-responsive bg-white border">
                                    <thead>
                                        <tr>
                                            <th style="width:60%">Product</th>
                                            <th style="width:12%">Price</th>
                                            <th style="width:10%">Quantity</th>
                                            <th style="width:16%"></th>
                                        </tr>
                                    </thead>
                                    <?php
                                        foreach($items as $data) { ?>
                                    <tbody>
                                        <tr id="<?php echo $data['name']?>-row">
                                            <?php $itemName = $data['name']; ?>
                                            <td data-th="Product">
                                                <div class="row">
                                                    <div class="col text-left mt-sm-2">
                                                        <h5>
                                                            <?php echo $data['name'] ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-th="Price">
                                                <?php echo $data['price'] ?>
                                            </td>
                                            <td data-th="Quantity">
                                                <input type="number" min="1"
                                                    class="form-control form-control-lg text-center"
                                                    value="<?php echo $data['quantity'] ?>" disabled>
                                            </td>
                                            <td class="actions" data-th="">
                                                <div class="text-right">
                                                    <button id="del-btn" name="<?php echo $data['name']?>"
                                                        price="<?php echo $data['price']?>"
                                                        quantity="<?php echo $data['quantity']?>"
                                                        class="btn btn-white border-secondary bg-white btn-md mb-2"
                                                        style='margin: 0.3rem 0 0 1.5rem;'>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php } ?>
                                </table>
                                <div style="text-align: end">
                                    <h4 style="margin-right: 2rem" id="total" price="<?php echo $cost?>">Subtotal: $
                                        <?php echo $cost?>
                                    </h4>
                                </div>
                            </div>
                            <?php
                                if (count($items) > 0) { 
                                ?>
                            <div class="row">
                                <div class="col mb-3">
                                    <a href="../checkout" type="submit" name="submit" class="btn btn-dark w-100 btn-lg">Checkout</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </section>
                <?php
                } else { ?>
                <div class="alert alert-danger" role="alert">
                    <div><strong>Your cart is empty.</strong></div>
                    <small>
                        Please go to the <a href="../menu">menu</a> to add an item to your cart.
                    </small>
                </div>
                <?php    
                }
                ?>

            </table>
        </div>
    </div>
</body>
<script>
    let total = parseFloat($("#total").attr("price"))
    let items = {}
    let count = 0
    $("button#del-btn").click(function () {
        let name = $(this).attr("name")
        let price = parseFloat($(this).attr("price"))
        let quantity = parseInt($(this).attr("quantity"))
        items[count] = {
            "name": name,
            "price": price,
            "quantity": quantity
        }
        total -= price
        $("#total").html(`Subtotal: $${total.toFixed(2)}`)
        $(`#${name}-row`).remove()

        $.ajax({
            type: "POST",
            url: "index.php",
            data: items,
            success: function () {
                count++
            }
        })
    })
</script>

</html>