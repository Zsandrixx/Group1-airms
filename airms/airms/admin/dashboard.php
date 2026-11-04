<?php
session_start();

include '../includes/db.php';


/* TOTAL UNITS */
$total_units_query = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM UNIT");

$total_units = mysqli_fetch_assoc($total_units_query)['total'];

/* OCCUPIED UNITS */
$occupied_query = mysqli_query($conn,
    "SELECT COUNT(*) AS occupied
     FROM UNIT
     WHERE unit_status='Occupied'");

$occupied_units = mysqli_fetch_assoc($occupied_query)['occupied'];

/* VACANT UNITS */
$vacant_query = mysqli_query($conn,
    "SELECT COUNT(*) AS vacant
     FROM UNIT
     WHERE unit_status='Unoccupied'");

$vacant_units = mysqli_fetch_assoc($vacant_query)['vacant'];

/* UNPAID RENTALS */
$unpaid_query = mysqli_query($conn,
    "SELECT COUNT(*) AS unpaid
     FROM RENTAL
     WHERE rental_status='Pending'");

$unpaid_rentals = mysqli_fetch_assoc($unpaid_query)['unpaid'];

/* ACTIVE TENANTS */
$tenant_query = mysqli_query($conn,
    "SELECT COUNT(*) AS tenants
     FROM BOOKING
     WHERE booking_status='Active'");

$active_tenants = mysqli_fetch_assoc($tenant_query)['tenants'];

/* MONTHLY REVENUE */
$revenue_query = mysqli_query($conn,
    "SELECT SUM(amount_due) AS revenue
     FROM RENTAL
     WHERE rental_status='Paid'");

$monthly_revenue = mysqli_fetch_assoc($revenue_query)['revenue'];

if ($monthly_revenue == null) {
    $monthly_revenue = 0;
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard</title>

    <style>

        body{
            font-family: Arial;
            background: #c41fe6;
        }

        h1{
            margin-bottom: 5px;
        }

        .dashboard{
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .box{
            background:rgb(13, 37, 82);;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .box h2{
            margin: 0;
            font-size: 18px;
            color: white;
        }

        .box p{
            font-size: 35px;
            margin-top: 15px;
            font-weight: bold;
        }

        .logout{
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background: crimson;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

    </style>

</head>
<body>

<div class="container">


    <!-- SIDEBAR -->
    <div class="sidebar">

        <h1>
            <img src="../admin/images/small logo.png">
        </h1>

        <img 
            src="../admin/images/userprofile.png" 
            class="profile"
        >

        
        <a href="dashboard.php" class="active">
            Dashboard
        </a>

        <a href="#">
            Units
        </a>

        <a href="#">
            Tenants
        </a>

        <a href="#">
            Booking
        </a>

        <a href="#">
            Rentals & Payments
        </a>

        <a href="../logout.php">
            Logout
        </a>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOP BAR -->
        <div class="top-bar">

            <h1>
                Hello, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?>!
            </h1>
            <p>
                Today is <?php echo date("l, j F Y"); ?>
            </p>

        </div>

        <!-- DASHBOARD BOXES -->
        <div class="dashboard">

            <div class="box">
                <h2>Total Units</h2>
                <p><?php echo $total_units; ?></p>
            </div>

            <div class="box">
                <h2>Occupied Units</h2>
                <p><?php echo $occupied_units; ?></p>
            </div>

            <div class="box">
                <h2>Vacant Units</h2>
                <p><?php echo $vacant_units; ?></p>
            </div>

            <div class="box">
                <h2>Unpaid Rentals</h2>
                <p><?php echo $unpaid_rentals; ?></p>
            </div>

            <div class="box">
                <h2>Active Tenants</h2>
                <p><?php echo $active_tenants; ?></p>
            </div>

            <div class="box">
                <h2>Monthly Revenue</h2>
                <p>
                    ₱<?php echo number_format($monthly_revenue, 2); ?>
                </p>
            </div>

        </div>

    </div>


</body>
</html>