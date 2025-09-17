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
    <title>Add New Team - UK E-Sports League Admin</title>
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
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php
                include 'dbconnect.php';

                try {
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Process form submission
                        
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Get form data
                        $team_name = trim($_POST['team_name']);
                        $location = trim($_POST['location']);
                        
                        // Validate input
                        if (!empty($team_name) && !empty($location)) {
                            // Check if team name already exists
                            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM team WHERE name = :name");
                            $check_stmt->bindParam(':name', $team_name);
                            $check_stmt->execute();
                            
                            if ($check_stmt->fetchColumn() > 0) {
                                echo "<div class='bg-yellow-500 text-gray-900 py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Team Already Exists</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-yellow-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-yellow-700 mb-4'>Duplicate Team Name</h5>";
                                echo "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4'>";
                                echo "<p><strong>A team with the name '" . htmlspecialchars($team_name) . "' already exists.</strong></p>";
                                echo "<p>Please choose a different team name.</p>";
                                echo "</div>";
                                echo "<a href='add_team.php' class='inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium py-2 px-4 rounded transition-colors'>Go Back and Try Again</a>";
                                echo "</div>";
                            } else {
                                // Insert new team
                                $stmt = $conn->prepare("INSERT INTO team (name, location) VALUES (:name, :location)");
                                $stmt->bindParam(':name', $team_name);
                                $stmt->bindParam(':location', $location);
                                
                                if ($stmt->execute()) {
                                    $team_id = $conn->lastInsertId();
                                    echo "<div class='bg-green-600 text-white py-4 text-center'>";
                                    echo "<h3 class='text-xl font-bold'>Team Added Successfully!</h3>";
                                    echo "</div>";
                                    echo "<div class='p-6 text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<svg class='w-16 h-16 text-green-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>";
                                    echo "</div>";
                                    echo "<h5 class='text-lg font-medium text-green-700 mb-4'>New Team Created</h5>";
                                    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>";
                                    echo "<p><strong>Team '" . htmlspecialchars($team_name) . "' has been successfully added!</strong></p>";
                                    echo "<p><strong>Team ID:</strong> " . $team_id . "</p>";
                                    echo "<p><strong>Location:</strong> " . htmlspecialchars($location) . "</p>";
                                    echo "</div>";
                                    echo "<div class='flex flex-col sm:flex-row gap-2 justify-center'>";
                                    echo "<a href='add_team.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Add Another Team</a>";
                                    echo "<a href='admin_menu.php' class='inline-block bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition-colors'>Back to Admin Menu</a>";
                                    echo "</div>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='bg-red-600 text-white py-4 text-center'>";
                                    echo "<h3 class='text-xl font-bold'>Add Team Failed</h3>";
                                    echo "</div>";
                                    echo "<div class='p-6 text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<svg class='w-16 h-16 text-red-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path></svg>";
                                    echo "</div>";
                                    echo "<h5 class='text-lg font-medium text-red-700 mb-4'>Operation Unsuccessful</h5>";
                                    echo "<p class='mb-4'>There was an error adding the team to the database.</p>";
                                    echo "<a href='add_team.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Try Again</a>";
                                    echo "</div>";
                                }
                            }
                        } else {
                            echo "<div class='bg-yellow-500 text-gray-900 py-4 text-center'>";
                            echo "<h3 class='text-xl font-bold'>Invalid Input</h3>";
                            echo "</div>";
                            echo "<div class='p-6 text-center'>";
                            echo "<div class='mb-4'>";
                            echo "<svg class='w-16 h-16 text-yellow-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                            echo "</div>";
                            echo "<h5 class='text-lg font-medium text-yellow-700 mb-4'>Validation Error</h5>";
                            echo "<p class='mb-4'>Both Team Name and Location are required fields.</p>";
                            echo "<a href='add_team.php' class='inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium py-2 px-4 rounded transition-colors'>Go Back and Try Again</a>";
                            echo "</div>";
                        }
                    }
                    else {
                        // Display the form
                        ?>
                        <div class="bg-blue-600 text-white py-4 text-center">
                            <h3 class="text-xl font-bold">Add New Team</h3>
                        </div>
                        <div class="p-6">
                            <form action="add_team.php" method="POST">
                                <div class="mb-4">
                                    <label for="team_name" class="block text-gray-700 font-medium mb-2">Team Name</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="team_name" name="team_name" required maxlength="100" placeholder="Enter team name (e.g., Lightning Bolts)">
                                </div>
                                <div class="mb-6">
                                    <label for="location" class="block text-gray-700 font-medium mb-2">Location</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="location" name="location" required maxlength="100" placeholder="Enter team location (e.g., Manchester)">
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors flex-1">Add Team</button>
                                    <a href="admin_menu.php" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition-colors text-center">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <?php
                    }
                }
                catch(PDOException $e) {
                    echo "<div class='bg-red-600 text-white py-4 text-center'>";
                    echo "<h3 class='text-xl font-bold'>Database Error</h3>";
                    echo "</div>";
                    echo "<div class='p-6 text-center'>";
                    echo "<div class='mb-4'>";
                    echo "<svg class='w-16 h-16 text-red-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                    echo "</div>";
                    echo "<h5 class='text-lg font-medium text-red-700 mb-4'>Connection Error</h5>";
                    echo "<p class='mb-4'>There was an error connecting to the database.</p>";
                    echo "<a href='admin_menu.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Back to Admin Menu</a>";
                    echo "</div>";
                }
                ?>
                <div class="bg-gray-100 text-gray-600 text-center py-3">
                    <small>UK E-Sports League Web Portal</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>