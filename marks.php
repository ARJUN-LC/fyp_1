<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("<div class='alert alert-danger text-center'>Connection failed: " . $conn->connect_error . "</div>");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert_marks'])) {
    $st_id = $_POST['st_id'];
    $sem = $_POST['sem'];
    $sub = $_POST['sub'];
    $assg1 = $_POST['assg1'];
    $assg2 = $_POST['assg2'];
    $assg3 = $_POST['assg3'];
    $cia1 = $_POST['cia1'];
    $cia2 = $_POST['cia2'];
    $model = $_POST['mode'];

    $sql = "INSERT INTO marks (st_id, sem, sub, assg1, assg2, assg3, cia1, cia2, model) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisiiiiii", $st_id, $sem, $sub, $assg1, $assg2, $assg3, $cia1, $cia2, $model);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>Marks inserted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_marks'])) {
    $roll_num = $_POST['roll_num'];
    $sem = $_POST['sem'];
    $sub = $_POST['sub'];
    
    $sql = "DELETE FROM marks WHERE st_id = ? AND sem = ? AND sub = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $roll_num, $sem, $sub);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>Marks deleted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
$conn->close();
?>

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
        }
    </style>
</head>
<body>
    <div class="container text-center mt-4">
        <h1>Marks Management</h1>
        <a href="db.php" class="btn btn-custom">Back to Student Management</a>
    </div>
    <div class="container text-center mt-4">
        <a href="imc.php" class="btn btn-custom">GO TO IMC</a>
    </div>
    <?= $message ?>
    
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Insert Student Marks</h2>
            <div class="mb-3">
                <label class="form-label">Student ID:</label>
                <input type="text" name="st_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Semester:</label>
                <input type="number" name="sem" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject:</label>
                <input type="text" name="sub" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment 1:</label>
                <input type="number" name="assg1" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment 2:</label>
                <input type="number" name="assg2" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Assignment 3:</label>
                <input type="number" name="assg3" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">CIA 1:</label>
                <input type="number" name="cia1" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">CIA 2:</label>
                <input type="number" name="cia2" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Model:</label>
                <input type="number" name="mode" class="form-control" required>
            </div>
            <input type="submit" name="insert_marks" value="Insert Marks" class="btn btn-custom w-100">
        </form>
    </div>
    
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Delete Student Marks</h2>
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
            <input type="submit" name="delete_marks" value="Delete Marks" class="btn btn-custom w-100">
        </form>
    </div>
</body>
</html>
