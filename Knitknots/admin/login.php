<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (isAdminLoggedIn()) {
    header("Location: " . SITE_URL . "/admin/index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                header("Location: " . SITE_URL . "/admin/index.php");
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | KnitKnots Studio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap');
        :root {
            --rose: #D4A373;
            --sage: #CCD5AE;
            --brown: #4A3728;
            --cream: #FAEDCD;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #FEFAE0 0%, #FAEDCD 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            flex-direction: column;
        }
        .login-box {
            background: white;
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 15px;
            outline: none;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--brown);
            color: white;
            border: none;
            border-radius: 15px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .alert {
            background: #f8d7da;
            color: #842029;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div style="font-size: 3rem; color: var(--brown); margin-bottom: 20px;">
        <i class="fas fa-lock"></i>
    </div>
    <h1 style="font-family: 'Playfair Display', serif; color: var(--brown); margin-bottom: 5px; font-size: 2.5rem;">Knit<span style="color: var(--rose);">Knots</span></h1>
    <p style="color: #999; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; margin-bottom: 30px;">Admin Access</p>

    <?php if ($error): ?>
        <div class="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label style="display: block; margin-bottom: 5px; color: #666; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">Username</label>
            <input type="text" name="username" class="form-control" required autofocus placeholder="Admin ID">
        </div>

        <div class="form-group">
            <label style="display: block; margin-bottom: 5px; color: #666; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn-login">Secure Login</button>
    </form>
    
    <div style="margin-top: 30px;">
        <a href="<?php echo SITE_URL; ?>" style="color: #999; text-decoration: none; font-size: 0.9rem;"><i class="fas fa-arrow-left"></i> Back to Public Website</a>
    </div>
</div>

<p style="color: #999; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 30px;">
    &copy; <?php echo date('Y'); ?> KnitKnots Studio
</p>

</body>
</html>