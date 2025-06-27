<?php
session_start();
require 'db.php';

$username = $password = "";
$errors = [];

// Check if the user is already logged in via session
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Auto-login if the "Remember me" cookie is set, but ask for password
if (isset($_COOKIE['remember_me'])) {
    // Cookie data: stored_username, stored_hashed_password
    list($stored_username, $stored_password) = explode(",", $_COOKIE['remember_me']);
    
    // Check if the cookie data is valid by querying the database
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $stored_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Prompt the user for their password to complete the login process
            $_SESSION['username'] = $row['username'];
            $username = $row['username'];
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";

    if (empty($errors)) {
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $hashed_password = hash('sha256', $password);

                // Verify password match
                if ($hashed_password === $row['password']) {
                    $_SESSION['username'] = $row['username'];

                    // If "Remember me" is checked, set a cookie with the username and hashed password
                    if (isset($_POST['remember_me'])) {
                        $cookie_value = $username . "," . $hashed_password;
                        setcookie('remember_me', $cookie_value, time() + 3600 * 24 * 30, "/"); // Expires in 30 days
                    }

                    header("Location: index.php");
                    exit();
                } else {
                    $errors[] = "Incorrect password.";
                }
            } else {
                $errors[] = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $con->error;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUP Student Portal - Login</title>
    <style>
        /* Your existing CSS styles go here... */
        body {
            font-family: sans-serif;
            margin: 0;
            background: linear-gradient(to bottom, #800000, #330000);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
            width: 90%;
            max-width: 1200px;
            color: #333;
        }

        .left-side {
            position: relative;
            color: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
            width: 40%;
            box-sizing: border-box;
            overflow: hidden;
            background-color: transparent;
        }

        .gallery-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .gallery-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            filter: grayscale(100%);
            transition: opacity 1s ease-in-out;
        }

        .gallery-image.active {
            opacity: 1;
        }

        .left-side .content {
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.4);
            padding: 20px;
            border-radius: 8px;
        }

        .right-side {
            padding: 40px;
            width: 60%;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            box-sizing: border-box;
        }

        .right-side h1 {
            color: #800000;
            font-size: 28px;
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
        }

        .right-side p {
            color: #555;
            font-size: 16px;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #fff;
            color: #333;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            color: #555;
            cursor: pointer;
        }

        .remember-forgot input {
            margin-right: 5px;
        }

        button {
            background-color: #800000;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #660000;
        }

        .need-help {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .need-help a {
            color: #800000;
            text-decoration: none;
        }

        .need-help a:hover {
            text-decoration: underline;
        }

        .contact-it {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        .error-messages {
            color: #a94442;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 14px;
        }

        .error-messages ul {
            margin: 0;
            padding-left: 20px;
            list-style-type: disc;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-side">
        <div class="gallery-container">
            <img class="gallery-image active" src="img1.jpg" alt="Gallery Image 1">
            <img class="gallery-image" src="img2.jpg" alt="Gallery Image 2">
            <img class="gallery-image" src="img3.jpg" alt="Gallery Image 3">
            <img class="gallery-image" src="img4.jpg" alt="Gallery Image 4">
        </div>
        <div class="content">
            <h2>Welcome back</h2>
            <p>Access your courses, grades, and campus resources through our secure student portal.</p>
        </div>
    </div>
    <div class="right-side">
        <h1>PUP Student Information System</h1>
        <p>Please log in to access your account</p>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="studentId">Student/Admin ID</label>
                <input type="text" id="studentId" name="username" placeholder="Enter your ID" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox" name="remember_me"> Remember me</label>
            </div>
            <button type="submit">Sign in</button>
            <div class="need-help">
                Not registered? <a href="register.php">Create an account</a>
            </div>
            <div class="need-help">
                Need help? <a href="https://www.pup.edu.ph/about/contactus" target="_blank">Visit our support page</a>
            </div>
            <p class="contact-it">Contact IT Support at support@pup.edu.ph</p>
        </form>
    </div>
</div>
<script>
    const galleryImages = document.querySelectorAll('.gallery-image');
    let current = 0;
    setInterval(() => {
        galleryImages[current].classList.remove('active');
        current = (current + 1) % galleryImages.length;
        galleryImages[current].classList.add('active');
    }, 5000);
</script>
</body>
</html>
