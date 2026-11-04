<?php
session_start();
include '../includes/db.php';

/* AUTO GENERATE MONTHLY RENTAL RECORDS */
$bookings = mysqli_query($conn, "
    SELECT 
        b.booking_id,
        u.unit_rate
    FROM BOOKING b
    JOIN UNIT u ON b.unit_id = u.unit_id
");

while ($booking = mysqli_fetch_assoc($bookings)) {
    $booking_id = $booking['booking_id'];
    $unit_rate = $booking['unit_rate'];

    $billing_month = date('Y-m-01');
    $due_date = date('Y-m-t 23:59:59');

    $check = mysqli_query($conn, "
        SELECT rental_id 
        FROM RENTAL
        WHERE booking_id = '$booking_id'
        AND MONTH(billing_month) = MONTH(CURRENT_DATE())
        AND YEAR(billing_month) = YEAR(CURRENT_DATE())
    ");

    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "
            INSERT INTO RENTAL 
            (
                booking_id,
                billing_month,
                amount_due,
                due_date,
                due_fine,
                rental_status
            )
            VALUES
            (
                '$booking_id',
                '$billing_month',
                '$unit_rate',
                '$due_date',
                0,
                'Pending'
            )
        ");
    }
}

/* FETCH RENTAL DATA */
$result = mysqli_query($conn, "
    SELECT
        r.rental_id,
        r.billing_month,
        r.amount_due,
        r.due_date,
        r.rental_status,
        t.first_name,
        t.last_name,
        u.unit_name,
        u.unit_type
    FROM RENTAL r
    JOIN BOOKING b ON r.booking_id = b.booking_id
    JOIN TENANT t ON b.tenant_id = t.tenant_id
    JOIN UNIT u ON b.unit_id = u.unit_id
    ORDER BY r.rental_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rental Management</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            padding: 20px;
        }

        h2 {
            color: #102a63;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #1c2f6b;
            color: white;
            padding: 10px;
            border: 1px solid #ccc;
        }

        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        tr:hover {
            background: #eef3ff;
        }

        .paid {
            color: green;
            font-weight: bold;
        }

        .pending {
            color: red;
            font-weight: bold;
        }

        .pay-btn {
            background: #1c2f6b;
            color: white;
            padding: 7px 14px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            font-size: 14px;
        }

        .pay-btn:hover {
            background: #10245a;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Rental Management</h2>

    <table>
        <tr>
            <th>Rental ID</th>
            <th>Tenant</th>
            <th>Unit</th>
            <th>Billing Month</th>
            <th>Monthly Rent</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>
                <td><?php echo $row['rental_id']; ?></td>

                <td>
                    <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                </td>

                <td>
                    <?php echo $row['unit_name'] . " (" . $row['unit_type'] . ")"; ?>
                </td>

                <td><?php echo $row['billing_month']; ?></td>

                <td>₱<?php echo number_format($row['amount_due'], 2); ?></td>

                <td><?php echo $row['due_date']; ?></td>

                <td>
                    <?php if ($row['rental_status'] == 'Paid') { ?>
                        <span class="paid">Paid</span>
                    <?php } else { ?>
                        <span class="pending">Pending</span>
                    <?php } ?>
                </td>

                <td>
                    <a class="pay-btn" href="rental_payment.php?rental_id=<?php echo $row['rental_id']; ?>">
                        Pay
                    </a>
                </td>
            </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>