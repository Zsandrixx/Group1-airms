<?php
session_start();
include '../includes/db.php';

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM UNIT WHERE unit_id=$id");
    header("Location: unit.php");
    exit();
}

/* ================= CREATE / UPDATE ================= */
if (isset($_POST['save_unit'])) {

    $id = $_POST['unit_id'];
    $unit_type = $_POST['unit_type'];
    $floor_level = $_POST['floor_level'];
    $unit_rate = $_POST['unit_rate'];
    $unit_status = $_POST['unit_status'];

    if ($id == "") {
        // CREATE
        $sql = "INSERT INTO UNIT (unit_type, floor_level, unit_rate, unit_status)
                VALUES ('$unit_type', '$floor_level', '$unit_rate', '$unit_status')";
    } else {
        // UPDATE
        $sql = "UPDATE UNIT SET 
                unit_type='$unit_type',
                floor_level='$floor_level',
                unit_rate='$unit_rate',
                unit_status='$unit_status'
                WHERE unit_id=$id";
    }

    mysqli_query($conn, $sql);

    header("Location: unit.php");
    exit();
}

/* ================= FETCH ================= */
$result = mysqli_query($conn, "SELECT * FROM UNIT ORDER BY unit_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unit CRUD</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            display: flex;
            background: #f4f6f9;
        }

        .form-box {
            width: 30%;
            padding: 20px;
            background: #dfe3ea;
            height: 100vh;
        }

        .table-box {
            width: 70%;
            padding: 20px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #1c2f6b;
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
            padding: 10px;
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

        .refresh-btn {
            margin-top: 10px;
            background: #555;
        }
    </style>
</head>

<body>

<!-- ================= FORM ================= -->
<div class="form-box">

    <h2>Unit Form</h2>

    <form method="POST">

        <!-- hidden ID (used for UPDATE) -->
        <input type="hidden" name="unit_id" id="unit_id">

        <input type="text" name="unit_type" id="unit_type" placeholder="Unit Type" required>

        <input type="number" name="floor_level" id="floor_level" placeholder="Floor Level" required>

        <input type="number" step="0.01" name="unit_rate" id="unit_rate" placeholder="Unit Rate" required>

        <select name="unit_status" id="unit_status" required>
            <option value="">Select Status</option>
            <option value="Occupied">Occupied</option>
            <option value="Unoccupied">Unoccupied</option>
        </select>

        <button type="submit" name="save_unit">Done</button>

        <!-- REFRESH BUTTON -->
        <button type="button" class="refresh-btn" onclick="refreshForm()">
            Refresh
        </button>

    </form>

</div>

<!-- ================= TABLE ================= -->
<div class="table-box">

    <h2>Unit List</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Floor</th>
            <th>Rate</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr class="row"
                data-id="<?php echo $row['unit_id']; ?>"
                data-type="<?php echo $row['unit_type']; ?>"
                data-floor="<?php echo $row['floor_level']; ?>"
                data-rate="<?php echo $row['unit_rate']; ?>"
                data-status="<?php echo $row['unit_status']; ?>">

                <td><?php echo $row['unit_id']; ?></td>
                <td><?php echo $row['unit_type']; ?></td>
                <td><?php echo $row['floor_level']; ?></td>
                <td><?php echo $row['unit_rate']; ?></td>
                <td><?php echo $row['unit_status']; ?></td>

                <td>
                    <a href="unit.php?delete=<?php echo $row['unit_id']; ?>"
                       onclick="return confirm('Delete this unit?')"
                       style="color:red; text-decoration:none;">X</a>
                </td>

            </tr>
        <?php } ?>

    </table>

</div>

<!-- ================= SCRIPT ================= -->
<script>
let rows = document.querySelectorAll(".row");

// CLICK ROW → FILL FORM
rows.forEach(row => {
    row.addEventListener("click", function () {

        rows.forEach(r => r.classList.remove("selected"));
        this.classList.add("selected");

        document.getElementById("unit_id").value = this.dataset.id;
        document.getElementById("unit_type").value = this.dataset.type;
        document.getElementById("floor_level").value = this.dataset.floor;
        document.getElementById("unit_rate").value = this.dataset.rate;
        document.getElementById("unit_status").value = this.dataset.status;
    });
});

// REFRESH FORM
function refreshForm() {
    document.getElementById("unit_id").value = "";
    document.getElementById("unit_type").value = "";
    document.getElementById("floor_level").value = "";
    document.getElementById("unit_rate").value = "";
    document.getElementById("unit_status").value = "";

    rows.forEach(r => r.classList.remove("selected"));
}
</script>

</body>
</html>