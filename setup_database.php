<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS portofoliophp";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("portofoliophp");

// Create articles table
$sql = "CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table articles created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert sample data (only if table is empty)
$check = $conn->query("SELECT COUNT(*) as count FROM articles");
$row = $check->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO articles (title, content) VALUES 
    ('Sejarah Perbudakan', 'Artikel ini membahas bagaimana sistem perbudakan berkembang sejak zaman Mesir Kuno, Romawi, hingga perdagangan budak di Atlantik dan akhirnya penghapusannya di berbagai negara.'),
    ('Dampak Perbudakan terhadap Dunia Modern', 'Mengulas bagaimana perbudakan membentuk ekonomi, budaya, dan struktur sosial di banyak negara serta dampaknya yang masih terasa hingga kini.')";

    if ($conn->query($sql) === TRUE) {
        echo "Sample data inserted successfully<br>";
    } else {
        echo "Error inserting sample data: " . $conn->error . "<br>";
    }
}

$conn->close();

echo "Database setup complete. <a href='index.php'>Go to homepage</a>";
?>
