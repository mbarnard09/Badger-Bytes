<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
    <title>Add Item | Badger Bytes</title>
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
    <!-- Bootstrap Bundle with Popper-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>
    <div class="hero w-100 text-white bg-dark d-flex flex-column mb-3 hero-size"
        style="background: url('add.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <h1>
                Add Item
            </h1>
        </div>
    </div>
    <div class="container">
        <h4>Item Details</h4>
        <form id="add/modify" method="post" action="index.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name" required>
                        <label for="name" class="form-label">Name</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="price" placeholder="Price" name="price" required>
                        <label for="price" class="form-label">Price</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="picture" placeholder="Image URL" name="picture">
                        <label for="picture" class="form-label">Image URL</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Item description" id="description"
                            style="height: 100px; resize: none;" name="description" required></textarea>
                        <label for="description">Item description</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-check form-switch mb-3" style="font-size: 1.5rem;">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                            name="availability" />
                        <label class="form-check-label" for="flexSwitchCheckDefault">Available</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Add Item" name="submit" class="btn btn-dark btn-lg mb-3">
                </div>
            </div>

            <?php
                
                if(isset($_POST['submit'])){
                    $servername = "127.0.0.1";
                    $username = "root";
                    $password = "streamable1";
                    $dbname = "spike";
                    $conn = mysqli_connect($servername, $username, $password, $dbname);
        
                    if (!$conn) {
                        echo "<div class='row'>";
                        echo "<div class='col'>";
                        echo "<div class='alert alert-danger' role='alert'>";
                        die("Connection failed: " . mysqli_connect_error());
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    
                    $name = $_POST['name'];
                    $picture = $_POST['picture'];
                    $price = $_POST['price'];
                    $availability = 0;
                    if(isset($_POST['availability'])){
                        $availability = 1;
                    }
                    $description = $_POST['description'];

                    $sql = "SELECT name FROM menu_items 
                        WHERE name = '$name'";
                        
                    if(($result1 = $conn->query($sql))){
                        if ($result1->num_rows != 0) {
                            echo "<div class='row'>";
                            echo "<div class='col'>";
                            echo "<div class='alert alert-danger' role='alert'>";
                            echo $name . " is an already existing menu item.";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            header("refresh:1;url= index.php");
                        } else {
                            $query = "INSERT INTO menu_items(name,price,description,picture,availability) 
                            VALUES ('$name', '$price','$description','$picture','$availability')";
                            if(mysqli_query($conn, $query)){
                                echo "<div class='row'>";
                                echo "<div class='col'>";
                                echo "<div class='alert alert-success' role='alert'>";
                                echo $name . " has been added to the menu.";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                header("refresh:1;url= index.php");
                            }
                        }
                    }
                        mysqli_close($conn);
                }
            
            ?>
        </form>
    </div>
</body>

</html>