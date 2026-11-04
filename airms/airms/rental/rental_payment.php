<?php
session_start();
require_once 'db_connection.php'; // Include your database connection file

// Check if the rental_id is passed in the URL
if (isset($_GET['rental_id'])) {
    $rental_id = $_GET['rental_id'];

    // Fetch rental details from the database
    $query = "SELECT rental.rental_id, booking.booking_id, tenant.first_name, tenant.last_name, 
                     unit.unit_name, unit.unit_rate, rental.billing_month, rental.amount_due, rental.due_fine
              FROM rental
              JOIN booking ON rental.booking_id = booking.booking_id
              JOIN tenant ON booking.tenant_id = tenant.tenant_id
              JOIN unit ON booking.unit_id = unit.unit_id
              WHERE rental.rental_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tenant_name = $row['first_name'] . ' ' . $row['last_name'];
        $unit_name = $row['unit_name'];
        $unit_rate = $row['unit_rate'];
        $billing_month = $row['billing_month'];
        $amount_due = $row['amount_due'];
        $due_fine = $row['due_fine'];
        $total_due = $amount_due + $due_fine;
    } else {
        echo "No rental found!";
        exit();
    }
} else {
    echo "Rental ID is required!";
    exit();
}

// Handle rental payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['admin_id']; // Assuming admin is logged in and admin_id is stored in session
    $payment_method = $_POST['payment_method'];
    $paid_amount = $_POST['paid_amount'];
    $reference_no = $_POST['reference_no'];
    $payment_status = 'Paid';
    $payment_date = date("Y-m-d H:i:s");

    // Insert payment into RENTAL_PAYMENT table
    $payment_query = "INSERT INTO rental_payment (admin_id, rental_id, rpaid_amount, rpayment_date, rpayment_method, rreference_no, rpayment_status)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
    $payment_stmt = $conn->prepare($payment_query);
    $payment_stmt->bind_param("iisssss", $admin_id, $rental_id, $paid_amount, $payment_date, $payment_method, $reference_no, $payment_status);
    $payment_stmt->execute();

    // Update rental status to 'Paid'
    $update_rental_query = "UPDATE rental SET rental_status = 'Paid' WHERE rental_id = ?";
    $update_rental_stmt = $conn->prepare($update_rental_query);
    $update_rental_stmt->bind_param("i", $rental_id);
    $update_rental_stmt->execute();

    // Redirect back to rental.php
    header("Location: rental.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Payment</title>
</head>
<body>
    <h2>Rental Payment Form</h2>
    <form action="rental_payment.php?rental_id=<?php echo $rental_id; ?>" method="POST">
        <label>Tenant Name:</label>
        <input type="text" value="<?php echo $tenant_name; ?>" disabled><br><br>

        <label>Unit:</label>
        <input type="text" value="<?php echo $unit_name; ?>" disabled><br><br>

        <label>Billing Month:</label>
        <input type="text" value="<?php echo $billing_month; ?>" disabled><br><br>

        <label>Amount Due:</label>
        <input type="text" value="<?php echo $amount_due; ?>" disabled><br><br>

        <label>Late Fine:</label>
        <input type="text" value="<?php echo $due_fine; ?>" disabled><br><br>

        <label>Total Due:</label>
        <input type="text" value="<?php echo $total_due; ?>" disabled><br><br>

        <label>Payment Method:</label>
        <select name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="Online">Online</option>
            <option value="Debit">Debit</option>
        </select><br><br>

        <label>Paid Amount:</label>
        <input type="number" name="paid_amount" required><br><br>

        <label>Reference Number:</label>
        <input type="text" name="reference_no" required><br><br>

        <button type="submit">Pay</button>
    </form>
</body>
</html>