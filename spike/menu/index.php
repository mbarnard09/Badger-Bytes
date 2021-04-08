<?php 
session_start();
foreach ($_POST as $item) {
    if (!in_array($item,$_SESSION['cart'])) {
      array_push($_SESSION['cart'],$item);
    }
}
?>
<!doctype html>
<html lang="en">

<head>
  <title>Menu | Badger Bytes</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="menu.css">
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
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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
    style="background: url('cup.png') repeat; background-size: 50px;">
    <div class="container mt-auto mb-auto">
      <h1>
        Menu
      </h1>
    </div>
  </div>
  <div class="container menu mb-4">
    <?php
      $servername = "127.0.0.1";
      $username = "root";
      $password = "streamable1";
      $dbname = "spike";
      
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        echo "<div class='alert alert-danger' role='alert'>";
        die("Connection failed: " . $conn->connect_error);
        echo "</div>";
      }
      
      $sql = "SELECT name,price,description,picture,availability FROM menu_items";
      $result = $conn->query($sql);
      
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $name = $row['name'];
          $price = $row['price'];
          $desc = $row['description'];
          $picture = $row['picture'];
          $id = str_replace(" ","-",$name);
          $availability = $row['availability'];
    ?>
    <div class='card border-danger bg-light' style='width: 19rem;'>
      <img src=<?php echo $picture;?> class='card-img-top' alt=''
      onerror="this.onerror=null;this.src='no_image.jpg';" style='width: 100%;height: 30vh;object-fit:
      cover;'>
      <div class='card-body overflow-auto'>
        <h5 class='card-title text-capitalize'>
          <?php echo $name ?>
        </h5>
        <p class='card-text text-muted'>
          <?php echo $desc ?>
        </p>
      </div>
      <div class='card-footer' style='display:flex; justify-content:space-between; align-items:center'>
        <?php if ($availability) {
                ?><a id="menu-btn" class='btn btn-danger' name="<?php echo $name?>" price="<?php echo $price?>"
          jqid="<?php echo $id?>">Add to Cart</a>
        <?php
              } else {
                ?><button type="button" class="btn btn-secondary" disabled>Out of Stock</button>
        <?php
              } ?>
        <input id="<?php echo $id?>-quantity" type="number" min="1" class="form-control text-center" value="1"
          style="width: 75px">
        <b>$
          <?php echo $price ?>
        </b>
      </div>
    </div>
    <?php
        }
      } else {
        echo "0 items";
      }
      $conn->close();
    ?>
  </div>

  <script>
    var cart = {}
    var count = 0
    $("a#menu-btn").click(function () {
      let name = $(this).attr("name")
      let price = $(this).attr("price")
      let id = $(this).attr("jqid")
      let quantity = $(`#${id}-quantity`).val()
      item = {
        "name": name,
        "price": price,
        "quantity": quantity
      }
      cart[count] = item
      $(this).replaceWith('<button type="button" class="btn btn-danger" disabled>Add to Cart</button>')
      $.ajax({
        type: "POST",
        url: "index.php",
        data: cart,
        success: function () {
          count++
        },
        error: function () {
          console.log(fail)
        }
      });
    })
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
    crossorigin="anonymous"></script>
</body>

</html>