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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <span class="text-xl font-bold">E-Sports League Admin</span>
            <div class="flex space-x-4">
                <a class="hover:text-blue-300 transition-colors" href="admin_menu.php">Back to Admin Menu</a>
                <a class="hover:text-blue-300 transition-colors" href="search_form.php">New Search</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 text-white py-4 text-center">
                <h2 class="text-2xl font-bold">Search Results</h2>
            </div>
            <div class="p-6">
                <?php
                include 'dbconnect.php';
                
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Check which form has been posted
                    if (isset($_POST['participant']) && $_POST['participant'] == "1") {
                        // Search for individual participants
                        $search_term = $_POST['firstname_surname'];
                        
                        echo "<div class='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6'>";
                        echo "<h4 class='font-bold text-lg'>Participant Search Results</h4>";
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
                            echo "<div class='overflow-x-auto'>";
                            echo "<table class='min-w-full divide-y divide-gray-200'>";
                            echo "<thead class='bg-gray-800 text-white'>";
                            echo "<tr>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>ID</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>First Name</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Surname</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Email</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Kills</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Deaths</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>K/D Ratio</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Team</th>
                                    <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Location</th>
                                  </tr>";
                            echo "</thead>";
                            echo "<tbody class='bg-white divide-y divide-gray-200'>";
                            
                            foreach ($results as $row) {
                                $kd_ratio = ($row['deaths'] > 0) ? round($row['kills'] / $row['deaths'], 2) : $row['kills'];
                                echo "<tr class='hover:bg-gray-50'>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row['id'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['firstname']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['surname']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-600 rounded-full'>" . $row['kills'] . "</span></td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full'>" . $row['deaths'] . "</span></td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full'>" . $kd_ratio . "</span></td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['team_name']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['team_location']) . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4'>";
                            echo "<h4 class='font-bold text-lg'>No Results Found</h4>";
                            echo "<p>No participants found matching your search criteria.</p>";
                            echo "</div>";
                        }
                    }
                    else {
                        // Search for teams
                        $team_search = $_POST['team'];
                        
                        echo "<div class='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6'>";
                        echo "<h4 class='font-bold text-lg'>Team Search Results</h4>";
                        echo "<p><strong>Searching for team:</strong> " . htmlspecialchars($team_search) . "</p>";
                        echo "</div>";
                        
                        $stmt = $conn->prepare("SELECT * FROM team WHERE name LIKE :search");
                        $search_param = '%' . $team_search . '%';
                        $stmt->bindParam(':search', $search_param);
                        $stmt->execute();
                        
                        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($teams) > 0) {
                            foreach ($teams as $team) {
                                echo "<div class='bg-white border border-green-400 rounded-lg mb-6 overflow-hidden'>";
                                echo "<div class='bg-green-600 text-white px-6 py-3'>";
                                echo "<h3 class='text-xl font-bold'>Team: " . htmlspecialchars($team['name']) . " <span class='text-sm font-normal'>(" . htmlspecialchars($team['location']) . ")</span></h3>";
                                echo "</div>";
                                echo "<div class='p-6'>";
                                
                                // Get team members and their stats
                                $member_stmt = $conn->prepare("SELECT * FROM participant WHERE team_id = :team_id");
                                $member_stmt->bindParam(':team_id', $team['id']);
                                $member_stmt->execute();
                                
                                $members = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                if (count($members) > 0) {
                                    $total_kills = 0;
                                    $total_deaths = 0;
                                    
                                    echo "<div class='overflow-x-auto'>";
                                    echo "<table class='min-w-full divide-y divide-gray-200'>";
                                    echo "<thead class='bg-gray-200'>";
                                    echo "<tr>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>First Name</th>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>Surname</th>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>Email</th>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>Kills</th>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>Deaths</th>
                                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider'>K/D Ratio</th>
                                          </tr>";
                                    echo "</thead>";
                                    echo "<tbody class='bg-white divide-y divide-gray-200'>";
                                    
                                    foreach ($members as $member) {
                                        $kd_ratio = ($member['deaths'] > 0) ? round($member['kills'] / $member['deaths'], 2) : $member['kills'];
                                        $total_kills += $member['kills'];
                                        $total_deaths += $member['deaths'];
                                        
                                        echo "<tr class='hover:bg-gray-50'>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($member['firstname']) . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($member['surname']) . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($member['email']) . "</td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-600 rounded-full'>" . $member['kills'] . "</span></td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full'>" . $member['deaths'] . "</span></td>";
                                        echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full'>" . $kd_ratio . "</span></td>";
                                        echo "</tr>";
                                    }
                                    
                                    // Team totals
                                    $team_kd_ratio = ($total_deaths > 0) ? round($total_kills / $total_deaths, 2) : $total_kills;
                                    echo "</tbody>";
                                    echo "<tfoot class='bg-gray-800 text-white'>";
                                    echo "<tr>";
                                    echo "<td colspan='3' class='px-6 py-3 font-bold'>TEAM TOTALS</td>";
                                    echo "<td class='px-6 py-3'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none bg-gray-100 text-gray-800 rounded-full'>" . $total_kills . "</span></td>";
                                    echo "<td class='px-6 py-3'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none bg-gray-100 text-gray-800 rounded-full'>" . $total_deaths . "</span></td>";
                                    echo "<td class='px-6 py-3'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none bg-gray-100 text-gray-800 rounded-full'>" . $team_kd_ratio . "</span></td>";
                                    echo "</tr>";
                                    echo "</tfoot>";
                                    echo "</table>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4'>";
                                    echo "<p>No members found for this team.</p>";
                                    echo "</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4'>";
                            echo "<h4 class='font-bold text-lg'>No Results Found</h4>";
                            echo "<p>No teams found matching your search criteria.</p>";
                            echo "</div>";
                        }
                    }
                       
                }
                catch(PDOException $e) {
                    echo "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4'>";
                    echo "<h4 class='font-bold text-lg'>Database Error</h4>";
                    echo "<p>There was an error connecting to the database.</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>