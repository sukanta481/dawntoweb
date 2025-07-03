<?php
require_once 'includes/db.php';

// // At the top of register.php
// $result = $conn->query("SELECT COUNT(*) as total FROM admin_users");
// $row = $result->fetch_assoc();
// if($row['total'] > 0) {
//     die("Registration is disabled. Admin already exists.");
// }


$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Simple validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if user/email exists
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed);
            if ($stmt->execute()) {
                $success = "Admin user registered successfully! <a href='login.php'>Login</a>";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin - Dawntoweb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background: #f7f8fa; }
        .register-form { max-width: 420px; margin: 60px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.07);}
    </style>
</head>
<body>
    <div class="register-form">
        <h3 class="text-center mb-4">Register Admin User</h3>
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" required value="<?php echo isset($username)?htmlspecialchars($username):''; ?>">
            </div>
            <div class="form-group">
                <label>Email address</label>
                <input type="email" class="form-control" name="email" required value="<?php echo isset($email)?htmlspecialchars($email):''; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button class="btn btn-success btn-block" type="submit">Register</button>
        </form>
        <div class="mt-3 text-center">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
