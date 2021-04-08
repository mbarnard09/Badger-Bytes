<!DOCTYPE html>
<html>

<?php
    session_start();

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

    if(isset($_POST['usernamesubmit'])){
        // username change
        $newusername =  $_POST['floatingUsername'];
        $newusernameconfirm = $_POST['usernameConfirmation'];

        if($newusername == $newusernameconfirm && is_null($newusername) != 1) {
            //changing in orders 
            $tempusername = $_SESSION['username'];
            $kidquery = "UPDATE orders SET username = '".$newusername."' WHERE username = '".$tempusername."' ";
            $kidresult = mysqli_query($conn, $kidquery);

            $tquery = "SELECT * FROM accounts where username ='".$newusername."'";
            $tresult = mysqli_query($conn, $tquery);
            if(mysqli_num_rows($tresult) == 0){
                $queryusernameupdate = "UPDATE spike.accounts SET username='".$newusername."' WHERE username='".$_SESSION['username']."'";
                // change location to sign in
                $_SESSION['username'] = $newusername;
                mysqli_query($conn, $queryusernameupdate);
            }
            else{
                echo "<div class='alert alert-danger mb-0' role='alert'>";
                echo "Username taken.";
                echo "</div>";
            }
        }
        else{
            echo "<div class='alert alert-danger mb-0' role='alert'>";
            echo "Usernames do not match.";
            echo "</div>";
        }

        $conn->close();
    }   

    if(isset($_POST['passwordsubmit'])){
        // password change
        $oldpassword = $_POST['passwordOld'];
        $newpassword = $_POST['passwordNew'];
        $newpasswordconfirm = $_POST['passwordConfirmation'];

        // use session password to compare
        if($_SESSION['password'] != $oldpassword){
            echo "<div class='alert alert-danger mb-0' role='alert'>";
            echo "Incorrect old password.";
            echo "</div>";
        }
        else{
            if($newpassword == $newpasswordconfirm && !is_null($newpassword)){
                $querypasswordupdate = "UPDATE spike.accounts SET password='".$newpassword."' WHERE username='".$_SESSION['username']."'";
                mysqli_query($conn, $querypasswordupdate);

                // change location to sign in
                $_SESSION['password'] = $newpassword;
            } else {
                echo "<div class='alert alert-danger mb-0' role='alert'>";
                echo "New passwords do not match.";
                echo "</div>";
            }
        }

        $conn->close();
    }

    if(isset($_POST['addresssubmit'])){
        // address change
        $newaddress = $_POST['inputAddress'];
        $newaptnumber = $_POST['inputAddress2'];
        $newcity = $_POST['inputCity'];
        $newstate = $_POST['inputState'];
        $newzip = $_POST['inputZip'];

        $queryaddressupdate = "UPDATE spike.accounts SET address='".$newaddress."', apartmentnumber='".$newaptnumber."', city='".$newcity."', state='".$newstate."', zipcode='".$newzip."' WHERE username='".$_SESSION['username']."'";
        mysqli_query($conn, $queryaddressupdate);

        $conn->close();
        $_SESSION['address'] = $newaddress;
        $_SESSION['apartmentnumber'] = $newaptnumber;
        $_SESSION['city'] = $newcity;
        $_SESSION['state'] = $newstate;
        $_SESSION['zipcode'] = $newzip;
    }

    if(isset($_POST['phonesubmit'])){
        // phonenumber change
        $newphonenumber = $_POST['phone'];

        $queryphoneupdate = "UPDATE spike.accounts SET phonenumber='".$newphonenumber."' WHERE username='".$_SESSION['username']."'";
        mysqli_query($conn, $queryphoneupdate);

        $conn->close();
        $_SESSION['phonenumber'] = $newphonenumber;
    }
    
    if(isset($_POST['paymentsubmit'])){
        // payment change
        $newpayment = $_POST['paymentType'];

        $querypaymentupdate = "UPDATE spike.accounts SET payment='".$newpayment."' WHERE username='".$_SESSION['username']."'";
        mysqli_query($conn, $querypaymentupdate);

        $conn->close();
        $_SESSION['payment'] = $newpayment;
    }
