<?php session_start(); ?>
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

  <title>Usage Reports | Badger Bytes</title>
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

    th,
    td {
      padding: 1rem !important;
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
    style="background: url('graph.png') repeat; background-size: 50px;">
    <div class="container mt-auto mb-auto">
      <h1>
        Usage Reports
      </h1>
    </div>
  </div>
  <div class="container">
    <?php
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
    ?>
    <form action="index.php" method="post">
      <h4>Usage Specifications</h4>
      <div class="row">
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <select id="menuitem" class="form-select" name="menuitem" required>
              <option value="" selected disabled>Select</option>
              <option value="ALL ITEMS">ALL ITEMS</option>
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
            <label for="menuitem" class="form-label">Menu item</label>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <div class="form-floating">
            <select class="form-select" id="timeperiod" name="timeperiod" required>
              <option value="" selected disabled>Select</option>
              <option value="Last 24 hours">Last 24 hours</option>
              <option value="Last 7 days">Last 7 days</option>
              <option value="Last 30 days">Last 30 days</option>
              <option value="Last 365 days">Last 365 days</option>
            </select>
            <label for="timeperiod">Time period</label>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-12">
          <input type="submit" value="Show Usage" name="submit" class="btn btn-dark btn-lg">
        </div>
      </div>
      <?php
          if(isset($_POST['submit'])){
            $date = date("Y-m-d");

            // Modifying date depending on selected time range
            $timeperiod = $_POST['timeperiod'];
            if(strcmp($timeperiod, "Last 24 hours") == 0) {
              $dateModify = date_create();
              $dateModify = date_modify($dateModify, "-1 days");
              $dateModify = date_format($dateModify, "Y-m-d");
            }else if(strcmp($timeperiod, "Last 7 days") == 0) {
              $dateModify = date_create();
              $dateModify = date_modify($dateModify, "-7 days");
              $dateModify = date_format($dateModify, "Y-m-d");
            }else if(strcmp($timeperiod, "Last 30 days") == 0){
              $dateModify = date_create();
              $dateModify = date_modify($dateModify, "-30 days");
              $dateModify = date_format($dateModify, "Y-m-d");
            }else {
              $dateModify = date_create();
              $dateModify = date_modify($dateModify, "-365 days");
              $dateModify = date_format($dateModify, "Y-m-d");
            }

            // Getting all sales in date range
            $item = $_POST['menuitem'];
            if(strcmp($item, "ALL ITEMS")==0){
              $query = "SELECT time, item, price FROM salesreport WHERE time BETWEEN '" . $dateModify. " 00:00:00' AND '" . $date . " 23:00:00' ";
              $result = mysqli_query($conn, $query);
              // Storing each row returned from the result query into rows[] array
              while($row = mysqli_fetch_array($result)){
                $rows[] = $row;
              }
            } else {
              $query = "SELECT time, item, price FROM salesreport WHERE item = '" . $item . "' AND time BETWEEN '" . $dateModify. " 00:00:00' AND '" . $date . " 23:00:00' ";
              $result = mysqli_query($conn, $query);
              // Storing each row returned from the result query into rows[] array
              while($row = mysqli_fetch_array($result)){
                $rows[] = $row;
              }
            }
            if ($result) {
              echo "<h4>Output Data for " . $item . " within the " . $timeperiod . "</h4>";
              echo "<div class='row mb-3'>";
                echo "<div class='col'>";
                  echo "<div class='bg-white border rounded px-3 py-3 table-responsive'>";
                    
                    // Creating table to output all the sales
                    echo "<table>";
                      echo "<thead>";
                        echo "<tr>";
                          echo "<th>Item</th>";
                          echo "<th>Cost</th>";
                          echo "<th>Date Sold</th>";
                        echo "</tr>";
                      echo "</thead>";
                      echo "<tbody>";
                        foreach($rows as $row) {
                          echo "<tr>";
                            echo "<td>" . $row['item'] . "</td>";
                            echo "<td>$" . $row['price'] . "</td>";
                            echo "<td>" . $row['time'] . "</td>";
                          echo "</tr>";
                        }
                      echo "</tbody>";
                    echo "</table>";
	          
                  // Getting total revenue
                  $total = 0;
                  foreach($rows as $row) {
                    $total = $total + (double)($row['price']);
                  }
        
                  echo "<br><b style='margin-left: 1rem;'>Total Revenue: $" . $total . "</b>";
                }
                echo "</div>";
              echo "</div>";
            echo "</div>";
            echo "<div class='row'>";
              echo "<div class='col'>";
                echo "<button class='btn btn-lg btn-dark mb-3' onClick='window.print()'>Print Report</button>";
              echo "</div>";
            echo "</div>";
          }
      ?>
    </form>
  </div>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</body>

</html>