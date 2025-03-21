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
        .table thead {
            background-color:darkgreen;
            color: white;
        }
        .container-box {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .container-box:hover {
            transform: scale(1.02);
        }
        h2 {
            color: darkgreen;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .container-box {
                padding: 20px;
                max-width: 90%;
            }
            h2 {
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
            h2 {
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
    <div class="container text-center">
        <h1>Student Data Management</h1>
        <a href="marks.php" class="btn btn-custom">Go to Marks Management</a>
    </div>

    <!-- Insert Student Data Form -->
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Insert Student Data</h2>
            <div class="mb-3">
                <label class="form-label">Roll Number:</label>
                <input type="text" name="roll_num" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department:</label>
                <input type="text" name="dept" class="form-control" required>
            </div>
            <input type="submit" name="insert" value="Insert Data" class="btn btn-custom w-100">
        </form>
    </div>

    <!-- Show Combined Data Form -->
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Show Combined Data</h2>
            <input type="submit" name="show_combined_data" value="Show Combined Data" class="btn btn-custom w-100">
        </form>
    </div>

    <!-- Show Data by Roll Number and Semester Form -->
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insert'])) {
    $roll_num = $_POST['roll_num'];
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    
    $sql = "INSERT INTO st_data (roll_num, name, dept) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $roll_num, $name, $dept);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Record inserted successfully</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_combined_data'])) {
    show_combined_data($conn);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_by_roll_sem'])) {
    $roll_num = $_POST['roll_num'];
    $sem = $_POST['sem'];
    show_by_roll_sem($conn, $roll_num, $sem);
}

function show_combined_data($conn) {
    $sql = "SELECT st_data.roll_num, st_data.name, st_data.dept, marks.sem, marks.sub, marks.assg1, marks.assg2, marks.assg3, marks.cia1, marks.cia2, marks.model 
            FROM st_data 
            LEFT JOIN marks ON st_data.roll_num = marks.st_id";
    $result = $conn->query($sql);
    
    echo "<div class='container mt-4'><h2 class='text-center'>Combined Data</h2><table class='table table-striped table-bordered'><thead><tr><th>Roll Number</th><th>Name</th><th>Department</th><th>Semester</th><th>Subject</th><th>Assignment 1</th><th>Assignment 2</th><th>Assignment 3</th><th>CIA 1</th><th>CIA 2</th><th>Model</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['roll_num']}</td><td>{$row['name']}</td><td>{$row['dept']}</td><td>{$row['sem']}</td><td>{$row['sub']}</td><td>{$row['assg1']}</td><td>{$row['assg2']}</td><td>{$row['assg3']}</td><td>{$row['cia1']}</td><td>{$row['cia2']}</td><td>{$row['model']}</td></tr>";
    }
    echo "</tbody></table></div>";
}

function show_by_roll_sem($conn, $roll_num, $sem) {
    $sql = "SELECT * FROM marks WHERE st_id = ? AND sem = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $roll_num, $sem);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<div class='container mt-4'><h2 class='text-center'>Student Marks</h2><table class='table table-striped table-bordered'><thead><tr><th>Subject</th><th>Assignment 1</th><th>Assignment 2</th><th>Assignment 3</th><th>CIA 1</th><th>CIA 2</th><th>Model</th></tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['sub']}</td><td>{$row['assg1']}</td><td>{$row['assg2']}</td><td>{$row['assg3']}</td><td>{$row['cia1']}</td><td>{$row['cia2']}</td><td>{$row['model']}</td></tr>";
    }
    echo "</tbody></table></div>";
}

$conn->close();
?>
