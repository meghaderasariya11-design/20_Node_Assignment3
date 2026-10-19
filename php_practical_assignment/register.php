<?php
require 'config.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (($_POST['captcha'] ?? '') !== ($_SESSION['captcha'] ?? '')) {
        $message = 'CAPTCHA answer is incorrect.';
        $messageType = 'error-message';

    } elseif (
        $name === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($password) < 4
    ) {
        $message = 'Enter a valid name, email, and password of at least 4 characters.';
        $messageType = 'error-message';

    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $message = 'Registration successful! You can now log in.';
            $messageType = 'success-message';

        } catch (PDOException $e) {
            $message = 'This email may already be registered.';
            $messageType = 'error-message';
        }
    }
}

$a = random_int(1, 9);
$b = random_int(1, 9);

$_SESSION['captcha'] = (string) ($a + $b);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>

    <link rel="stylesheet" href="style.css?v=2">
</head>

<body>

    <h1>Shopping Inventory System</h1>

    <div class="auth-container">

        <h2>Create Account</h2>

        <p class="auth-description">
            Register to get started with our shopping system.
        </p>

        <?php if ($message !== '') { ?>
            <div class="<?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php } ?>

        <form method="POST" action="register.php">

            <label for="name">Full Name</label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your full name"
                required
            >

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a password"
                minlength="4"
                required
            >

            <label for="captcha">
                CAPTCHA: What is <?= $a ?> + <?= $b ?>?
            </label>

            <input
                type="number"
                id="captcha"
                name="captcha"
                placeholder="Enter the answer"
                required
            >

            <button type="submit">Register</button>

        </form>

        <div class="auth-links">
            Already have an account?
            <a href="login.php">Login here</a>
        </div>

        <div class="auth-links">
            <a href="index.php">Back to Home</a>
        </div>

    </div>

</body>
</html>