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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <span class="text-xl font-bold">E-Sports League Admin</span>
            <div class="flex space-x-4">
                <a class="hover:text-blue-300 transition-colors" href="view_participants_edit_delete.php">Back to Participants List</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                                echo "<div class='bg-green-600 text-white py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Update Successful!</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-green-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-green-700 mb-4'>Scores Updated Successfully</h5>";
                                echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>";
                                echo "<p><strong>Participant scores have been updated successfully.</strong></p>";
                                echo "</div>";
                                echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
                                echo "</div>";
                            } else {
                                echo "<div class='bg-red-600 text-white py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Update Failed</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-red-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-red-700 mb-4'>Update Unsuccessful</h5>";
                                echo "<p class='mb-4'>There was an error updating the participant scores.</p>";
                                echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
                                echo "</div>";
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
                            echo "<p class='mb-4'>Kills and Deaths must be valid numbers greater than or equal to 0.</p>";
                            echo "<a href='edit_participant.php?id=" . $id . "' class='inline-block bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium py-2 px-4 rounded transition-colors'>Go Back and Try Again</a>";
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
                                
                                // Include the form directly instead of using a separate file
                                echo "<div class='bg-blue-600 text-white py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Update Participant Scores</h3>";
                                echo "</div>";
                                echo "<div class='p-6'>";
                                echo "<form method='post' action='edit_participant.php'>";
                                echo "<input type='hidden' name='id' value='" . $id . "'>";
                                
                                echo "<div class='mb-4 p-4 bg-gray-50 rounded-lg'>";
                                echo "<h4 class='text-lg font-medium text-gray-800 mb-2'>Participant Information</h4>";
                                echo "<div class='grid grid-cols-1 md:grid-cols-2 gap-4'>";
                                echo "<div><strong>Name:</strong> " . htmlspecialchars($firstname) . " " . htmlspecialchars($surname) . "</div>";
                                echo "<div><strong>ID:</strong> " . $id . "</div>";
                                echo "</div>";
                                echo "</div>";
                                
                                echo "<div class='mb-4'>";
                                echo "<label for='kills' class='block text-gray-700 font-medium mb-2'>Kills</label>";
                                echo "<input type='number' id='kills' name='kills' value='" . $kills . "' min='0' class='w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500' required>";
                                echo "</div>";
                                
                                echo "<div class='mb-6'>";
                                echo "<label for='deaths' class='block text-gray-700 font-medium mb-2'>Deaths</label>";
                                echo "<input type='number' id='deaths' name='deaths' value='" . $deaths . "' min='0' class='w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500' required>";
                                echo "</div>";
                                
                                echo "<div class='flex justify-between items-center'>";
                                echo "<a href='view_participants_edit_delete.php' class='text-blue-600 hover:text-blue-800 transition-colors'>Cancel</a>";
                                echo "<button type='submit' class='bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Update Scores</button>";
                                echo "</div>";
                                echo "</form>";
                                echo "</div>";
                            } else {
                                echo "<div class='bg-yellow-500 text-gray-900 py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Error</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-yellow-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-yellow-700 mb-4'>Participant Not Found</h5>";
                                echo "<p class='mb-4'>The requested participant could not be found in the database.</p>";
                                echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='bg-yellow-500 text-gray-900 py-4 text-center'>";
                            echo "<h3 class='text-xl font-bold'>Error</h3>";
                            echo "</div>";
                            echo "<div class='p-6 text-center'>";
                            echo "<div class='mb-4'>";
                            echo "<svg class='w-16 h-16 text-yellow-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>";
                            echo "</div>";
                            echo "<h5 class='text-lg font-medium text-yellow-700 mb-4'>Invalid Request</h5>";
                            echo "<p class='mb-4'>No valid participant ID was provided.</p>";
                            echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
                            echo "</div>";
                        }
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
                    echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
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