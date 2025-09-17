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
    <title>Add New Participant - UK E-Sports League Admin</title>
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
                        $firstname = trim($_POST['firstname']);
                        $surname = trim($_POST['surname']);
                        $email = trim($_POST['email']);
                        $kills = $_POST['kills'];
                        $deaths = $_POST['deaths'];
                        $team_id = $_POST['team_id'];
                        
                        // Validate input
                        if (!empty($firstname) && !empty($surname) && !empty($email) && 
                            is_numeric($kills) && is_numeric($deaths) && $kills >= 0 && $deaths >= 0) {
                            
                            // Check if email already exists
                            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM participant WHERE email = :email");
                            $check_stmt->bindParam(':email', $email);
                            $check_stmt->execute();
                            
                            if ($check_stmt->fetchColumn() > 0) {
                                echo "<div class='bg-yellow-500 text-gray-900 py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Participant Already Exists</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-yellow-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-yellow-700 mb-4'>Duplicate Email Address</h5>";
                                echo "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4'>";
                                echo "<p><strong>A participant with the email '" . htmlspecialchars($email) . "' already exists.</strong></p>";
                                echo "<p>Please use a different email address.</p>";
                                echo "</div>";
                                echo "<a href='add_participant.php' class='inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium py-2 px-4 rounded transition-colors'>Go Back and Try Again</a>";
                                echo "</div>";
                            } else {
                                // Set team_id to NULL if no team is selected
                                if (empty($team_id)) {
                                    $team_id = NULL;
                                }
                                
                                // Insert new participant
                                $stmt = $conn->prepare("INSERT INTO participant (firstname, surname, email, kills, deaths, team_id) VALUES (:firstname, :surname, :email, :kills, :deaths, :team_id)");
                                $stmt->bindParam(':firstname', $firstname);
                                $stmt->bindParam(':surname', $surname);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':kills', $kills);
                                $stmt->bindParam(':deaths', $deaths);
                                $stmt->bindParam(':team_id', $team_id);
                                
                                if ($stmt->execute()) {
                                    $participant_id = $conn->lastInsertId();
                                    $kd_ratio = ($deaths > 0) ? round($kills / $deaths, 2) : $kills;
                                    
                                    // Get team name if assigned
                                    $team_name = "No team assigned";
                                    if ($team_id) {
                                        $team_stmt = $conn->prepare("SELECT name FROM team WHERE id = :team_id");
                                        $team_stmt->bindParam(':team_id', $team_id);
                                        $team_stmt->execute();
                                        $team_result = $team_stmt->fetch(PDO::FETCH_ASSOC);
                                        if ($team_result) {
                                            $team_name = $team_result['name'];
                                        }
                                    }
                                    
                                    echo "<div class='bg-green-600 text-white py-4 text-center'>";
                                    echo "<h3 class='text-xl font-bold'>Participant Added Successfully!</h3>";
                                    echo "</div>";
                                    echo "<div class='p-6 text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<svg class='w-16 h-16 text-green-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>";
                                    echo "</div>";
                                    echo "<h5 class='text-lg font-medium text-green-700 mb-4'>New Participant Created</h5>";
                                    echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>";
                                    echo "<p><strong>" . htmlspecialchars($firstname . ' ' . $surname) . " has been successfully added!</strong></p>";
                                    echo "<p><strong>Participant ID:</strong> " . $participant_id . "</p>";
                                    echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                                    echo "<p><strong>Kills:</strong> " . $kills . " | <strong>Deaths:</strong> " . $deaths . " | <strong>K/D Ratio:</strong> " . $kd_ratio . "</p>";
                                    echo "<p><strong>Team:</strong> " . htmlspecialchars($team_name) . "</p>";
                                    echo "</div>";
                                    echo "<div class='flex flex-col sm:flex-row gap-2 justify-center'>";
                                    echo "<a href='add_participant.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Add Another Participant</a>";
                                    echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors'>View All Participants</a>";
                                    echo "<a href='admin_menu.php' class='inline-block bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition-colors'>Back to Admin Menu</a>";
                                    echo "</div>";
                                    echo "</div>";
                                } else {
                                    echo "<div class='bg-red-600 text-white py-4 text-center'>";
                                    echo "<h3 class='text-xl font-bold'>Add Participant Failed</h3>";
                                    echo "</div>";
                                    echo "<div class='p-6 text-center'>";
                                    echo "<div class='mb-4'>";
                                    echo "<svg class='w-16 h-16 text-red-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path></svg>";
                                    echo "</div>";
                                    echo "<h5 class='text-lg font-medium text-red-700 mb-4'>Operation Unsuccessful</h5>";
                                    echo "<p class='mb-4'>There was an error adding the participant to the database.</p>";
                                    echo "<a href='add_participant.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Try Again</a>";
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
                            echo "<p class='mb-4'>Please ensure all required fields are filled correctly. Kills and Deaths must be valid numbers greater than or equal to 0.</p>";
                            echo "<a href='add_participant.php' class='inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium py-2 px-4 rounded transition-colors'>Go Back and Try Again</a>";
                            echo "</div>";
                        }
                    }
                    else {
                        // Display the form
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Get all teams for the dropdown
                        $teams_stmt = $conn->prepare("SELECT id, name, location FROM team ORDER BY name");
                        $teams_stmt->execute();
                        $teams = $teams_stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="bg-blue-600 text-white py-4 text-center">
                            <h3 class="text-xl font-bold">Add New Participant</h3>
                        </div>
                        <div class="p-6">
                            <form action="add_participant.php" method="POST">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="firstname" class="block text-gray-700 font-medium mb-2">First Name *</label>
                                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               id="firstname" name="firstname" required maxlength="50" placeholder="Enter first name">
                                    </div>
                                    <div>
                                        <label for="surname" class="block text-gray-700 font-medium mb-2">Surname *</label>
                                        <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               id="surname" name="surname" required maxlength="50" placeholder="Enter surname">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="block text-gray-700 font-medium mb-2">Email Address *</label>
                                    <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           id="email" name="email" required maxlength="100" placeholder="Enter email address">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="kills" class="block text-gray-700 font-medium mb-2">Kills</label>
                                        <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               id="kills" name="kills" min="0" step="0.01" value="0" required>
                                    </div>
                                    <div>
                                        <label for="deaths" class="block text-gray-700 font-medium mb-2">Deaths</label>
                                        <input type="number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                               id="deaths" name="deaths" min="0" step="0.01" value="0" required>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <label for="team_id" class="block text-gray-700 font-medium mb-2">Assign to Team (Optional)</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                            id="team_id" name="team_id">
                                        <option value="">-- No team assigned --</option>
                                        <?php
                                        foreach ($teams as $team) {
                                            echo "<option value='" . $team['id'] . "'>" . htmlspecialchars($team['name']) . " (" . htmlspecialchars($team['location']) . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors flex-1">Add Participant</button>
                                    <a href="admin_menu.php" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded transition-colors text-center">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <div class="bg-gray-100 text-gray-600 text-center py-3">
                            <small>* Required fields</small>
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