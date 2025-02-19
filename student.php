<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f5e3;
            color: #333;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-custom {
            background-color: #e67e22;
            color: white;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #d35400;
        }
        .table thead {
            background-color: #f39c12;
            color: white;
        }
        .container-box {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #d35400;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>


    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Show Data by Roll Number and Semester</h2>
            <div class="mb-3">
                <label class="form-label">Roll Number:</label>
                <input type="text" name="roll_num" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester:</label>
                <input type="number" name="sem" class="form-control" required>
            </div>
            <input type="submit" name="show_by_roll_sem" value="Show Data" class="btn btn-custom w-100">
        </form>
    </div>
</body>
</html>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_by_roll_sem'])) {
    $roll_num = $_POST['roll_num'];
    $sem = $_POST['sem'];
    show_by_roll_sem($conn, $roll_num, $sem);
}

function show_by_roll_sem($conn, $roll_num, $sem) {
    $sql = "SELECT * FROM marks WHERE st_id = ? AND sem = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $roll_num, $sem);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<div class='container'><h2 class='text-center'>Student Marks</h2><table class='table table-striped table-bordered'><thead><tr><th>Subject</th><th>Assignment 1</th><th>Assignment 2</th><th>Assignment 3</th><th>CIA 1</th><th>CIA 2</th><th>Model</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['sub']}</td><td>{$row['assg1']}</td><td>{$row['assg2']}</td><td>{$row['assg3']}</td><td>{$row['cia1']}</td><td>{$row['cia2']}</td><td>{$row['model']}</td></tr>";
    }
    echo "</tbody></table></div>";
}

$conn->close();
?>