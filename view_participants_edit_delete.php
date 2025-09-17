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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <span class="text-xl font-bold">E-Sports League Admin</span>
            <div class="flex space-x-4">
                <a class="hover:text-blue-300 transition-colors" href="admin_menu.php">Back to Admin Menu</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-yellow-500 text-gray-900 py-4 text-center">
                <h2 class="text-2xl font-bold">All Participants - Edit or Delete</h2>
            </div>
            <div class="p-6">
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
                                <th class='px-6 py-3 text-left text-xs font-medium uppercase tracking-wider'>Actions</th>
                              </tr>";
                        echo "</thead>";
                        echo "<tbody class='bg-white divide-y divide-gray-200'>";
                        
                        foreach ($participants as $participant) {
                            $kd_ratio = ($participant['deaths'] > 0) ? round($participant['kills'] / $participant['deaths'], 2) : $participant['kills'];
                            
                            echo "<tr class='hover:bg-gray-50'>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $participant['id'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($participant['firstname']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($participant['surname']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($participant['email']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-600 rounded-full'>" . $participant['kills'] . "</span></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full'>" . $participant['deaths'] . "</span></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-600 rounded-full'>" . $kd_ratio . "</span></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($participant['team_name']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($participant['team_location']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>";
                            echo "<a href='edit_participant.php?id=" . $participant['id'] . "' class='inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded mr-2 transition-colors'>Edit</a>";
                            echo "<a href='delete.php?id=" . $participant['id'] . "' class='inline-block bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-1 px-3 rounded transition-colors' onclick='return confirm(\"Are you sure you want to delete this participant?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<div class='bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 text-center'>";
                        echo "<h4 class='font-bold text-lg'>No Participants Found</h4>";
                        echo "<p>No participants found in the database.</p>";
                        echo "</div>";
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