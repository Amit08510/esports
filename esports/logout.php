<?php
session_start();

// Destroy all session data
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logout - UK E-Sports League</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="card-title mb-0">Logout Successful</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="text-success" style="font-size: 4rem;">✓</i>
                        </div>
                        <h5 class="card-title text-success">You have been successfully logged out</h5>
                        <p class="card-text text-muted">Thank you for using the UK E-Sports League Admin Portal. Your session has been securely ended.</p>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="index.html" class="btn btn-primary">Return to Home Page</a>
                            <a href="admin_login.html" class="btn btn-outline-secondary">Login Again</a>
                        </div>
                    </div>
                    <div class="card-footer text-center text-muted">
                        <small>UK E-Sports League Web Portal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>