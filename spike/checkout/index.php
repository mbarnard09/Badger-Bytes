<!DOCTYPE html>
<html>
    
<?php
    session_start();
    $validSubmission1 = 0;
    $validSubmission2 = 0;

    $servername = "127.0.0.1";
    $username = "root";
    $password = "streamable1";
    $dbname = "spike";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        echo "<div class='alert alert-danger mb-0' role='alert'>";
        die("Connection failed: " . mysqli_connect_error());
        echo "</div>";
    }

    // grab order id
    $queryorderid = "SELECT MAX(ID) as maxid from orders";
    $result = mysqli_query($conn, $queryorderid);
    $resultarray = mysqli_fetch_array($result);
    if(is_null($resultarray['maxid'])){
        $orderid = 1;
    }
    else{
        $orderid = $resultarray['maxid'] + 1;
    }
    $price = 0.00;
    $orderitems = "";

    foreach ($_SESSION['cart'] as $item) {
        $price += $item['price'] * $item['quantity'];
        $orderitems = $orderitems . ", " . $item['name'];
    }

    if(isset($_POST['submit'])){

        $address = $_POST['inputAddress'];
        $address2 = $_POST['inputAddress2'];
        $city = $_POST['inputCity'];
        $state = $_POST['inputState'];
        $zipcode = $_POST['inputZip'];
        $paymenttype = $_POST['paymentType'];
        $pickupmonth = $_POST['pickupMonth'];
        $pickupday = $_POST['pickupDay'];
        $pickuphour = $_POST['pickupHour'];
        $pickupminute = $_POST['pickupMinute'];
        $pickupAMPM = $_POST['pickupAMPM'];
        $cardescription = $_POST['carDescription'];

        if($pickupAMPM == "pm"){
            $pickuphour = $pickuphour + 12;
        }

        $date = date_create();
        date_date_set($date, 2021, $pickupmonth, $pickupday);
        date_time_set($date, $pickuphour, $pickupminute);

        date_default_timezone_set('America/Chicago');
        $currentdate = date('Y/m/d h:i:s', time());

        if($currentdate < $date->format('Y/m/d H:i:s')){

            // update saved billing address
            if($_POST['saveInfo']){
                $queryaddressupdate = "UPDATE accounts SET phonenumber='".$phonenumber."', address='".$address."', apartmentnumber='".$address2."', city='".$city."', state='".$state."', zipcode='".$zipcode."' WHERE username='".$_SESSION['username']."'";
                mysqli_query($conn, $queryaddressupdate);
            }

            // adding cart to orderitems and sales report
            foreach ($_SESSION['cart'] as $item) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $querysalesreport = "INSERT INTO salesreport (time, price, item, ID) VALUES ('".$date->format('Y-m-d H:i:s')."', '".$item['price']."', '".$item['name']."', '$orderid')";
                    $validSubmission1 = mysqli_query($conn, $querysalesreport);
                }
            }

            // add orders to order table
            $queryorder = "INSERT INTO orders (priority, status, username, items, cardescription, time, ID, total, phone) VALUES (2, 0, '".$_SESSION['username']."', '".$orderitems."', '".$cardescription."', '".$date->format('Y-m-d H:i:s')."', '".$orderid."', '".$price."', '".$_SESSION['phonenumber']."')";
            $validSubmission2 = mysqli_query($conn, $queryorder);

            // empty cart
            $_SESSION['cart'] = [];
        }
        else{
            echo "<div class='alert alert-danger mb-0' role='alert'>";
            echo "Pick up date must be after '".$currentdate."'";
            echo "</div>";
        }
    }
?>

<?php if (isset($_POST['submit']) && $validSubmission1==1 && $validSubmission2==1) { ?>
    <script type="text/javascript">window.location = "../receipt/index.php?orderid=<?php echo $orderid?>";</script>
<?php } ?>

