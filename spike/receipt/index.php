<?php session_start(); ?>
<!DOCTYPE html>
<html>
<?php
        $orderNum = $_GET['orderid'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "streamable1";
        $dbname = "spike";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
?>

<head>
    <title>Order #<?php echo $orderNum?> | Badger Bytes
    </title>
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

        .override-disabled {
            background: #fff !important;
        }

        .order-in-progress {
            background: #fff3cd !important;
        }

        .order-complete {
            background: #d1e7dd !important;
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
        style="background: url('receipt.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <div class="row">
                <div class="col-10">
                    <h1>
                        Order #<?php echo $orderNum?>
                    </h1>
                </div>
                <div class="col-2">
                    <button class="btn btn-light btn-lg float-end" onClick="window.print()">PDF</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        if (!$conn) {
            echo "<div class='row'>";
            echo "<div class='col'>";
            echo "<div class='alert alert-danger mt-3' role='alert'>";
            die("Connection failed: " . mysqli_connect_error());
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        $sql = "SELECT status,username,cardescription,time,id,phone
                FROM orders 
                WHERE id = '$orderNum'";

        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $status = $row['status'];
        $username1 = $row['username'];
        $cardescription = $row['cardescription'];
        $datetime = new DateTime($row['time']);
        $phone = $row['phone'];

        $date = $datetime->format('m/d/Y');
        $time = $datetime->format('H:i');

        $sql2 = "SELECT item,price
                 FROM salesreport
                 WHERE id = '$orderNum'";
        $result2 = $conn->query($sql2);
        $count = $result2->num_rows;
        ?>
        <div class="row">
            <div class="col-md-6">
                <h4>Order details</h4>
                <div class="row">
                    <?php if($status == 0): ?>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control order-in-progress" id="orderstatusinprogress"
                                value="In Progress" disabled>
                            <label for="orderstatusinprogress" class="form-label">Order status</label>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control order-complete" id="orderstatuscomplete"
                                value="Complete" disabled>
                            <label for="orderstatuscomplete" class="form-label">Order status</label>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control override-disabled" id="orderdate" placeholder="Date"
                                value="<?php echo $date?>" disabled>
                            <label for="orderdate" class="form-label">Date</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control override-disabled" id="pickuptime" placeholder="Time"
                                value="<?php echo $time?>" disabled>
                            <label for="pickuptime" class="form-label">Pickup time</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control override-disabled" id="username"
                                placeholder="Username" value="<?php echo $username1;?>" disabled>
                            <label for="username" class="form-label">Username</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control override-disabled" id="phone" placeholder="Phone"
                                value="<?php echo $phone;?>" disabled>
                            <label for="phone" class="form-label">Phone</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <textarea class="form-control override-disabled" placeholder="Enter car information here"
                                id="carinfo" style="height: 100px; resize: none;"
                                disabled><?php echo $cardescription;?></textarea>
                            <label for="carinfo">Car description and information</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span>Items purchased</span>
                    <span class="badge bg-secondary rounded-pill">#
                        <?php echo $count;?>
                    </span>
                </h4>
                <ul class="list-group mb-3">
                    <?php
                        $total = 0;
                        while($row2 = $result2->fetch_assoc()) {
                    ?>
                    <li class="list-group-item d-flex justify-content-between 1h-sm">
                        <div>
                            <h6 class="my-0">
                                <?php echo $row2['item'];?>
                            </h6>
                        </div>
                        <span class="text-muted">$
                            <?php echo $row2['price'];?>
                        </span>
                    </li>
                    <?php 
                            $total+= (int)$row2['price'];
                        } 
                    ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total</span>
                        <span>$
                            <?php echo $total;?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>