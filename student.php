<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #FFE259, #FFA751);
            color: #333;
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3 {
            color: #d35400;
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
            color: #d35400;
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
            color: #e67e22;
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
    
    echo "<div class='container mt-4 shadow-card'>
            <h2 class='text-center'>Student Marks</h2>
            <table class='table table-striped table-bordered'>
                <thead>
                    <tr>
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
}

$conn->close();
?>
