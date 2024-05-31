<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$database = "travelsignup"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST["fullname"];
    $age = $_POST["age"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];

    // Perform basic validation
    $errors = [];
    if (empty($fullname) || empty($age) || empty($email) || empty($mobile) || empty($password)) {
        $errors[] = "All fields are required";
    }

    // If no errors, proceed with signup process
    if (empty($errors)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO users (fullname, age, email, mobile, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $fullname, $age, $email, $mobile, $hashed_password);

        if ($stmt->execute()) {
            echo "Signup successful!<br>";
            echo "Full Name: $fullname<br>";
            echo "Age: $age<br>";
            echo "Email: $email<br>";
            echo "Mobile Number: $mobile<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If there are errors, display them
        foreach ($errors as $error) {
            echo "$error<br>";
        }
    }
}

// Close database connection
$conn->close();
?>
