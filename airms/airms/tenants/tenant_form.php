<?php
session_start();
include '../includes/db.php';

/* ================= SAVE TENANT THEN NEXT ================= */
if (isset($_POST['next'])) {

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $emergency_no = $_POST['emergency_no'];
    

    // INSERT TENANT
    $sql = "INSERT INTO TENANT 
    (first_name, middle_name, last_name, suffix, birthdate, email, contact_no, emergency_no)
    VALUES 
    ('$first_name','$middle_name','$last_name','$suffix','$birthdate','$email','$contact_no','$emergency_no')";

    mysqli_query($conn, $sql);

    // GET LAST INSERTED ID
    $tenant_id = mysqli_insert_id($conn);

    // STORE IN SESSION
    $_SESSION['tenant_id'] = $tenant_id;

    // GO TO BOOKING FORM
    header("Location: ../booking/booking_form.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenant Registration</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            width: 400px;
            padding: 20px;
            background: #dfe3ea;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #1c2f6b;
            color: white;
            border: none;
        }
    </style>
</head>

<body>

<div class="box">

    <h2>Tenant Information</h2>

    <form method="POST">

        <input type="text" name="first_name" placeholder="First Name" required>

        <input type="text" name="middle_name" placeholder="Middle Name">

        <input type="text" name="last_name" placeholder="Last Name" required>

        <input type="text" name="suffix" placeholder="Suffix">

        <input type="date" name="birthdate" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="contact_no" placeholder="Contact No" required>

        <input type="text" name="emergency_no" placeholder="Emergency No" required>


        <button type="submit" name="next">Next → Book Unit</button>

    </form>

</div>

</body>
</html>