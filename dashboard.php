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
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();
$user_id = $user['id'];

// Hapus task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: dashboard.php");
}

// Ambil semua task user
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daily List - Dashboard</title>
    <style>
        body {
            background-color: #061946;
            color: white;
            font-family: sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            background-color: #777;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }

        th {
            background-color: #061946;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .edit {
            background-color: #FFD700;
        }

        .delete {
            background-color: #FF0000;
            color: white;
        }

        .tambah {
            padding: 8px 16px;
            background-color: #00ccff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .logo {
            font-size: 24px;
            color: #00ffcc;
        }
    </style>
</head>

<body>

    <div class="logo"><b>DAILY LIST</b></div>
    <h2>Selamat datang, <?= htmlspecialchars($username) ?>!</h2>

    <p><a href="tambah.php" class="tambah">+ Tambah Tugas</a></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Dekripsi</th>
                <th>Status</th>
                <th>Prioritas</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($task = $tasks->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= $task['status'] ?></td>
                    <td><?= $task['priority'] ?></td>
                    <td><?= $task['start_date'] ?></td>
                    <td><?= $task['end_date'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $task['id'] ?>" class="btn edit">Edit</a>
                        <a href="?delete=<?= $task['id'] ?>" class="btn delete" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


    <p><a href="logout.php" style="color:red;">Logout</a></p>
</body>

</html>