<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Participant - UK E-Sports League Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: admin_login.html");
        exit();
    }
    ?>
    
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
                                echo "<div class='bg-green-600 text-white py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Delete Successful</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-green-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-green-700 mb-4'>Participant Deleted Successfully</h5>";
                                echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4'>";
                                echo "<p><strong>Participant " . htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']) . " has been deleted from the database.</strong></p>";
                                echo "</div>";
                                echo "<a href='view_participants_edit_delete.php' class='inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors'>Return to Participants List</a>";
                                echo "</div>";
                            } else {
                                echo "<div class='bg-red-600 text-white py-4 text-center'>";
                                echo "<h3 class='text-xl font-bold'>Delete Failed</h3>";
                                echo "</div>";
                                echo "<div class='p-6 text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<svg class='w-16 h-16 text-red-500 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path></svg>";
                                echo "</div>";
                                echo "<h5 class='text-lg font-medium text-red-700 mb-4'>Delete Unsuccessful</h5>";
                                echo "<p class='mb-4'>There was an error deleting the participant.</p>";
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