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

// Insert Marks Functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert_marks'])) {
    $st_id = $_POST['st_id'];
    $sem = $_POST['sem'];
    $sub = $_POST['sub'];
    $assg1 = $_POST['assg1'];
    $assg2 = $_POST['assg2'];
    $assg3 = $_POST['assg3'];
    $cia1 = $_POST['cia1'];
    $cia2 = $_POST['cia2'];
    $model = $_POST['model'];

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

// Delete Marks Functionality
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
    <title>Marks Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #FFE259, #FFA751);
            color: #333;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        h1, h2 {
            color: #d35400;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .container-box {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .container-box:hover {
            transform: scale(1.02);
        }
        .btn-custom {
            background-color: #e67e22;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-custom:hover {
            background-color: #d35400;
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 10px;
        }
        .alert {
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .container-box {
                padding: 20px;
                max-width: 90%;
            }
            h1, h2 {
                font-size: 24px;
            }
            .btn-custom {
                font-size: 16px;
                padding: 8px 15px;
            }
        }
        @media (max-width: 480px) {
            .container-box {
                padding: 15px;
                max-width: 100%;
            }
            h1, h2 {
                font-size: 22px;
            }
            .btn-custom {
                font-size: 14px;
                padding: 7px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container text-center mt-4">
        <h1>Marks Management</h1>
        <a href="staff.php" class="btn btn-custom">Back to Student Management</a>
    </div>
    <div class="container text-center mt-4">
        <a href="imc.php" class="btn btn-custom">Go to IMC</a>
    </div>
    <?= $message ?>

    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Insert Student Marks</h2>
            <input type="text" name="st_id" class="form-control mb-3" placeholder="Student ID" required>
            <input type="number" name="sem" class="form-control mb-3" placeholder="Semester" required>
            <input type="text" name="sub" class="form-control mb-3" placeholder="Subject" required>
            <input type="number" name="assg1" class="form-control mb-3" placeholder="Assignment 1" required>
            <input type="number" name="assg2" class="form-control mb-3" placeholder="Assignment 2" required>
            <input type="number" name="assg3" class="form-control mb-3" placeholder="Assignment 3" required>
            <input type="number" name="cia1" class="form-control mb-3" placeholder="CIA 1" required>
            <input type="number" name="cia2" class="form-control mb-3" placeholder="CIA 2" required>
            <input type="number" name="model" class="form-control mb-3" placeholder="Model" required>
            <input type="submit" name="insert_marks" value="Insert Marks" class="btn btn-custom w-100">
        </form>
    </div>

    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Delete Student Marks</h2>
            <input type="text" name="roll_num" class="form-control mb-3" placeholder="Roll Number" required>
            <input type="number" name="sem" class="form-control mb-3" placeholder="Semester" required>
            <input type="text" name="sub" class="form-control mb-3" placeholder="Subject" required>
            <input type="submit" name="delete_marks" value="Delete Marks" class="btn btn-custom w-100">
        </form>
    </div>
</body>
</html>
