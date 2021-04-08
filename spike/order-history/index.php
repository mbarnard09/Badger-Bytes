<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order History | Badger Bytes</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

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
        style="background: url('history.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <div class="row">
                <div class="col-10">
                    <h1>
                        Order History
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
            $servername = "127.0.0.1";
            $username = "root";
            $password = "streamable1";
            $dbname = "spike";
            
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<div class='alert alert-danger' role='alert'>";
                die("Connection failed: " . $conn->connect_error);
                echo "</div";
                echo "</div";
                echo "</div";
            }
            
            $sql = "SELECT time, ID, items, status, total FROM orders WHERE username='".$_SESSION['username']."'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-title">Order #<?php echo $row['ID'];?>
                        </h5>
                        <p class="text-muted">
                            <?php echo $row['time'] ?>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <b>Status:</b>
                        <?php if ($row['status'] == 1) { echo "Complete"; } else { echo "In Progress"; }?><br>
                        <b>Total:</b>
                        <?php echo "$" . $row['total']?>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="card-text">
                            <b>Items: </b>
                            <?php echo $row['items'];?>
                        </p>
                        <a href="../receipt/index.php?orderid=<?php echo $row['ID'] ?>">View Receipt</a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
                }
            } else {
        ?>
        <div class="alert alert-danger" role="alert">There is no order history for this account.</div>
        <?php
            }
            $conn->close();
        ?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>
</body>

</html>