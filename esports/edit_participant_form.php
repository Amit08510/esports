<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Participant Scores - UK E-Sports League</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">E-Sports League Admin</span>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="view_participants_edit_delete.php">Back to Participants List</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="card-title mb-0">Edit Participant: <?php echo htmlspecialchars($firstname . ' ' . $surname); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="edit_participant.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" disabled value="<?php echo htmlspecialchars($firstname); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Surname</label>
                                    <input type="text" class="form-control" id="surname" name="surname" disabled value="<?php echo htmlspecialchars($surname); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kills" class="form-label">Kills</label>
                                    <input type="number" class="form-control" id="kills" name="kills" min="0" step="0.01" value="<?php echo $kills; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="deaths" class="form-label">Deaths</label>
                                    <input type="number" class="form-control" id="deaths" name="deaths" min="0" step="0.01" value="<?php echo $deaths; ?>" required>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">Update Player Scores</button>
                                <a href="view_participants_edit_delete.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>