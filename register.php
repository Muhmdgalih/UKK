<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        $error = "Email sudah terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            background: #666;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #001f4d;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 10px #00f;
            width: 320px;
            text-align: center;
            color: white;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            background-color: red;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        a {
            color: red;
            text-decoration: none;
        }

        p {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login</a></p>
    </div>
</body>

</html>