<head>
    <title>Checkout | Badger Bytes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>

    <style>
        @media (max-width: 768px) {
            .hero-size {
                height: 100px;
            }
        }

        @media (min-width: 768px) {
            .hero-size {
                height: 200px;
            }
        }
    </style>
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
    <div class="hero w-100 text-white bg-dark d-flex flex-column mb-3 hero-size"
        style="background: url('shopping-bag.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <h1>
                Checkout
            </h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge bg-secondary rounded-pill"><?php echo count($_SESSION['cart'])?></span>
                </h4>
                <ul class="list-group mb-3">
                    <?php foreach ($_SESSION['cart'] as $item) {?>
                    <li class="list-group-item d-flex justify-content-between 1h-sm">
                        <div>
                            <h6 class="my-0">
                                <?php
                                    echo $item['name'];
                                ?>
                            </h6>
                            <small class="text-muted">
                                <?php echo " (" . $item['quantity'] . " at $" . $item['price'] . " each)" ?>
                            </small>
                        </div>
                        <span class="text-muted">
                            <?php
                                echo "$" . $item['price'] * $item['quantity'];
                            ?>
                        </span>
                    </li>
                    <?php } ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <span>
                            <?php
                                echo "$".$price;
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-8">
                <form id="billinginformation" name="billing information" method="post" class="needs-validation mb-3" novalidate>
                    <h4>Billing information</h4>
                    <div class="row mt-3">
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                    value="<?php
                                        echo $_SESSION['username'];
                                    ?>" required disabled>
                                <label for="username" class="form-label">Username</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="<?php
                                        echo $_SESSION['phonenumber'];
                                    ?>" required>
                                <label for="phone" class="form-label">Phone</label>
                            </div>
                        </div>
                        <div class="mb-3 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="inputAddress" name="inputAddress" placeholder="123 Main St"
                                    value="<?php
                                        echo $_SESSION['address'];
                                    ?>" required>
                                <label for="inputAddress" class="form-label">Address</label>
                            </div>
                        </div>
                        <div class="mb-3 col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="inputAddress2" name="inputAddress2" placeholder="Apt 502"
                                    value="<?php
                                        echo $_SESSION['apartmentnumber'];
                                    ?>">
                                <label for="inputAddress2" class="form-label">Apartment/studio/floor</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="inputCity" name="inputCity" placeholder="City" value="<?php
                                        echo $_SESSION['city'];
                                    ?>"
                                    required>
                                <label for="inputCity" class="form-label">City</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <div class="form-floating">
                                <select id="inputState" name="inputState" class="form-select" required>
                                    <option value="" <?php echo $_SESSION['state'] == "" ? "selected" : ''?> disabled>Select</option>
                                    <option value="AL" <?php echo $_SESSION['state'] == "AL" ? "selected" : ''?>>AL</option>
                                    <option value="AK" <?php echo $_SESSION['state'] == "AK" ? "selected" : ''?>>AK</option>
                                    <option value="AZ" <?php echo $_SESSION['state'] == "AZ" ? "selected" : ''?>>AZ</option>
                                    <option value="AR" <?php echo $_SESSION['state'] == "AR" ? "selected" : ''?>>AR</option>
                                    <option value="CA" <?php echo $_SESSION['state'] == "CA" ? "selected" : ''?>>CA</option>
                                    <option value="CO" <?php echo $_SESSION['state'] == "CO" ? "selected" : ''?>>CO</option>
                                    <option value="CT" <?php echo $_SESSION['state'] == "CT" ? "selected" : ''?>>CT</option>
                                    <option value="DE" <?php echo $_SESSION['state'] == "DE" ? "selected" : ''?>>DE</option>
                                    <option value="FL" <?php echo $_SESSION['state'] == "FL" ? "selected" : ''?>>FL</option>
                                    <option value="GA" <?php echo $_SESSION['state'] == "GA" ? "selected" : ''?>>GA</option>
                                    <option value="HI" <?php echo $_SESSION['state'] == "HA" ? "selected" : ''?>>HI</option>
                                    <option value="ID" <?php echo $_SESSION['state'] == "ID" ? "selected" : ''?>>ID</option>
                                    <option value="IL" <?php echo $_SESSION['state'] == "IL" ? "selected" : ''?>>IL</option>
                                    <option value="IN" <?php echo $_SESSION['state'] == "IN" ? "selected" : ''?>>IN</option>
                                    <option value="IA" <?php echo $_SESSION['state'] == "IA" ? "selected" : ''?>>IA</option>
                                    <option value="KS" <?php echo $_SESSION['state'] == "KS" ? "selected" : ''?>>KS</option>
                                    <option value="KY" <?php echo $_SESSION['state'] == "KY" ? "selected" : ''?>>KY</option>
                                    <option value="LA" <?php echo $_SESSION['state'] == "LA" ? "selected" : ''?>>LA</option>
                                    <option value="ME" <?php echo $_SESSION['state'] == "ME" ? "selected" : ''?>>ME</option>
                                    <option value="MD" <?php echo $_SESSION['state'] == "MD" ? "selected" : ''?>>MD</option>
                                    <option value="MA" <?php echo $_SESSION['state'] == "MA" ? "selected" : ''?>>MA</option>
                                    <option value="MI" <?php echo $_SESSION['state'] == "MI" ? "selected" : ''?>>MI</option>
                                    <option value="MN" <?php echo $_SESSION['state'] == "MN" ? "selected" : ''?>>MN</option>
                                    <option value="MS" <?php echo $_SESSION['state'] == "MS" ? "selected" : ''?>>MS</option>
                                    <option value="MO" <?php echo $_SESSION['state'] == "MO" ? "selected" : ''?>>MO</option>
                                    <option value="MT" <?php echo $_SESSION['state'] == "MO" ? "selected" : ''?>>MT</option>
                                    <option value="NE" <?php echo $_SESSION['state'] == "NE" ? "selected" : ''?>>NE</option>
                                    <option value="NV" <?php echo $_SESSION['state'] == "NV" ? "selected" : ''?>>NV</option>
                                    <option value="NH" <?php echo $_SESSION['state'] == "NH" ? "selected" : ''?>>NH</option>
                                    <option value="NJ" <?php echo $_SESSION['state'] == "NJ" ? "selected" : ''?>>NJ</option>
                                    <option value="NM" <?php echo $_SESSION['state'] == "NM" ? "selected" : ''?>>NM</option>
                                    <option value="NY" <?php echo $_SESSION['state'] == "NY" ? "selected" : ''?>>NY</option>
                                    <option value="NC" <?php echo $_SESSION['state'] == "NC" ? "selected" : ''?>>NC</option>
                                    <option value="ND" <?php echo $_SESSION['state'] == "ND" ? "selected" : ''?>>ND</option>
                                    <option value="OH" <?php echo $_SESSION['state'] == "OH" ? "selected" : ''?>>OH</option>
                                    <option value="OK" <?php echo $_SESSION['state'] == "OK" ? "selected" : ''?>>OK</option>
                                    <option value="OR" <?php echo $_SESSION['state'] == "OR" ? "selected" : ''?>>OR</option>
                                    <option value="PA" <?php echo $_SESSION['state'] == "PA" ? "selected" : ''?>>PA</option>
                                    <option value="RI" <?php echo $_SESSION['state'] == "RI" ? "selected" : ''?>>RI</option>
                                    <option value="SC" <?php echo $_SESSION['state'] == "SC" ? "selected" : ''?>>SC</option>
                                    <option value="SD" <?php echo $_SESSION['state'] == "SD" ? "selected" : ''?>>SD</option>
                                    <option value="TN" <?php echo $_SESSION['state'] == "TN" ? "selected" : ''?>>TN</option>
                                    <option value="TX" <?php echo $_SESSION['state'] == "TX" ? "selected" : ''?>>TX</option>
                                    <option value="UT" <?php echo $_SESSION['state'] == "UT" ? "selected" : ''?>>UT</option>
                                    <option value="VT" <?php echo $_SESSION['state'] == "VT" ? "selected" : ''?>>VT</option>
                                    <option value="VA" <?php echo $_SESSION['state'] == "VA" ? "selected" : ''?>>VA</option>
                                    <option value="WA" <?php echo $_SESSION['state'] == "WA" ? "selected" : ''?>>WA</option>
                                    <option value="WV" <?php echo $_SESSION['state'] == "WV" ? "selected" : ''?>>WV</option>
                                    <option value="WI" <?php echo $_SESSION['state'] == "WI" ? "selected" : ''?>>WI</option>
                                    <option value="WY" <?php echo $_SESSION['state'] == "WY" ? "selected" : ''?>>WY</option>
                                </select>
                                <label for="inputState" class="form-label">State</label>
                            </div>
                        </div>
                        <div class="mb-3 col-md-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="inputZip" name="inputZip" placeholder="Zip" value=<?php
                                        echo $_SESSION['zipcode'];
                                    ?>
                                    required>
                                <label for="inputZip" class="form-label">Zip</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="paymentType" name="paymentType" required>
                                    <option value="" <?php echo $_SESSION['payment'] == "" ? "selected" : ''?> disabled>Select</option>
                                    <option value="PayPal" <?php echo $_SESSION['payment'] == "PayPal" ? "selected" : ''?>>PayPal</option>
                                    <option value="Stripe" <?php echo $_SESSION['payment'] == "Stripe" ? "selected" : ''?>>Stripe</option>
                                    <option value="Apple Pay" <?php echo $_SESSION['payment'] == "Apple Pay" ? "selected" : ''?>>Apple Pay</option>
                                </select>
                                <label for="paymentType">Payment type</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-5">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="saveInfo" name="saveInfo">
                                <label class="form-check-label" for="saveInfo">Save billing information for
                                    next time</label>
                            </div>
                        </div>
                    </div>
                    <h4>Pickup information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="pickupMonth" name="pickupMonth" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <label for="pickupMonth">Month</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="pickupDay" name="pickupDay" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="31">31</option>
                                </select>
                                <label for="pickupDay">Day</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="pickupHour" name="pickupHour" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                                <label for="pickupHour">Hour</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="pickupMinute" name="pickupMinute" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                                <label for="pickupMinute">Minute</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="pickupAMPM" name="pickupAMPM" required>
                                    <option value="" selected disabled>--</option>
                                    <option value="am">AM</option>
                                    <option value="pm">PM</option>
                                </select>
                                <label for="pickupAMPM">Period</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="carDescription" name="carDescription" placeholder="Enter car information here" id="inputCarInfo" name="inputCarInfo"
                            style="height: 100px; resize: none;" required></textarea>
                        <label for="inputCarInfo">Car description and information</label>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <button type="submit" name="submit" method="post" class="btn btn-dark w-100 btn-lg">Purchase</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>