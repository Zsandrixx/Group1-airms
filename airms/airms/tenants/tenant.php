<?php
session_start();
include '../includes/db.php';

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM TENANT WHERE tenant_id=$id");
    header("Location: tenant.php");
    exit();
}

/* ================= UPDATE ONLY ================= */
if (isset($_POST['save_tenant'])) {

    $id = $_POST['tenant_id'];

    // MUST HAVE ID (prevent adding new records)
    if ($id == "") {
        header("Location: tenant.php");
        exit();
    }

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $emergency_no = $_POST['emergency_no'];


    $sql = "UPDATE TENANT SET
        first_name='$first_name',
        middle_name='$middle_name',
        last_name='$last_name',
        suffix='$suffix',
        email='$email',
        contact_no='$contact_no',
        emergency_no='$emergency_no'
        
        WHERE tenant_id=$id";

    mysqli_query($conn, $sql);

    header("Location: tenant.php");
    exit();
}

/* ================= FETCH ================= */
$result = mysqli_query($conn, "SELECT * FROM TENANT ORDER BY tenant_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="tenant.css">
    <title>Tenant Management</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            display: flex;
        }

        .form-box {
            width: 25%;
            height: 65%;
            border-radius: 20px;
            padding: 20px;
            background: #283bab;
        }

        .table-box {
            width: 65%;
            padding: 20px;
            overflow-x: auto;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #36a44a;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #1c2f6b;
            color: white;
        }

        .row {
            cursor: pointer;
        }

        .row:hover {
            background: #eef3ff;
        }

        .selected {
            background: #d6e4ff !important;
        }

        .notice {
            font-size: 12px;
            color: #c4dfe4;
            font-style: italic;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<!-- ================= FORM ================= -->
<div class="form-box">

    <h2>Edit Tenant Only</h2>

    <div class="notice">
        Click a row to load data. You can only edit existing records.
    </div>

    <form method="POST">

        <input type="hidden" name="tenant_id" id="tenant_id">

        <input type="text" name="first_name" id="first_name" placeholder="First Name" required>

        <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name">

        <input type="text" name="last_name" id="last_name" placeholder="Last Name" required>

        <input type="text" name="suffix" id="suffix" placeholder="Suffix">

        <!-- BIRTHDATE IS READ ONLY (NOT IN FORM) -->

        <input type="email" name="email" id="email" placeholder="Email" required>

        <input type="text" name="contact_no" id="contact_no" placeholder="Contact No" required>

        <input type="text" name="emergency_no" id="emergency_no" placeholder="Emergency No" required>

      

        <button type="submit" name="save_tenant">Save Changes</button>

    </form>

</div>

<!-- ================= TABLE ================= -->
<div class="table-box">

    <h2>Tenant List</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>First</th>
            <th>Middle</th>
            <th>Last</th>
            <th>Suffix</th>
            <th>Birthdate</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Emergency</th>
            <th>Delete</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="row"
                data-id="<?php echo $row['tenant_id']; ?>"
                data-first="<?php echo $row['first_name']; ?>"
                data-middle="<?php echo $row['middle_name']; ?>"
                data-last="<?php echo $row['last_name']; ?>"
                data-suffix="<?php echo $row['suffix']; ?>"
                data-email="<?php echo $row['email']; ?>"
                data-contact="<?php echo $row['contact_no']; ?>"
                data-emergency="<?php echo $row['emergency_no']; ?>"
                >

                <td><?php echo $row['tenant_id']; ?></td>

                <td>
                    <?php echo $row['first_name'] . " "; ?>
                </td>

                <td>
                    <?php echo $row['middle_name'] . " "; ?>
                </td>

                <td>
                    <?php echo $row['last_name'] . " "; ?>
                </td>

                <td>
                    <?php echo $row['suffix']; ?>
                </td>

                <td><?php echo $row['birthdate']; ?></td>

                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['contact_no']; ?></td>
                <td><?php echo $row['emergency_no']; ?></td>

                <td>
                    <a href="tenant.php?delete=<?php echo $row['tenant_id']; ?>"
                       onclick="return confirm('Delete this tenant?')"
                       style="color:red;">X</a>
                </td>

            </tr>
        <?php } ?>

    </table>

</div>

<!-- ================= SCRIPT ================= -->
<script>
let rows = document.querySelectorAll(".row");

rows.forEach(row => {
    row.addEventListener("click", function () {

        rows.forEach(r => r.classList.remove("selected"));
        this.classList.add("selected");

        document.getElementById("tenant_id").value = this.dataset.id;
        document.getElementById("first_name").value = this.dataset.first;
        document.getElementById("middle_name").value = this.dataset.middle;
        document.getElementById("last_name").value = this.dataset.last;
        document.getElementById("suffix").value = this.dataset.suffix;
        document.getElementById("email").value = this.dataset.email;
        document.getElementById("contact_no").value = this.dataset.contact;
        document.getElementById("emergency_no").value = this.dataset.emergency;
    });
});
</script>

</body>
</html>