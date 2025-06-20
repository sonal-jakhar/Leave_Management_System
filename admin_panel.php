<?php
session_start();
include "db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("Access denied.");
}

// Dashboard counts
$pending = $conn->query("SELECT COUNT(*) as count FROM leave_applications WHERE status='Pending'")->fetch_assoc()['count'];
$approved = $conn->query("SELECT COUNT(*) as count FROM leave_applications WHERE status='Approved'")->fetch_assoc()['count'];
$rejected = $conn->query("SELECT COUNT(*) as count FROM leave_applications WHERE status='Rejected'")->fetch_assoc()['count'];
$total = $conn->query("SELECT COUNT(*) as count FROM leave_applications")->fetch_assoc()['count'];

// Status filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$whereClause = $filter ? "WHERE la.status = '$filter'" : '';

// Updated query with employee ID and role
$res = $conn->query("
  SELECT la.*, u.name, u.role, u.id as emp_id
  FROM leave_applications la
  JOIN users u ON la.user_id = u.id
  $whereClause
  ORDER BY la.id DESC
");

// Approve/Reject actions
if (isset($_GET['approve'])) {
    $conn->query("UPDATE leave_applications SET status='Approved' WHERE id={$_GET['approve']}");
    header("Location: admin_panel.php");
    exit;
}
if (isset($_GET['reject'])) {
    $conn->query("UPDATE leave_applications SET status='Rejected' WHERE id={$_GET['reject']}");
    header("Location: admin_panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
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
<body>
  <div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Admin Panel — Leave Management</h2>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- DASHBOARD CARDS -->
    <div class="row text-center mb-4">
      <div class="col-md-3">
        <a href="admin_panel.php?filter=Pending" class="text-decoration-none">
          <div class="card border-start border-4 border-warning shadow-sm bg-white">
            <div class="card-body">
              <h6 class="text-muted">🕐 Pending</h6>
              <h4 class="text-dark"><?= $pending ?></h4>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="admin_panel.php?filter=Approved" class="text-decoration-none">
          <div class="card border-start border-4 border-success shadow-sm bg-white">
            <div class="card-body">
              <h6 class="text-muted">✅ Approved</h6>
              <h4 class="text-dark"><?= $approved ?></h4>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="admin_panel.php?filter=Rejected" class="text-decoration-none">
          <div class="card border-start border-4 border-danger shadow-sm bg-white">
            <div class="card-body">
              <h6 class="text-muted">❌ Rejected</h6>
              <h4 class="text-dark"><?= $rejected ?></h4>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3">
        <a href="admin_panel.php" class="text-decoration-none">
          <div class="card border-start border-4 border-info shadow-sm bg-white">
            <div class="card-body">
              <h6 class="text-muted">📋 Total</h6>
              <h4 class="text-dark"><?= $total ?></h4>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- FILTER FORM -->
    <form method="GET" class="mb-3 d-flex justify-content-end">
      <select name="filter" class="form-select w-auto me-2">
        <option value="">All Status</option>
        <option value="Pending" <?= $filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Approved" <?= $filter == 'Approved' ? 'selected' : '' ?>>Approved</option>
        <option value="Rejected" <?= $filter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
      </select>
      <button class="btn btn-primary">Filter</button>
    </form>

    <!-- APPLICATIONS TABLE -->
    <div class="table-responsive shadow-sm rounded bg-white">
      <table class="table table-bordered table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Employee</th>
            <th>Emp ID</th>
            <th>Role</th>
            <th>Type</th>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $types = array(
            'CL' => 'Casual Leave',
            'EL' => 'Earned Leave',
            'ML' => 'Medical Leave',
            'PL' => 'Paid Leave'
          );

          if ($res->num_rows == 0) {
            echo "<tr><td colspan='10' class='text-center text-muted'>No applications found.</td></tr>";
          } else {
            while ($row = $res->fetch_assoc()) {
              $typeFull = $types[$row['type']] ?? $row['type'];
              $empID = "EMP" . str_pad($row['emp_id'], 3, '0', STR_PAD_LEFT);
              $badge = $row['status'] === 'Approved' ? 'success' : ($row['status'] === 'Rejected' ? 'danger' : 'warning');
              echo "<tr>
                <td>{$row['id']}</td>
                <td><strong>{$row['name']}</strong></td>
                <td>$empID</td>
                <td>" . ucfirst($row['role']) . "</td>
                <td>$typeFull</td>
                <td>{$row['start_date']}</td>
                <td>{$row['end_date']}</td>
                <td>{$row['reason']}</td>
                <td><span class='badge bg-$badge'>{$row['status']}</span></td>
                <td>
                  <a href='admin_panel.php?approve={$row['id']}' class='btn btn-success btn-sm' title='Approve'>&#10004;</a>
                  <a href='admin_panel.php?reject={$row['id']}' class='btn btn-danger btn-sm' title='Reject'>&#10060;</a>
                </td>
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
