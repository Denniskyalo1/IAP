<?php
require_once '../classes/database.php';

if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = "User registered successfully.";
    } elseif ($_GET['status'] === 'failed') {
        $message = "Failed to register user.";
    }
    echo "<script>alert('$message');</script>";

}

$database = new Database();
$db = $database->connect();

$query = "SELECT id, username, email, created_at FROM users";
$stmt = $db->query($query);

echo "<table class='table'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['username']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['created_at']}</td>";
    echo "</tr>";
}

echo "</table>";
?>
