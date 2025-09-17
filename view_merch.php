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
    <title>View Merchandise Registrations - UK E-Sports League Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#8b5cf6',
                        accent: '#f97316',
                        dark: {
                            900: '#0a0a0a',
                            800: '#1a1a1a',
                            700: '#262626',
                            600: '#404040'
                        }
                    }
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-primary {
                @apply bg-primary hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-secondary {
                @apply border border-gray-600 hover:bg-gray-700 text-gray-300 font-medium py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-purple {
                @apply bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
        }
    </style>
</head>
<body class="bg-dark-900 min-h-screen">
    <nav class="bg-dark-800 shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <span class="text-xl font-bold text-white">E-Sports League Admin</span>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    <a href="admin_menu.php" class="btn-secondary inline-flex items-center text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Menu
                    </a>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300 text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-purple-700 to-purple-800 py-5 px-6 text-center">
                <h2 class="text-2xl font-bold text-white">Merchandise Registrations</h2>
                <p class="text-purple-100 text-sm mt-1">View all merchandise registration requests</p>
            </div>
            
            <div class="p-6">
                <?php
                include 'dbconnect.php';
                    
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Select all merchandise registrations
                    $stmt = $conn->prepare("SELECT * FROM merchandise ORDER BY id DESC");
                    $stmt->execute();
                    
                    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($registrations) > 0) {
                        echo '<div class="overflow-x-auto">';
                        echo '<table class="min-w-full bg-dark-700 rounded-lg overflow-hidden">';
                        echo '<thead class="bg-dark-600">';
                        echo '<tr>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">ID</th>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">First Name</th>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">Surname</th>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">Email</th>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">Terms Accepted</th>';
                        echo '<th class="py-3 px-4 text-left text-gray-300 font-semibold">Registration Date</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody class="divide-y divide-gray-600">';
                        
                        foreach ($registrations as $registration) {
                            $terms_status = $registration['terms'] ? 
                                '<span class="bg-green-600 text-green-100 py-1 px-2 rounded text-xs">Yes</span>' : 
                                '<span class="bg-red-600 text-red-100 py-1 px-2 rounded text-xs">No</span>';
                            
                            echo '<tr class="hover:bg-dark-600 transition-colors">';
                            echo '<td class="py-3 px-4 text-gray-300">' . $registration['id'] . '</td>';
                            echo '<td class="py-3 px-4 text-gray-300">' . htmlspecialchars($registration['firstname']) . '</td>';
                            echo '<td class="py-3 px-4 text-gray-300">' . htmlspecialchars($registration['surname']) . '</td>';
                            echo '<td class="py-3 px-4 text-gray-300">' . htmlspecialchars($registration['email']) . '</td>';
                            echo '<td class="py-3 px-4">' . $terms_status . '</td>';
                            echo '<td class="py-3 px-4 text-gray-400 text-sm">Registered</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        
                        // Summary statistics
                        $total_stmt = $conn->prepare("SELECT COUNT(*) as total FROM merchandise");
                        $total_stmt->execute();
                        $total = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
                        
                        $terms_stmt = $conn->prepare("SELECT COUNT(*) as accepted FROM merchandise WHERE terms = 1");
                        $terms_stmt->execute();
                        $accepted = $terms_stmt->fetch(PDO::FETCH_ASSOC)['accepted'];
                        
                        echo '<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">';
                        echo '<div class="bg-dark-700 p-4 rounded-lg">';
                        echo '<h4 class="text-lg font-semibold text-purple-400 mb-2">Total Registrations</h4>';
                        echo '<p class="text-3xl font-bold text-white">' . $total . '</p>';
                        echo '</div>';
                        echo '<div class="bg-dark-700 p-4 rounded-lg">';
                        echo '<h4 class="text-lg font-semibold text-green-400 mb-2">Terms Accepted</h4>';
                        echo '<p class="text-3xl font-bold text-white">' . $accepted . ' <span class="text-sm text-gray-400">(' . round(($accepted/$total)*100, 1) . '%)</span></p>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="text-center py-8">';
                        echo '<i class="fas fa-box-open text-4xl text-gray-500 mb-4"></i>';
                        echo '<h3 class="text-xl font-semibold text-gray-400 mb-2">No Registrations Found</h3>';
                        echo '<p class="text-gray-500">No merchandise registrations have been submitted yet.</p>';
                        echo '</div>';
                    }
                    
                } catch(PDOException $e) {
                    echo '<div class="bg-red-900/30 border border-red-800 rounded-lg p-6 text-center">';
                    echo '<i class="fas fa-database text-red-500 text-4xl mb-4"></i>';
                    echo '<h3 class="text-xl font-semibold text-red-400 mb-2">Database Error</h3>';
                    echo '<p class="text-red-300">There was an error connecting to the database.</p>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="bg-dark-700 px-6 py-4 text-center">
                <a href="admin_menu.php" class="btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Admin Menu
                </a>
            </div>
        </div>
    </div>
</body>
</html>