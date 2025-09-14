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
    <title>View Participants - UK E-Sports League Admin</title>
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

    <div class="container-fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark text-center">
                <h2 class="card-title mb-0">All Participants - Edit or Delete</h2>
            </div>
            <div class="card-body">
                <?php
                //including connection variables   
                include 'dbconnect.php';
                    
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Select all participants with their team information
                    $stmt = $conn->prepare("SELECT p.*, t.name as team_name, t.location as team_location 
                                           FROM participant p 
                                           LEFT JOIN team t ON p.team_id = t.id 
                                           ORDER BY p.surname, p.firstname");
                    $stmt->execute();
                    
                    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($participants) > 0) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-striped table-hover'>";
                        echo "<thead class='table-dark'>";
                        echo "<tr><th>ID</th><th>First Name</th><th>Surname</th><th>Email</th><th>Kills</th><th>Deaths</th><th>K/D Ratio</th><th>Team</th><th>Location</th><th>Actions</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        
                        foreach ($participants as $participant) {
                            $kd_ratio = ($participant['deaths'] > 0) ? round($participant['kills'] / $participant['deaths'], 2) : $participant['kills'];
                            
                            echo "<tr>";
                            echo "<td>" . $participant['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($participant['firstname']) . "</td>";
                            echo "<td>" . htmlspecialchars($participant['surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($participant['email']) . "</td>";
                            echo "<td><span class='badge bg-success'>" . $participant['kills'] . "</span></td>";
                            echo "<td><span class='badge bg-danger'>" . $participant['deaths'] . "</span></td>";
                            echo "<td><span class='badge bg-info'>" . $kd_ratio . "</span></td>";
                            echo "<td>" . htmlspecialchars($participant['team_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($participant['team_location']) . "</td>";
                            echo "<td>";
                            echo "<a href='edit_participant.php?id=" . $participant['id'] . "' class='btn btn-sm btn-primary me-1'>Edit</a>";
                            echo "<a href='delete.php?id=" . $participant['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this participant?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-info text-center'>";
                        echo "<h4>No Participants Found</h4>";
                        echo "<p>No participants found in the database.</p>";
                        echo "</div>";
                    }
                    
                }
                catch(PDOException $e) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>Database Error</h4>";
                    echo "<p>There was an error connecting to the database.</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>