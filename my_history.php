<?php
session_start();
include "db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'employee') {
    die("Access denied.");
}

$uid = $_SESSION['user']['id'];
$name = $_SESSION['user']['name'];

// Filter by status if clicked
$statusFilter = isset($_GET['status']) ? $_GET['status'] : "";

// Leave types
$leaveTypes = array(
    'CL' => 'Casual Leave',
    'EL' => 'Earned Leave',
    'ML' => 'Medical Leave',
    'PL' => 'Paid Leave'
);

// Summary stats
$totalApps = $conn->query("SELECT COUNT(*) AS total FROM leave_applications WHERE user_id = $uid")->fetch_assoc()['total'];
$pendingApps = $conn->query("SELECT COUNT(*) FROM leave_applications WHERE user_id = $uid AND status = 'Pending'")->fetch_row()[0];

$daysTaken = 0;
$used = $conn->query("SELECT DATEDIFF(end_date, start_date)+1 as days FROM leave_applications WHERE user_id = $uid AND status = 'Approved'");
while ($row = $used->fetch_assoc()) {
    $daysTaken += $row['days'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>My History</title>
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">🏢 Leave System</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="employee_dashboard.php">🏠 Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="apply_leave.php">➕ Apply Leave</a></li>
        <li class="nav-item"><a class="nav-link active fw-bold text-primary" href="my_history.php">📄 My History</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">🚪 Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- DASHBOARD CARDS (Clickable) -->
<div class="container mb-4">
  <div class="row g-3 text-center">
    <div class="col-md-4">
      <a href="my_history.php" class="text-decoration-none">
        <div class="card bg-white border-start border-4 border-info shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">📋 Total Applications</h6>
            <h3 class="text-dark"><?= $totalApps ?></h3>
            <small class="text-muted">Click to view all</small>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="my_history.php?status=Approved" class="text-decoration-none">
        <div class="card bg-white border-start border-4 border-success shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">✅ Days Taken</h6>
            <h3 class="text-dark"><?= $daysTaken ?></h3>
            <small class="text-muted">Approved leaves only</small>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="my_history.php?status=Pending" class="text-decoration-none">
        <div class="card bg-white border-start border-4 border-warning shadow-sm">
          <div class="card-body">
            <h6 class="text-muted">🕐 Pending Requests</h6>
            <h3 class="text-dark"><?= $pendingApps ?></h3>
            <small class="text-muted">Awaiting review</small>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<!-- TABLE -->
<div class="container mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5>📄 Leave History <?= $statusFilter ? "— Filter: <span class='text-primary'>$statusFilter</span>" : "" ?></h5>
      <p class="text-muted mb-0">Showing your submitted leave requests</p>
    </div>
  </div>

  <div class="table-responsive shadow-sm bg-white rounded">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="text-muted">
          <th>Type</th>
          <th>Period</th>
          <th>Days</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $filterQuery = $statusFilter ? "AND status = '$statusFilter'" : "";
        $res = $conn->query("SELECT * FROM leave_applications WHERE user_id = $uid $filterQuery ORDER BY id DESC");

        if ($res->num_rows == 0) {
          echo "<tr><td colspan='6' class='text-center text-muted'>No leave applications found.</td></tr>";
        } else {
          while ($row = $res->fetch_assoc()) {
            $type = $row['type'];
            $days = (new DateTime($row['start_date']))->diff(new DateTime($row['end_date']))->days + 1;
            $badge = $row['status'] == 'Approved' ? 'success' : ($row['status'] == 'Rejected' ? 'danger' : 'warning');
            echo "
            <tr>
              <td><span class='badge bg-light text-dark border rounded-pill px-3'>{$type}</span></td>
              <td>{$row['start_date']} to {$row['end_date']}</td>
              <td>$days</td>
              <td>{$row['reason']}</td>
              <td><span class='badge bg-$badge'>{$row['status']}</span></td>
              <td><a href='view_leave.php?id={$row['id']}' class='btn btn-sm btn-outline-primary'>🔍 View</a></td>
            </tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