?>

<head>
    <title>Manage Account | Badger Bytes</title>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
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
        style="background: url('gear.png') repeat; background-size: 50px;">
        <div class="container mt-auto mb-auto">
            <h1>
                Manage Account
            </h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form id="usernameupdate" method="post" class="col-sm mb-5">
                <h3 id="ma-username">Change Username</h3>
                <div class="form-floating mb-3" aria-describedby="usernameHelpBlock">
                    <input type="username" class="form-control" name="floatingUsername" id="floatingUsername"
                        placeholder="New username" required>
                    <label for="floatingUsername">New username</label>
                </div>
                <div class="form-floating mb-3" aria-describedby="usernameHelpBlock">
                    <input type="username" class="form-control" id="usernameConfirmation"
                        placeholder="Confirm new username" name="usernameConfirmation" required>
                    <label for="usernameConfirmation" class="form-label">Confirm new username</label>
                </div>
                <div id="usernameHelpblock" class="form-text mb-3">
                    Your current username is <b>
                        <?php echo $_SESSION['username'];?>
                    </b>. Once your username changes, you will need
                    to use your new username to sign in to your Badger Bytes account.
                </div>
                <button type="submit" name="usernamesubmit" class="btn btn-dark">Update Username</button>
            </form>
            <form id="passwordupdate" method="post" class="col-sm mb-5">
                <h3 id="ma-password">Change Password</h3>
                <div class="form-floating mb-3" aria-describedby="passwordHelpBlock" required>
                    <input type="password" class="form-control" id="passwordOld" name="passwordOld"
                        placeholder="Old password">
                    <label for="passwordOld" class="form-label">Old password</label>
                </div>
                <div class="form-floating mb-3" aria-describedby="passwordHelpBlock" required>
                    <input type="password" class="form-control" id="password" name="passwordNew"
                        placeholder="New password">
                    <label for="password" class="form-label">New password</label>
                </div>
                <div class="form-floating mb-3" aria-describedby="passwordHelpBlock">
                    <input type="password" class="form-control" id="passwordConfirmation" name="passwordConfirmation"
                        placeholder="Confirm new password">
                    <label for="passwordConfirmation" class="form-label">Confirm new password</label>
                </div>
                <div id="passwordHelpBlock" class="form-text mb-3">
                    Once your password changes, you will need
                    to use your new password to sign in to your Badger Bytes account.
                </div>
                <button type="submit" formmethod="post" name="passwordsubmit" class="btn btn-dark">Change
                    Password</button>
            </form>
        </div>
        <div class="row">
            <div class="col-sm">
                <form id="addressupdate" method="post" class="row mb-5">
                    <h3 id="ma-address">Update Address</h3>
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="inputAddress" name="inputAddress"
                                placeholder="123 Main St" value="<?php
                                        echo $_SESSION['address'];
                                    ?>" required>
                            <label for="inputAddress" class="form-label">Address</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class=" form-floating">
                            <input type="text" class="form-control" id="inputAddress2" name="inputAddress2"
                                placeholder="Apt 502" value="<?php
                                        echo $_SESSION['apartmentnumber'];
                                    ?>">
                            <label for="inputAddress2" class="form-label">Apartment/studio/floor</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="inputCity" name="inputCity" placeholder="City"
                                value="<?php
                                        echo $_SESSION['city'];
                                    ?>" required>
                            <label for="inputCity" class="form-label">City</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <select id="inputState" name="inputState" class="form-select" required>
                                <option value="" <?php echo $_SESSION['state']=="" ? "selected" : '' ?> disabled>Select
                                </option>
                                <option value="AL" <?php echo $_SESSION['state']=="AL" ? "selected" : '' ?>>AL</option>
                                <option value="AK" <?php echo $_SESSION['state']=="AK" ? "selected" : '' ?>>AK</option>
                                <option value="AZ" <?php echo $_SESSION['state']=="AZ" ? "selected" : '' ?>>AZ</option>
                                <option value="AR" <?php echo $_SESSION['state']=="AR" ? "selected" : '' ?>>AR</option>
                                <option value="CA" <?php echo $_SESSION['state']=="CA" ? "selected" : '' ?>>CA</option>
                                <option value="CO" <?php echo $_SESSION['state']=="CO" ? "selected" : '' ?>>CO</option>
                                <option value="CT" <?php echo $_SESSION['state']=="CT" ? "selected" : '' ?>>CT</option>
                                <option value="DE" <?php echo $_SESSION['state']=="DE" ? "selected" : '' ?>>DE</option>
                                <option value="FL" <?php echo $_SESSION['state']=="FL" ? "selected" : '' ?>>FL</option>
                                <option value="GA" <?php echo $_SESSION['state']=="GA" ? "selected" : '' ?>>GA</option>
                                <option value="HI" <?php echo $_SESSION['state']=="HA" ? "selected" : '' ?>>HI</option>
                                <option value="ID" <?php echo $_SESSION['state']=="ID" ? "selected" : '' ?>>ID</option>
                                <option value="IL" <?php echo $_SESSION['state']=="IL" ? "selected" : '' ?>>IL</option>
                                <option value="IN" <?php echo $_SESSION['state']=="IN" ? "selected" : '' ?>>IN</option>
                                <option value="IA" <?php echo $_SESSION['state']=="IA" ? "selected" : '' ?>>IA</option>
                                <option value="KS" <?php echo $_SESSION['state']=="KS" ? "selected" : '' ?>>KS</option>
                                <option value="KY" <?php echo $_SESSION['state']=="KY" ? "selected" : '' ?>>KY</option>
                                <option value="LA" <?php echo $_SESSION['state']=="LA" ? "selected" : '' ?>>LA</option>
                                <option value="ME" <?php echo $_SESSION['state']=="ME" ? "selected" : '' ?>>ME</option>
                                <option value="MD" <?php echo $_SESSION['state']=="MD" ? "selected" : '' ?>>MD</option>
                                <option value="MA" <?php echo $_SESSION['state']=="MA" ? "selected" : '' ?>>MA</option>
                                <option value="MI" <?php echo $_SESSION['state']=="MI" ? "selected" : '' ?>>MI</option>
                                <option value="MN" <?php echo $_SESSION['state']=="MN" ? "selected" : '' ?>>MN</option>
                                <option value="MS" <?php echo $_SESSION['state']=="MS" ? "selected" : '' ?>>MS</option>
                                <option value="MO" <?php echo $_SESSION['state']=="MO" ? "selected" : '' ?>>MO</option>
                                <option value="MT" <?php echo $_SESSION['state']=="MO" ? "selected" : '' ?>>MT</option>
                                <option value="NE" <?php echo $_SESSION['state']=="NE" ? "selected" : '' ?>>NE</option>
                                <option value="NV" <?php echo $_SESSION['state']=="NV" ? "selected" : '' ?>>NV</option>
                                <option value="NH" <?php echo $_SESSION['state']=="NH" ? "selected" : '' ?>>NH</option>
                                <option value="NJ" <?php echo $_SESSION['state']=="NJ" ? "selected" : '' ?>>NJ</option>
                                <option value="NM" <?php echo $_SESSION['state']=="NM" ? "selected" : '' ?>>NM</option>
                                <option value="NY" <?php echo $_SESSION['state']=="NY" ? "selected" : '' ?>>NY</option>
                                <option value="NC" <?php echo $_SESSION['state']=="NC" ? "selected" : '' ?>>NC</option>
                                <option value="ND" <?php echo $_SESSION['state']=="ND" ? "selected" : '' ?>>ND</option>
                                <option value="OH" <?php echo $_SESSION['state']=="OH" ? "selected" : '' ?>>OH</option>
                                <option value="OK" <?php echo $_SESSION['state']=="OK" ? "selected" : '' ?>>OK</option>
                                <option value="OR" <?php echo $_SESSION['state']=="OR" ? "selected" : '' ?>>OR</option>
                                <option value="PA" <?php echo $_SESSION['state']=="PA" ? "selected" : '' ?>>PA</option>
                                <option value="RI" <?php echo $_SESSION['state']=="RI" ? "selected" : '' ?>>RI</option>
                                <option value="SC" <?php echo $_SESSION['state']=="SC" ? "selected" : '' ?>>SC</option>
                                <option value="SD" <?php echo $_SESSION['state']=="SD" ? "selected" : '' ?>>SD</option>
                                <option value="TN" <?php echo $_SESSION['state']=="TN" ? "selected" : '' ?>>TN</option>
                                <option value="TX" <?php echo $_SESSION['state']=="TX" ? "selected" : '' ?>>TX</option>
                                <option value="UT" <?php echo $_SESSION['state']=="UT" ? "selected" : '' ?>>UT</option>
                                <option value="VT" <?php echo $_SESSION['state']=="VT" ? "selected" : '' ?>>VT</option>
                                <option value="VA" <?php echo $_SESSION['state']=="VA" ? "selected" : '' ?>>VA</option>
                                <option value="WA" <?php echo $_SESSION['state']=="WA" ? "selected" : '' ?>>WA</option>
                                <option value="WV" <?php echo $_SESSION['state']=="WV" ? "selected" : '' ?>>WV</option>
                                <option value="WI" <?php echo $_SESSION['state']=="WI" ? "selected" : '' ?>>WI</option>
                                <option value="WY" <?php echo $_SESSION['state']=="WY" ? "selected" : '' ?>>WY</option>
                            </select>
                            <label for="inputState" class="form-label">State</label>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="inputZip" name="inputZip" placeholder="Zip"
                                value="<?php
                                        echo $_SESSION['zipcode'];
                                    ?>" required>
                            <label for="inputZip" class="form-label">Zip</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="addresssubmit" class="btn btn-dark">Update Address</button>
                    </div>
                </form>
            </div>
            <div class="col-sm">
                <div class="row">
                    <form id="phoneupdate" method="post" class="col-sm-6 mb-5">
                        <h3 id="ma-phone">Update Phone</h3>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="phone" value="<?php
                                        echo $_SESSION['phonenumber'];
                                    ?>" required>
                            <label for="phone" class="form-label">Phone</label>
                        </div>
                        <button type="submit" name="phonesubmit" class="btn btn-dark">Update Phone</button>
                    </form>
                    <form id="paymentupdate" method="post" class="col-sm-6 mb-5">
                        <h3 id="ma-payment">Update Payment Type</h3>
                        <div class="form-floating mb-3">
                            <select type="text" class="form-select" id="paymentType" name="paymentType" required>
                                <option value="" <?php echo $_SESSION['payment']=="" ? "selected" : '' ?>
                                    disabled>Select</option>
                                <option value="PayPal" <?php echo $_SESSION['payment']=="PayPal" ? "selected" : '' ?>
                                    >PayPal</option>
                                <option value="Stripe" <?php echo $_SESSION['payment']=="Stripe" ? "selected" : '' ?>
                                    >Stripe</option>
                                <option value="Apple Pay" <?php echo $_SESSION['payment']=="Apple Pay" ? "selected" : ''
                                    ?>>Apple Pay</option>
                            </select>
                            <label for="paymentType">Payment type</label>
                        </div>
                        <div class="col-12">
                            <button type="submit" formmethod="post" name="paymentsubmit" class="btn btn-dark">Update
                                Payment
                                Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>