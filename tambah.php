<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, status, priority, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $title, $desc, $status, $priority, $start, $end);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <style>
        body {
            background-color: #061946;
            color: white;
            font-family: sans-serif;
            padding: 20px;
        }

        input,
        select {
            padding: 8px;
            margin: 6px 0;
            width: 300px;
        }

        button {
            padding: 10px 16px;
            background-color: #00ccff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        a {
            color: #ff6666;
        }
    </style>
</head>

<body>

    <h2>Tambah Tugas Baru</h2>

    <form method="post">
        <input type="text" name="title" placeholder="Judul" required><br>
        <input type="text" name="description" placeholder="Deskripsi" required><br>
        <select name="status" required>
            <option value="Belum selesai">Belum selesai</option>
            <option value="Selesai">Selesai</option>
        </select><br>
        <select name="priority" required>
            <option value="Prioritas">Prioritas</option>
            <option value="Tidak Prioritas">Tidak Prioritas</option>
        </select><br>
        <input type="date" name="start_date" required><br>
        <input type="date" name="end_date" required><br>
        <button type="submit">Tambah</button>
    </form>

    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>

</body>

</html>