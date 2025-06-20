<?php
session_start();
include "db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    die("Access denied. Please login as employee.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Apply for Leave</title>
  <!-- Bootstrap CSS -->
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
  <div class="container mt-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 600px;">
      <h2 class="text-center mb-4">Apply for Leave</h2>

      <!-- Apply Leave Form -->
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Leave Type</label>
          <select name="type" class="form-select" required>
            <option value="CL">Casual Leave</option>
            <option value="EL">Earned Leave</option>
            <option value="ML">Medical Leave</option>
            <option value="PL">Paid Leave</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Start Date</label>
          <input type="date" name="start" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">End Date</label>
          <input type="date" name="end" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control" rows="3" required></textarea>
        </div>

        <div class="d-flex justify-content-between">
          <a href="logout.php" class="btn btn-secondary">Logout</a>
          <button type="submit" name="apply" class="btn btn-success">Apply</button>
        </div>
      </form>

      <!-- Handle Form Submission -->
      <?php
      if (isset($_POST['apply'])) {
          $uid = $_SESSION['user']['id'];
          $type = $_POST['type'];
          $start = $_POST['start'];
          $end = $_POST['end'];
          $reason = $_POST['reason'];

          // Insert leave into DB
          $sql = "INSERT INTO leave_applications (user_id, type, start_date, end_date, reason)
                  VALUES ('$uid', '$type', '$start', '$end', '$reason')";
          if ($conn->query($sql)) {
              echo "<div class='alert alert-success mt-3'>Leave Applied Successfully!</div>";
          } else {
              echo "<div class='alert alert-danger mt-3'>Error: " . $conn->error . "</div>";
          }
      }
      ?>
    </div>
  </div>
</body>
</html>
