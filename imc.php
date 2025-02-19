<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAFF</title>
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
    <div class="container text-center">
        <h1>Student Data Management</h1>
        <a href="marks.php" class="btn btn-custom">Go to Marks Management</a>
    </div>
    
    <div class="container text-center mt-4">

        <a href="db.php" class="btn btn-custom">Back to Student Management</a>
    </div>

    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Calculate Internal Marks</h2>
            <div class="mb-3">
                <label class="form-label">Roll Number:</label>
                <input type="text" name="roll_num" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester:</label>
                <input type="number" name="sem" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject:</label>
                <input type="text" name="sub" class="form-control" required>
            </div>
            <input type="submit" name="calculate_marks" value="Calculate" class="btn btn-custom w-100">
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate_marks'])) {
    $roll_num = $_POST['roll_num'];
    $sem = $_POST['sem'];
    $sub = $_POST['sub'];

    $sql_student = "SELECT name FROM st_data WHERE roll_num = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("s", $roll_num);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();
    
    $student_name = "Unknown";
    if ($result_student->num_rows > 0) {
        $row_student = $result_student->fetch_assoc();
        $student_name = $row_student['name'];
    }
    $stmt_student->close();

    $sql = "SELECT assg1, assg2, assg3, cia1, cia2, model FROM marks WHERE st_id = ? AND sem = ? AND sub = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $roll_num, $sem, $sub);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $internal_marks = calculate_internal_marks([$row['assg1'], $row['assg2'], $row['assg3']], [$row['cia1'], $row['cia2']], $row['model']);
        echo "<div class='container text-center mt-4'><h2>Student: $student_name</h2><h3>Subject: $sub</h3><h2>Internal Marks: $internal_marks</h2></div>";
    } else {
        echo "<div class='container text-center mt-4'><h2 class='text-danger'>No data found.</h2></div>";
    }
    $stmt->close();
}

function calculate_internal_marks($assignments, $cia, $model) {
    if (count($assignments) === 3 && count($cia) === 2 && isset($model)) {
        $total_assignment_marks = (array_sum($assignments) / 30) * 10;
        $average_internal_marks = (array_sum($cia) / 60) * 20;
        $model_marks = ($model / 50) * 20;
        return number_format($total_assignment_marks + $average_internal_marks + $model_marks, 2);
    } else {
        return "Invalid Data";
    }
}

$conn->close();
?>