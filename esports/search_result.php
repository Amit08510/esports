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
    <title>Search Results - UK E-Sports League Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">E-Sports League Admin</span>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="admin_menu.php">Back to Admin Menu</a>
                <a class="nav-link" href="search_form.php">New Search</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white text-center">
                <h2 class="card-title mb-0">Search Results</h2>
            </div>
            <div class="card-body">
                <?php
                include 'dbconnect.php';
                
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Check which form has been posted
                    if (isset($_POST['participant']) && $_POST['participant'] == "1") {
                        // Search for individual participants
                        $search_term = $_POST['firstname_surname'];
                        
                        echo "<div class='alert alert-info'>";
                        echo "<h4>Participant Search Results</h4>";
                        echo "<p><strong>Searching for:</strong> " . htmlspecialchars($search_term) . "</p>";
                        echo "</div>";
                        
                        $stmt = $conn->prepare("SELECT p.*, t.name as team_name, t.location as team_location 
                                               FROM participant p 
                                               LEFT JOIN team t ON p.team_id = t.id 
                                               WHERE p.firstname LIKE :search OR p.surname LIKE :search");
                        $search_param = '%' . $search_term . '%';
                        $stmt->bindParam(':search', $search_param);
                        $stmt->execute();
                        
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($results) > 0) {
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-striped table-hover'>";
                            echo "<thead class='table-dark'>";
                            echo "<tr><th>ID</th><th>First Name</th><th>Surname</th><th>Email</th><th>Kills</th><th>Deaths</th><th>K/D Ratio</th><th>Team</th><th>Location</th></tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            
                            foreach ($results as $row) {
                                $kd_ratio = ($row['deaths'] > 0) ? round($row['kills'] / $row['deaths'], 2) : $row['kills'];
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td><span class='badge bg-success'>" . $row['kills'] . "</span></td>";
                                echo "<td><span class='badge bg-danger'>" . $row['deaths'] . "</span></td>";
                                echo "<td><span class='badge bg-info'>" . $kd_ratio . "</span></td>";
                                echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['team_location']) . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "<div class='alert alert-warning'>";
                            echo "<h4>No Results Found</h4>";
                            echo "<p>No participants found matching your search criteria.</p>";
                            echo "</div>";
                        }
                    }
                    else {
                        // Search for teams
                        $team_search = $_POST['team'];
                        
                        echo "<div class='alert alert-info'>";
                        echo "<h4>Team Search Results</h4>";
                        echo "<p><strong>Searching for team:</strong> " . htmlspecialchars($team_search) . "</p>";
                        echo "</div>";
                        
                        $stmt = $conn->prepare("SELECT * FROM team WHERE name LIKE :search");
                        $search_param = '%' . $team_search . '%';
                        $stmt->bindParam(':search', $search_param);
                        $stmt->execute();
                        
                        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($teams) > 0) {
                            foreach ($teams as $team) {
                                echo "<div class='card mb-4 border-success'>";
                                echo "<div class='card-header bg-success text-white'>";
                                echo "<h3 class='mb-0'>Team: " . htmlspecialchars($team['name']) . " <small>(" . htmlspecialchars($team['location']) . ")</small></h3>";
                                echo "</div>";
                                echo "<div class='card-body'>";
                                
                                // Get team members and their stats
                                $member_stmt = $conn->prepare("SELECT * FROM participant WHERE team_id = :team_id");
                                $member_stmt->bindParam(':team_id', $team['id']);
                                $member_stmt->execute();
                                
                                $members = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($members) > 0) {
                                    $total_kills = 0;
                                    $total_deaths = 0;
                                    
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-striped'>";
                                    echo "<thead class='table-secondary'>";
                                    echo "<tr><th>First Name</th><th>Surname</th><th>Email</th><th>Kills</th><th>Deaths</th><th>K/D Ratio</th></tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    
                                    foreach ($members as $member) {
                                        $kd_ratio = ($member['deaths'] > 0) ? round($member['kills'] / $member['deaths'], 2) : $member['kills'];
                                        $total_kills += $member['kills'];
                                        $total_deaths += $member['deaths'];
                                        
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($member['firstname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($member['surname']) . "</td>";
                                        echo "<td>" . htmlspecialchars($member['email']) . "</td>";
                                        echo "<td><span class='badge bg-success'>" . $member['kills'] . "</span></td>";
                                        echo "<td><span class='badge bg-danger'>" . $member['deaths'] . "</span></td>";
                                        echo "<td><span class='badge bg-info'>" . $kd_ratio . "</span></td>";
                                        echo "</tr>";
                                    }
                                    
                                    // Team totals
                                    $team_kd_ratio = ($total_deaths > 0) ? round($total_kills / $total_deaths, 2) : $total_kills;
                                    echo "</tbody>";
                                    echo "<tfoot class='table-dark'>";
                                    echo "<tr>";
                                    echo "<td colspan='3'><strong>TEAM TOTALS</strong></td>";
                                    echo "<td><span class='badge bg-light text-dark'>" . $total_kills . "</span></td>";
                                    echo "<td><span class='badge bg-light text-dark'>" . $total_deaths . "</span></td>";
                                    echo "<td><span class='badge bg-light text-dark'>" . $team_kd_ratio . "</span></td>";
                                    echo "</tr>";
                                    echo "</tfoot>";
                                    echo "</table>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='alert alert-warning'>";
                                    echo "<p>No members found for this team.</p>";
                                    echo "</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-warning'>";
                            echo "<h4>No Results Found</h4>";
                            echo "<p>No teams found matching your search criteria.</p>";
                            echo "</div>";
                        }
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