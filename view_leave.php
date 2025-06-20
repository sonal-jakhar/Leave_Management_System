<?php
session_start();
include "db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'employee') {
    die("Access denied.");
}

$id = $_GET['id'];
$uid = $_SESSION['user']['id'];

$res = $conn->query("SELECT * FROM leave_applications WHERE id = $id AND user_id = $uid");
if ($res->num_rows == 0) {
    die("Leave record not found.");
}
$row = $res->fetch_assoc();
$days = (new DateTime($row['start_date']))->diff(new DateTime($row['end_date']))->days + 1;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Leave Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3">📄 Leave Request Details</h4>
      <p><strong>Leave Type:</strong> <?= $row['type'] ?></p>
      <p><strong>From:</strong> <?= $row['start_date'] ?></p>
      <p><strong>To:</strong> <?= $row['end_date'] ?></p>
      <p><strong>Total Days:</strong> <?= $days ?></p>
      <p><strong>Reason:</strong><br><?= nl2br($row['reason']) ?></p>
      <p><strong>Status:</strong> <span class="badge bg-<?= $row['status']=='Approved' ? 'success' : ($row['status']=='Rejected' ? 'danger' : 'warning') ?>">
        <?= $row['status'] ?>
      </span></p>
      <a href="my_history.php" class="btn btn-secondary mt-3">← Back to History</a>
    </div>
  </div>
</div>
</body>
</html>
