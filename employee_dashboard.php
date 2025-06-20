<?php
session_start();
include "db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'employee') {
    die("Access denied. Please log in as employee.");
}

$uid = $_SESSION['user']['id'];
$name = $_SESSION['user']['name'];

// Sample total leave values (you can store in DB later)
$totalLeaves = array(
    'CL' => 12,
    'EL' => 21,
    'ML' => 10,
    'PL' => 15
);

// Count used leaves
$usedLeaves = array('CL' => 0, 'EL' => 0, 'ML' => 0, 'PL' => 0);

$leaveRes = $conn->query("SELECT type, DATEDIFF(end_date, start_date) + 1 as days FROM leave_applications WHERE user_id=$uid AND status='Approved'");
while ($row = $leaveRes->fetch_assoc()) {
    $usedLeaves[$row['type']] += $row['days'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Employee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background: linear-gradient(to right, #e0f2f1, #d1c4e9, #c2e9fb);
    background-size: 300% 300%;
    animation: pastelFlow 12s ease infinite;
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
  }

  @keyframes pastelFlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }
</style>

</head>
<body class="bg-light">

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">🏢 Leave System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active fw-bold text-primary" href="employee_dashboard.php">🏠 Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="apply_leave.php">➕ Apply Leave</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="my_history.php">📄 My History</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">🚪 Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ WELCOME -->
<div class="container mb-4">
  <div class="bg-primary text-white p-4 rounded shadow">
    <h4>Welcome Back, <?= $name ?></h4>
    <p class="mb-0">Employee ID: EMP<?= str_pad($uid, 3, '0', STR_PAD_LEFT) ?> | Department: Software Development</p>
  </div>
</div>

<!-- ✅ LEAVE BALANCE CARDS -->
<div class="container mb-4">
  <div class="row g-3">
    <?php foreach ($totalLeaves as $type => $total): 
      $used = $usedLeaves[$type];
      $remaining = $total - $used;
    ?>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-1">
            <strong>
              <?= ($type == 'CL' ? 'Casual' : ($type == 'EL' ? 'Earned' : ($type == 'ML' ? 'Medical' : 'Paid'))) ?> Leave
            </strong>
            <span class="badge bg-light text-dark"><?= $type ?></span>
          </div>
          <p class="mb-1">Remaining: <strong><?= $remaining ?></strong></p>
          <small class="text-muted">Used: <?= $used ?> days this year</small>
          <div class="progress mt-2" style="height: 6px;">
            <div class="progress-bar bg-dark" role="progressbar" style="width: <?= ($used/$total)*100 ?>%"></div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ✅ RECENT APPLICATIONS -->
<div class="container mb-5">
  <h5 class="mb-3">📌 Recent Leave Applications</h5>
  <div class="list-group shadow-sm">
    <?php
    $apps = $conn->query("SELECT * FROM leave_applications WHERE user_id=$uid ORDER BY id DESC LIMIT 5");
    if ($apps->num_rows == 0) {
      echo "<div class='list-group-item text-muted'>No leave applications found.</div>";
    } else {
      while ($row = $apps->fetch_assoc()) {
        $days = (new DateTime($row['start_date']))->diff(new DateTime($row['end_date']))->days + 1;
        $type = $row['type'];
        $status = $row['status'];
        $dateText = $row['start_date'];
        if ($days > 1) {
          $dateText = $row['start_date'] . " to " . $row['end_date'];
        }
        $badge = $status == 'Approved' ? 'success' : ($status == 'Rejected' ? 'danger' : 'warning');
        $icon = $status == 'Approved' ? '✔️' : ($status == 'Rejected' ? '❌' : '⏳');
        echo "<div class='list-group-item d-flex justify-content-between align-items-center'>
          <div>
            <strong>$type</strong> - $days day" . ($days > 1 ? "s" : "") . "<br>
            <small class='text-muted'>$dateText</small>
          </div>
          <span class='badge bg-$badge'>$icon $status</span>
        </div>";
      }
    }
    ?>
  </div>
</div>

</body>
</html>
