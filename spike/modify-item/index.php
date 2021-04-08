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
    <title>Modify Item | Badger Bytes</title>
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
    <?php
        $servername = "127.0.0.1";
        $username = "root";
        $password = "streamable1";
        $dbname = "spike";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    ?>
    <!-- Bootstrap Bundle with Popper-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
        crossorigin="anonymous"></script>
    <div class="hero w-100 text-white bg-dark d-flex flex-column mb-3 hero-size"
        style="background: url('slash.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <h1>
                Modify Item
            </h1>
        </div>
    </div>
    <div class="container">
        <?php
            if (!$conn) {
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<div class='alert alert-danger' role='alert'>";
                die("Connection failed: " . mysqli_connect_error());
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        ?>
        <h4>Select an Item</h4>
        <form id="selectMenuItem" method="post" action="index.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select name="formItem" id="menuItem" class="form-select" required>
                            <option value="" selected disabled>Select</option>
                            <?php 
                                $result = $conn->query("SELECT name FROM menu_items");
                                while($row = $result->fetch_assoc()) {
                                    $name = $row['name'];
                                ?>
                            <option value="<?php echo $name;?>">
                                <?php echo $name;?>
                            </option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="menuItem" class="form-label">Item</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <input type="submit" value="Select Item" name="selectItem" class="btn btn-dark btn-lg">
                </div>
            </div>
        </form>
        <?php               
        if(isset($_POST['selectItem'])){

            if (!$conn) {
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<div class='alert alert-danger' role='alert'>";
                die("Connection failed: " . mysqli_connect_error());
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            
            if(isset($_POST['formItem'])) {
                $name1 = $_POST['formItem'];
            
                $sql = "SELECT name,price,description,picture,availability FROM menu_items 
                WHERE name = '$name1'";
                
                $result1 = $conn->query($sql);
                    $row1 = $result1->fetch_assoc();
                        $price = $row1['price'];
                        $picture = $row1['picture'];
                        $description = $row1['description'];
                        $availability = $row1['availability'];
            }
                 
        }
        ?>
        <?php if(isset($_POST['selectItem'])):?>
        <h4>Item Details</h4>
        <form id="modify" method="post" action="index.php">
            <?php if($_SESSION['accounttype'] == 'admin'): ?>
            <!-- note that the option to change this information is only for admins -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Name"
                            value="<?php echo $name1;?>" name="name" required>
                        <label for="name" class="form-label">Name</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="price" placeholder="Price"
                            value="<?php echo $price;?>" name="price" required>
                        <label for="price" class="form-label">Price</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="picture" placeholder="Image URL"
                            value="<?php echo $picture;?>" name="picture">
                        <label for="picture" class="form-label">Image URL</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Item description" id="description"
                            style="height: 100px; resize: none;" name="description"
                            required><?php echo $description;?></textarea>
                        <label for="description">Item description</label>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- note that this is the same as above with prefilling fields, but the fields are all DISABLED since it is not admin access -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Name"
                            value="<?php echo $name1;?>" name="name" required readonly="readonly">
                        <label for="name" class="form-label">Name</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="price" placeholder="Price"
                            value="<?php echo $price;?>" name="price" required readonly="readonly">
                        <label for="price" class="form-label">Price</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="picture" placeholder="Image URL"
                            value="<?php echo $picture;?>" name="picture" readonly="readonly">
                        <label for="picture" class="form-label">Image URL</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Item description" id="description"
                            style="height: 100px; resize: none;" name="description" required
                            readonly="readonly"><?php echo $description;?></textarea>
                        <label for="description">Item description</label>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col">
                    <div class="form-check form-switch mb-3" style="font-size: 1.5rem;">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="availability"
                            <?php if($availability==1) echo 'checked' ?> />
                        <label class="form-check-label" for="flexSwitchCheckDefault">Available</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" value="Update Item" name="submit" class="btn btn-dark btn-lg mb-3">
                    <?php if($_SESSION['accounttype'] == 'admin'): ?>
                    <input type="submit" value="Delete Item" name="delItem" class="btn btn-danger btn-lg mb-3">
                    <?php endif; ?>
                </div>
            </div>
            <input type="hidden" />
            <input type="hidden" name="nameDel" value="<?php echo $name1; ?>" />
    </div>
    </div>
    </form>
    <?php endif; ?>
    <?php    
        if(isset($_POST['submit'])){
            // check name isnt taken
            $querycheckitem = "SELECT * FROM menu_items WHERE name='".$name."'";
            $checkresult = mysqli_query($conn, $tuerycheckitem);
            if(mysqli_num_rows($checkresult) == 0){
                $name = $_POST['name'];
                $picture = $_POST['picture'];
                $price = $_POST['price'];
                $availability = 0;
                if(isset($_POST['availability'])){
                    $availability = 1;
                }
                $description = $_POST['description'];
                $nameDel = $_POST['nameDel'];
                $sql2 = "DELETE FROM menu_items WHERE name = '$nameDel'";
                $sql3 = "UPDATE salesreport
                        SET item = '$name'
                        WHERE item = '$nameDel'";
                $conn->query($sql2);
                $conn->query($sql3);
            
                $query = "INSERT INTO menu_items(name,price,description,picture,availability) 
                VALUES ('$name', '$price','$description','$picture','$availability')";
                if(mysqli_query($conn, $query)){
                    echo "<div class='row'>";
                    echo "<div class='col'>";
                    echo "<div class='alert alert-success' role='alert'>";
                    echo $name . " has been updated.";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    header("refresh:1;url= index.php");
                }
                mysqli_close($conn);
            }
        }
            
        // Item deletion
        if(isset($_POST['delItem'])){
            $nameToDelete = $_POST['name'];
            $sql3 = "DELETE FROM menu_items WHERE name = '$nameToDelete'";

            if($conn->query($sql3)) {
                echo "<div class='row'>";
                echo "<div class='col'>";
                echo "<div class='alert alert-success' role='alert'>";
                echo $nameToDelete . " has been deleted.";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                header("refresh:1;url= index.php");
            }
            mysqli_close($conn);
        }
        
        ?>
    </div>
</body>

</html>