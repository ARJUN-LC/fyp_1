<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #FFE259, #FFA751);
            color: #333;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
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
        .table thead {
            background-color: #f39c12;
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
            color: #d35400;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <!-- Show Combined Data -->
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Show Combined Data</h2>
            <input type="submit" name="show_combined_data" value="Show All Data" class="btn btn-custom w-100">
        </form>
    </div>

    <!-- Show Data by Roll Number and Semester -->
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Show Data by Roll Number & Semester</h2>
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

    <!-- Show Data by Department -->
    <div class="container-box mt-4">
        <form method="post" action="">
            <h2>Show Data by Department</h2>
            <div class="mb-3">
                <label class="form-label">Department:</label>
                <input type="text" name="dept" class="form-control" required>
            </div>
            <input type="submit" name="show_by_dept" value="Show Results" class="btn btn-custom w-100">
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

// Show Combined Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_combined_data'])) {
    show_combined_data($conn);
}

// Show Data by Roll Number and Semester
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_by_roll_sem'])) {
    $roll_num = $_POST['roll_num'];
    $sem = $_POST['sem'];
    show_by_roll_sem($conn, $roll_num, $sem);
}

// Show Data by Department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_by_dept'])) {
    $dept = $_POST['dept'];
    show_by_dept($conn, $dept);
}

function show_combined_data($conn) {
    $sql = "SELECT st_data.roll_num, st_data.name, st_data.dept, marks.sem, marks.sub, marks.assg1, marks.assg2, marks.assg3, marks.cia1, marks.cia2, marks.model 
            FROM st_data 
            LEFT JOIN marks ON st_data.roll_num = marks.st_id";
    $result = $conn->query($sql);
    
    display_table($result, "All Students' Data");
}

function show_by_roll_sem($conn, $roll_num, $sem) {
    $sql = "SELECT st_data.roll_num, st_data.name, marks.sem, marks.sub, 
                   marks.assg1, marks.assg2, marks.assg3, marks.cia1, marks.cia2, marks.model
            FROM marks 
            JOIN st_data ON marks.st_id = st_data.roll_num
            WHERE marks.st_id = ? AND marks.sem = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $roll_num, $sem);
    $stmt->execute();
    $result = $stmt->get_result();
    
    display_table($result, "Results for Roll Number: $roll_num, Semester: $sem");
}

function show_by_dept($conn, $dept) {
    $sql = "SELECT st_data.roll_num, st_data.name, st_data.dept, marks.sem, marks.sub, 
                   marks.assg1, marks.assg2, marks.assg3, marks.cia1, marks.cia2, marks.model
            FROM st_data
            LEFT JOIN marks ON st_data.roll_num = marks.st_id
            WHERE st_data.dept = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dept);
    $stmt->execute();
    $result = $stmt->get_result();
    
    display_table($result, "Results for Department: $dept");
}

function display_table($result, $title) {
    if ($result->num_rows > 0) {
        echo "<div class='container mt-4'>
                <h2 class='text-center'>$title</h2>
                <table class='table table-striped table-bordered'>
                    <thead>
                        <tr>
                            <th>Roll Number</th>
                            <th>Name</th>
                            <th>Semester</th>
                            <th>Subject</th>
                            <th>Assignment 1</th>
                            <th>Assignment 2</th>
                            <th>Assignment 3</th>
                            <th>CIA 1</th>
                            <th>CIA 2</th>
                            <th>Model</th>
                        </tr>
                    </thead>
                    <tbody>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['roll_num']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['sem']}</td>
                    <td>{$row['sub']}</td>
                    <td>{$row['assg1']}</td>
                    <td>{$row['assg2']}</td>
                    <td>{$row['assg3']}</td>
                    <td>{$row['cia1']}</td>
                    <td>{$row['cia2']}</td>
                    <td>{$row['model']}</td>
                  </tr>";
        }
        
        echo "</tbody></table></div>";
    } else {
        echo "<h2 class='text-center text-danger'>$title - No records found</h2>";
    }
}

$conn->close();
?>
