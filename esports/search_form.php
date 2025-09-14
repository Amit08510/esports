<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin_login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search - UK E-Sports League Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">E-Sports League Admin</span>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin_menu.php">Back to Admin Menu</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h2 class="card-title mb-0">Search for Participants or Teams</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">Search Individual Participant</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="search_result.php" method="POST">
                                            <div class="mb-3">
                                                <label for="firstname_surname" class="form-label">Participant First Name or Surname</label>
                                                <input type="text" class="form-control" id="firstname_surname" name="firstname_surname" required>
                                            </div>
                                            <input type="hidden" name="participant" value="1">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Search Participant</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h4 class="mb-0">Search Team</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="search_result.php" method="POST">
                                            <div class="mb-3">
                                                <label for="team" class="form-label">Team Name</label>
                                                <input type="text" class="form-control" id="team" name="team" required>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success">Search Team</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>