<?php
require 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header('Location: user_dashboard.php');
        exit;

    } else {
        $message = 'Incorrect email or password.';
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Login</title>

    <link rel="stylesheet" href="style.css?v=2">
</head>

<body>

    <h1>Shopping Inventory System</h1>

    <div class="auth-container">

        <h2>User Login</h2>

        <p class="auth-description">
            Login to access your account.
        </p>

        <?php if ($message !== '') { ?>
            <p class="error-message">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php } ?>

        <form method="post" action="">

            <label>Email Address</label>
            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">Login</button>

        </form>

        <div class="auth-links">
            <p>
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>

            <a href="index.php">Back to Home</a>
        </div>

    </div>

</body>
</html>