<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_activity_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    if (!empty($full_name) && !empty($email) && !empty($age)) {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, age, gender) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $full_name, $email, $age, $gender);

        if ($stmt->execute()) {
            $message = "<p class='success'>Data captured successfully!</p>";
        } else {
            $message = "<p class='error'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='error'>All fields are required!</p>";
    }
}

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFILANDA_Form Capture & Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="form-section">
        <h2>Registration Form</h2>
        <?php echo $message; ?>
        <form method="POST" action="index.php">
            <label>Full Name:</label>
            <input type="text" name="full_name" required placeholder="Enter full name">

            <label>Email:</label>
            <input type="email" name="email" required placeholder="Enter email">

            <label>Age:</label>
            <input type="number" name="age" min="1" max="100" required placeholder="Age">

            <label>Gender:</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <button type="submit">Submit Data</button>
        </form>
    </div>

    <div class="data-section">
        <h2>Database Records (Total: <?php echo $result->num_rows; ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["full_name"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["age"] . "</td>
                                <td>" . $row["gender"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>