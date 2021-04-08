<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders | Badger Bytes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

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
        style="background: url('list.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <div class="row">
                <div class="col-10">
                    <h1>
                        View Orders
                    </h1>
                </div>
                <div class="col-2">
                    <button class="btn btn-light btn-lg float-end" onClick="window.print()">PDF</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container table-responsive">
        <table class="table table-striped bg-white" id="order-table">
            <thead>
                <tr>
                    <th scope="col">Order #</th>
                    <th scope="col">Car Description</th>
                    <th scope="col">Username</th>
                    <th scope="col">Items</th>
                    <th scope="col">Phone #</th>
                    <th scope="col">Status</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Date/Time</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "127.0.0.1";
                $username = "root";
                $password = "streamable1";
                $dbname = "spike";
                
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    echo "<div class='alert alert-danger' role='alert'>";
                    die("Connection failed: " . $conn->connect_error);
                    echo "</div>";
                }
                
                $sql = "SELECT * FROM orders";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    //   var_dump($row['priority']);
                    ?>
                    <tr>
                        <th scope="row"><?php echo $row['ID']?></th>
                        <td><?php echo $row['cardescription']?></td>
                        <td><?php echo $row['username']?></td>
                        <td><?php echo $row["items"]?></td>
                        <td><?php echo $row["phone"]?></td>
                        <!-- <td><?php echo $row["status"]?></td> -->
                        <td id="test">
                            <select class="form-select form-select-sm" 
                            aria-label=".form-select-sm example" 
                            name="status"
                            id="<?php echo $row['username']?>-status"
                            oid="<?php echo $row['ID']?>"
                            style="width: 125px">
                                <option value="" <?php echo $row['status']=="" ? "selected" : '' ?> disabled>Select</option>
                                <option value="0" <?php echo $row['status']== strval(0) ? "selected" : '' ?>>In Progress</option>
                                <option value="1" <?php echo $row['status']== strval(1) ? "selected" : '' ?>>Completed</option>
                            </select></td>
                        <td>
                            <div style="display: flex; justify-content: start; align-items: center; align-content: center;">
                                <select class="form-select form-select-sm" 
                                    aria-label=".form-select-sm example"
                                    name="priority"
                                    id="<?php echo $row['username']?>-newpri"
                                    oid="<?php echo $row['ID']?>"
                                    style="width: 100px">
                                    <option value="" <?php echo $row['priority']=="" ? "selected" : '' ?> disabled>Select</option>
                                    <option value="3" <?php echo $row['priority']== strval(3) ? "selected" : '' ?>>High</option>
                                    <option value="2" <?php echo $row['priority']== strval(2) ? "selected" : '' ?>>Medium</option>
                                    <option value="1" <?php echo $row['priority']== strval(1) ? "selected" : '' ?>>Low</option>
                                </select>
                            </div>
                        </td>
                        <td><?php echo $row["time"]?></td>
                        <td><a href="../receipt/index.php?orderid=<?php echo $row['ID']?>">View Receipt</a></td>
                    </tr>
                    <?php
                }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" class="table-danger">No Orders</td>
                    </tr>
                    <?php
                }
                if (count($_POST) > 0) {
                    if (isset($_POST['priority'])) {
                        $pri = intval($_POST['priority']);
                        $id = intval($_POST['id']);
                        $newsql = "UPDATE orders SET priority='".$pri."' WHERE ID='".$id."'";
                    }
                    if (isset($_POST['status'])) {
                        $sta = intval($_POST['status']);
                        $id = intval($_POST['id']);
                        $newsql = "UPDATE orders SET status='".$sta."' WHERE ID='".$id."'";
                    }
                    
                    $conn->query($newsql);
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <div id="sorted-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none">
            Table Sorted
        </div>
    </div>
</body>
<script src="table-sort.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</html>