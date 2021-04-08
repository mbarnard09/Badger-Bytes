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

  <title>Create an Account | Badger Bytes</title>
  <style>
    @media (max-width: 768px) {
      .floatingBox {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
      }

      .hero {
        height: 100px;
      }
    }

    @media (min-width: 768px) {
      .floatingBox {
        border-radius: 2rem !important;
        padding: 5rem !important;
        margin-bottom: 5rem !important;
      }

      .hero {
        height: 200px;
      }
    }
  </style>
</head>

<body class="bg-dark">
  <div class="hero w-100 text-white text-center d-flex flex-column mb-3">
    <h1 class="mt-auto mb-auto">Create an Account</h1>
  </div>
  <div class="container w-100 bg-light floatingBox">
    <form action="index.php" method="post">
      <h4>Account information</h4>
      <div class="row mt-3">
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
            <label for="username" class="form-label">Username</label>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <input type="password" class="form-control" id="password" placeholder="Password" name="userpassword"
              required>
            <label for="password" class="form-label">Password</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="phone" placeholder="Phone" name="phonenumber" required>
            <label for="phone" class="form-label">Phone</label>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <select id="accounttype" class="form-select" name="accounttype" required>
              <option value="" selected disabled>Select</option>
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
              <option value="customer">Customer</option>
            </select>
            <label for="accounttype" class="form-label">Account type</label>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <select class="form-select" id="paymentType" name="payment" required>
              <option value="" selected disabled>Select</option>
              <option value="PayPal">PayPal</option>
              <option value="Stripe">Stripe</option>
              <option value="Apple Pay">Apple Pay</option>
            </select>
            <label for="paymentType">Payment type</label>
          </div>
        </div>
      </div>
      <h4>Personal information</h4>
      <div class="row mb-3">
        <div class="mb-3 col-md-12">
          <div class="form-floating">
            <input type="text" class="form-control" id="inputAddress" placeholder="123 Main St" name="address" required>
            <label for="inputAddress" class="form-label">Address</label>
          </div>
        </div>
        <div class="mb-3 col-md-12">
          <div class="form-floating">
            <input type="text" class="form-control" id="inputAddress2" placeholder="Apt 502" name="apartmentnumber">
            <label for="inputAddress2" class="form-label">Apartment/studio/floor</label>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="inputCity" placeholder="City" name="city" required>
            <label for="inputCity" class="form-label">City</label>
          </div>
        </div>
        <div class="mb-3 col-md-4">
          <div class="form-floating">
            <select id="inputState" class="form-select" name="state" required>
              <option value="" selected disabled>Select</option>
              <option value="AL">AL</option>
              <option value="AK">AK</option>
              <option value="AZ">AZ</option>
              <option value="AR">AR</option>
              <option value="CA">CA</option>
              <option value="CO">CO</option>
              <option value="CT">CT</option>
              <option value="DE">DE</option>
              <option value="FL">FL</option>
              <option value="GA">GA</option>
              <option value="HI">HI</option>
              <option value="ID">ID</option>
              <option value="IL">IL</option>
              <option value="IN">IN</option>
              <option value="IA">IA</option>
              <option value="KS">KS</option>
              <option value="KY">KY</option>
              <option value="LA">LA</option>
              <option value="ME">ME</option>
              <option value="MD">MD</option>
              <option value="MA">MA</option>
              <option value="MI">MI</option>
              <option value="MN">MN</option>
              <option value="MS">MS</option>
              <option value="MO">MO</option>
              <option value="MT">MT</option>
              <option value="NE">NE</option>
              <option value="NV">NV</option>
              <option value="NH">NH</option>
              <option value="NJ">NJ</option>
              <option value="NM">NM</option>
              <option value="NY">NY</option>
              <option value="NC">NC</option>
              <option value="ND">ND</option>
              <option value="OH">OH</option>
              <option value="OK">OK</option>
              <option value="OR">OR</option>
              <option value="PA">PA</option>
              <option value="RI">RI</option>
              <option value="SC">SC</option>
              <option value="SD">SD</option>
              <option value="TN">TN</option>
              <option value="TX">TX</option>
              <option value="UT">UT</option>
              <option value="VT">VT</option>
              <option value="VA">VA</option>
              <option value="WA">WA</option>
              <option value="WV">WV</option>
              <option value="WI">WI</option>
              <option value="WY">WY</option>
            </select>
            <label for="inputState" class="form-label">State</label>
          </div>
        </div>
        <div class="mb-3 col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" id="inputZip" placeholder="Zip" name="zipcode" required>
            <label for="inputZip" class="form-label">Zip</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <input type="submit" value="Create Account" name="submit" class="btn btn-dark btn-lg">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <small><a href="../sign-in" class="text-muted" style="text-decoration: none;">Already have an account? Sign
              In</a></small>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <small class="text-muted">Note that upon account creation you will be redirected to the Sign In page.</small>
        </div>
      </div>
    </form>

    <?php
      if(isset($_POST['submit'])){
      $servername = "127.0.0.1"; 
      $username = "root";
      $password = "streamable1";
      $dbname = "spike";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        echo "<div class='alert alert-danger mt-3' role='alert'>";
        die("Connection failed: " . $conn->connect_error);
        echo "</div>";
      }
      

      $username = $_POST['username'];
      $userpassword = $_POST['userpassword'];
      $address = $_POST['address'];
      $apartmentnumber = $_POST['apartmentnumber'];
      $city = $_POST['city'];
      $state = $_POST['state'];
      $zipcode = $_POST['zipcode'];
      $payment = $_POST['payment'];
      $accounttype = $_POST['accounttype'];
      $phonenumber = $_POST['phonenumber'];
      $apartmentnumber = $_POST['apartmentnumber'];
      $city = $_POST['city'];
      $state = $_POST['state'];
      $zipcode = $_POST['zipcode'];
      
      // Check if account already exists
      $tquery = "SELECT * FROM accounts where username ='" . $username ."'";
      $tresult = mysqli_query($conn, $tquery);
      if(mysqli_num_rows($tresult) > 0){
        echo "<div class='alert alert-danger mt-3' role='alert'>";
        echo "An account with this username already exists!";
        echo "</div>";
        exit();
      }


      $sql = "INSERT INTO accounts (username, password, address, payment, accounttype, phonenumber, apartmentnumber, city, state, zipcode) VALUES ('$username', '$userpassword', '$address', '$payment', '$accounttype', '$phonenumber','$apartmentnumber', '$city', '$state', '$zipcode' )";

      if($conn->query($sql)) {        
  ?>
      <script type="text/javascript">window.location = "../sign-in";</script>
  <?php
      }
      $conn->close();
    }
  ?>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</body>

</html>