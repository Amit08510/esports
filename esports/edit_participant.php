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
    <title>Update Participants Score - UK E-Sports League Admin</title>
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
                    //including connection variables   
                    include 'dbconnect.php';

                    try {
                        if($_SERVER['REQUEST_METHOD'] == 'POST') {
                            // UPDATE section - user has submitted the form
                            
                            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            // Get form data
                            $id = $_POST['id'];
                            $kills = $_POST['kills'];
                            $deaths = $_POST['deaths'];
                            
                            // Validate input
                            if (is_numeric($kills) && is_numeric($deaths) && $kills >= 0 && $deaths >= 0) {
                                // Update participant scores
                                $stmt = $conn->prepare("UPDATE participant SET kills = :kills, deaths = :deaths WHERE id = :id");
                                $stmt->bindParam(':kills', $kills);
                                $stmt->bindParam(':deaths', $deaths);
                                $stmt->bindParam(':id', $id);
                                
                                if ($stmt->execute()) {
                                    echo "<div class='card-header bg-success text-white text-center'>";
                                    echo "<h3 class='card-title mb-0'>Update Successful!</h3>";
                                    echo "</div>";
                                    echo "<div class='card-body text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<i class='text-success' style='font-size: 4rem;'>✓</i>";
                                    echo "</div>";
                                    echo "<h5 class='card-title text-success'>Scores Updated Successfully</h5>";
                                    echo "<div class='alert alert-success'>";
                                    echo "<p><strong>Participant scores have been updated successfully.</strong></p>";
                                    echo "</div>";
                                    echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='card-header bg-danger text-white text-center'>";
                                    echo "<h3 class='card-title mb-0'>Update Failed</h3>";
                                    echo "</div>";
                                    echo "<div class='card-body text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<i class='text-danger' style='font-size: 4rem;'>✗</i>";
                                    echo "</div>";
                                    echo "<h5 class='card-title text-danger'>Update Unsuccessful</h5>";
                                    echo "<p class='card-text'>There was an error updating the participant scores.</p>";
                                    echo "<a href='view_participants_edit_delete.php' class='btn btn-primary'>Return to Participants List</a>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<div class='card-header bg-warning text-dark text-center'>";
                                echo "<h3 class='card-title mb-0'>Invalid Input</h3>";
                                echo "</div>";
                                echo "<div class='card-body text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<i class='text-warning' style='font-size: 4rem;'>⚠</i>";
                                echo "</div>";
                                echo "<h5 class='card-title text-warning'>Validation Error</h5>";
                                echo "<p class='card-text'>Kills and Deaths must be valid numbers greater than or equal to 0.</p>";
                                echo "<a href='edit_participant.php?id=" . $id . "' class='btn btn-warning'>Go Back and Try Again</a>";
                                echo "</div>";
                            }
                        }
                        else {
                            // SELECT section - display the edit form
                            
                            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Get participant ID from URL
                            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                                $participant_id = $_GET['id'];
                                
                                // Fetch participant data
                                $stmt = $conn->prepare("SELECT * FROM participant WHERE id = :id");
                                $stmt->bindParam(':id', $participant_id);
                                $stmt->execute();
                                
                                $participant = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                                if ($participant) {
                                    // Set variables for the form
                                    $id = $participant['id'];
                                    $firstname = $participant['firstname'];
                                    $surname = $participant['surname'];
                                    $kills = $participant['kills'];
                                    $deaths = $participant['deaths'];
                                    
                                    include "edit_participant_form.php";
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