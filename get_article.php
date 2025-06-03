<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portofoliophp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT id, title, content FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode($article);
    } else {
        echo json_encode(['error' => 'Article not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}

$conn->close();
?>
