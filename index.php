<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
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
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4 shadow" style="width: 350px;">
    <h3 class="text-center mb-3">Leave Management</h3>
    <form method="POST" action="login.php">
      <div class="mb-3">
        <label>Email</label>
        <input type="text" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button class="btn btn-primary w-100" type="submit">Login</button>
    </form>
  </div>
</body>
</html>
