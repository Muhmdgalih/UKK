<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['id'];

// Ambil ID task
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$task_id = $_GET['id'];

// Ambil data task
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();

if (!$task) {
    echo "Tugas tidak ditemukan.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, priority = ?, start_date = ?, end_date = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssii", $title, $desc, $status, $priority, $start, $end, $task_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
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
            background-color: #FFD700;
            color: black;
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

    <h2>Edit Tugas</h2>

    <form method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br>
        <input type="text" name="description" value="<?= htmlspecialchars($task['description']) ?>" required><br>
        <select name="status" required>
            <option value="Belum selesai" <?= $task['status'] == "Belum selesai" ? 'selected' : '' ?>>Belum selesai</option>
            <option value="Selesai" <?= $task['status'] == "Selesai" ? 'selected' : '' ?>>Selesai</option>
        </select><br>
        <select name="priority" required>
            <option value="Prioritas" <?= $task['priority'] == "Prioritas" ? 'selected' : '' ?>>Prioritas</option>
            <option value="Tidak Prioritas" <?= $task['priority'] == "Tidak Prioritas" ? 'selected' : '' ?>>Tidak Prioritas</option>
        </select><br>
        <input type="date" name="start_date" value="<?= $task['start_date'] ?>" required><br>
        <input type="date" name="end_date" value="<?= $task['end_date'] ?>" required><br>
        <button type="submit">Simpan Perubahan</button>
    </form>

    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>

</body>

</html>