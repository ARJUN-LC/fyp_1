<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAFF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:  rgba(172, 255, 47, 0.625);
            color: #333;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3 {
            color:darkgreen;
        }
        .btn-custom {
            background-color: darkgreen;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-custom:hover {
            background-color: rgba(172, 255, 47, 0.625);
            transform: translateY(-2px);
        }
        .container-box {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .container-box:hover {
            transform: scale(1.02);
        }
        .container-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: green;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
        }
        input[type="submit"] {
            border: none;
        }
        .shadow-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
        }
        .shadow-card h2 {
            color: darkgreen;
        }
        @media (max-width: 768px) {
            .container-box {
                max-width: 90%;
                padding: 20px;
            }
            .btn-custom {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        @media (max-width: 480px) {
            .container-box {
                padding: 15px;
                max-width: 100%;
            }
            .btn-custom {
                font-size: 14px;
                padding: 7px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Internal Mark Calculator</h1>
        <a href="marks.php" class="btn btn-custom">Go to Marks Management</a>
    </div>
    
    <div class="container text-center mt-4">
        <a href="staff.php" class="btn btn-custom">Back to Student Management</a>
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
        echo "<div class='container text-center mt-4 shadow-card'><h2>Student: $student_name</h2><h3>Subject: $sub</h3><h2>Internal Marks: $internal_marks</h2></div>";
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
