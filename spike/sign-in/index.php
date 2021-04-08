<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
    integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
    crossorigin="anonymous"></script>
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

  <title>Sign In | Badger Bytes</title>
  <style>
    @media (max-width: 768px) {
      .floatingBox {
        padding-top: 2rem !important;
        height: 100vh;
      }

      .hero {
        height: 100px;
      }
    }

    @media (min-width: 768px) {
      .floatingBox {
        border-radius: 2rem !important;
        padding: 5rem !important;
        margin-bottom: 5rem;
      }

      .hero {
        height: 200px;
      }
    }
  </style>
</head>

<body class="bg-dark">
  <div class="hero w-100 text-white text-center d-flex flex-column mb-3">
    <h1 class=" mt-auto mb-auto">Sign In</h1>
  </div>
  <div class="container col-md-6 bg-light floatingBox">
    <form id="signin" method="post" action="index.php">
      <div class="row">
        <div class="col-12 mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
            <label for="username" class="form-label">Username</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 mb-3">
          <div class="form-floating">
            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
            <label for="password" class="form-label">Password</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6 mb-3">
          <a href="../create-account/" class="btn text-muted fs-6 mt-2" style="text-decoration: none;">Create an
            Account</a>
        </div>
        <div class="col-6 mb-3">
          <input type="submit" value="Sign In" name="submit" class="btn btn-dark btn-lg float-end">
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
            echo "<div class='col mb-3'>";
            echo "<div class='alert alert-danger' role='alert'>";
            die("Connection failed: " . mysqli_connect_error());
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
      ?>
    </form>
  </div>
  <?php
          $username = $_POST['username'];
          $password = $_POST['password'];

          $qquery = "SELECT username, password, address, payment, accounttype, phonenumber,apartmentnumber,city,state,zipcode FROM accounts WHERE username = '" . $username . "' ";
          $result = mysqli_query($conn, $qquery);
          $resultarray = mysqli_fetch_array($result);
          if($username != $resultarray['username'] || $password != $resultarray['password']){
            echo "<div class='container'>";
            echo "<div class='row'>";
            echo "<div class='col mb-3'>";
            echo "<div class='alert alert-danger' role='alert'>";
            echo "Incorrect username or password.";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
          else if($password == $resultarray['password']) {
            session_start();
            $_SESSION['username'] = $resultarray['username'];
            $_SESSION['password'] = $resultarray['password'];
            $_SESSION['address'] = $resultarray['address'];
            $_SESSION['payment'] = $resultarray['payment'];
            $_SESSION['accounttype'] = $resultarray['accounttype'];
            $_SESSION['phonenumber'] = $resultarray['phonenumber'];
            $_SESSION['apartmentnumber'] = $resultarray['apartmentnumber'];
            $_SESSION['city'] = $resultarray['city'];
            $_SESSION['state'] = $resultarray['state'];
            $_SESSION['zipcode'] = $resultarray['zipcode'];
            $_SESSION['cart'] = [];

            if (strcmp($resultarray['accounttype'], "admin") == 0) {
    ?>
              <script type="text/javascript">window.location = "../view-orders";</script>
    <?php
            } else if (strcmp($resultarray['accounttype'], "staff") == 0) {
    ?>
              <script type="text/javascript">window.location = "../view-orders";</script>
    <?php
            } else {
    ?>
              <script type="text/javascript">window.location = "../menu";</script>
    <?php
            }
          }
        mysqli_close($conn);
      }
    ?>
  </div>
</body>

</html>