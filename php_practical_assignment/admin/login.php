<?php
require '../config.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);

    $admin = $stmt->fetch();

    if ($admin && ($_POST['password'] ?? '') === $admin['password']) {

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];

        header('Location: dashboard.php');
        exit;

    } else {
        $message = 'Invalid admin email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body class="login-page">

<div class="login-container">

    <div class="login-icon">🛍️</div>

    <h1>Admin Login</h1>

    <p class="subtitle">Shopping Inventory System</p>

    <?php if ($message !== ''): ?>
        <div class="alert error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <label>Email Address</label>
        <input
            type="email"
            name="email"
            placeholder="Enter admin email"
            required
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            placeholder="Enter password"
            required
        >

        <button type="submit" class="btn primary full-width">
            Login to Dashboard
        </button>

    </form>

    <a href="../index.php" class="back-link">← Back to User Site</a>

</div>

</body>
</html>