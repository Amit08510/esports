<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Participant - UK E-Sports League Admin</title>
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
                    <?php
                    session_start();

                    // Check if user is logged in
                    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                        header("Location: admin_login.html");
                        exit();
                    }
                       
                    include 'dbconnect.php';

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Check if participant ID is provided
                        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                            $participant_id = $_GET['id'];
                            
                            // First, get participant details for confirmation
                            $select_stmt = $conn->prepare("SELECT firstname, surname FROM participant WHERE id = :id");
                            $select_stmt->bindParam(':id', $participant_id);
                            $select_stmt->execute();
                            
                            $participant = $select_stmt->fetch(PDO::FETCH_ASSOC);
                            
                            if ($participant) {
                                // Delete the participant
                                $delete_stmt = $conn->prepare("DELETE FROM participant WHERE id = :id");
                                $delete_stmt->bindParam(':id', $participant_id);
                                
                                if ($delete_stmt->execute()) {
                                    echo "<div class='card-header bg-success text-white text-center'>";
                                    echo "<h3 class='card-title mb-0'>Delete Successful</h3>";
                                    echo "</div>";
                                    echo "<div class='card-body text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<i class='text-success' style='font-size: 4rem;'>✓</i>";
                                    echo "</div>";
                                    echo "<h5 class='card-title text-success'>Participant Deleted Successfully</h5>";
                                    echo "<div class='alert alert-success'>";
                                    echo "<p><strong>Participant " . htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']) . " has been deleted from the database.</strong></p>";
                                    echo "</div>";
                                    echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='card-header bg-danger text-white text-center'>";
                                    echo "<h3 class='card-title mb-0'>Delete Failed</h3>";
                                    echo "</div>";
                                    echo "<div class='card-body text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<i class='text-danger' style='font-size: 4rem;'>✗</i>";
                                    echo "</div>";
                                    echo "<h5 class='card-title text-danger'>Delete Unsuccessful</h5>";
                                    echo "<p class='card-text'>There was an error deleting the participant.</p>";
                                    echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<div class='card-header bg-warning text-dark text-center'>";
                                echo "<h3 class='card-title mb-0'>Error</h3>";
                                echo "</div>";
                                echo "<div class='card-body text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<i class='text-warning' style='font-size: 4rem;'>⚠</i>";
                                echo "</div>";
                                echo "<h5 class='card-title text-warning'>Participant Not Found</h5>";
                                echo "<p class='card-text'>The requested participant could not be found in the database.</p>";
                                echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='card-header bg-warning text-dark text-center'>";
                            echo "<h3 class='card-title mb-0'>Error</h3>";
                            echo "</div>";
                            echo "<div class='card-body text-center'>";
                            echo "<div class='mb-4'>";
                            echo "<i class='text-warning' style='font-size: 4rem;'>⚠</i>";
                            echo "</div>";
                            echo "<h5 class='card-title text-warning'>Invalid Request</h5>";
                            echo "<p class='card-text'>No valid participant ID was provided.</p>";
                            echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                            echo "</div>";
                        }
                        
                    }
                    catch(PDOException $e) {
                        echo "<div class='card-header bg-danger text-white text-center'>";
                        echo "<h3 class='card-title mb-0'>Database Error</h3>";
                        echo "</div>";
                        echo "<div class='card-body text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='text-danger' style='font-size: 4rem;'>⚠</i>";
                        echo "</div>";
                        echo "<h5 class='card-title text-danger'>Connection Error</h5>";
                        echo "<p class='card-text'>There was an error connecting to the database.</p>";
                        echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                        echo "</div>";
                    }
                    ?>
                    <div class="card-footer text-center text-muted">
                        <small>UK E-Sports League Web Portal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>