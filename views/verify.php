<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredCode = $_POST['verification_code'];

    if ($enteredCode == $_SESSION['verification_code'] && time() <= $_SESSION['code_expiry']) {
        $tempUser = $_SESSION['temp_user'];

        require_once '../classes/database.php';
        $database = new Database();
        $db = $database->connect();

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':username', $tempUser['username']);
        $stmt->bindParam(':email', $tempUser['email']);
        $stmt->bindParam(':password', $tempUser['password']);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: ../views/users.php?status=success");
            exit();
        } else {
            $errorMessage = "Failed to finalize registration.";
        }
    } else {
        $errorMessage = "Invalid or expired verification code.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
</head>
<body>
    <h1>Verify Your Account</h1>
    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="verification_code">Enter Verification Code:</label>
        <input type="text" name="verification_code" id="verification_code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
