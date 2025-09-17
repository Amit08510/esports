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
    <title>Admin Menu - UK E-Sports League</title>
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
            
            .btn-success {
                @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-warning {
                @apply bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-info {
                @apply bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-purple {
                @apply bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-danger {
                @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
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
                    <a href="logout.php" class="btn-danger inline-flex items-center text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-primary to-blue-800 py-5 px-6 text-center">
                <h2 class="text-2xl font-bold text-white">Admin Dashboard</h2>
                <p class="text-blue-100 text-sm mt-1">UK E-Sports League Management Portal</p>
            </div>
            
            <div class="p-6">
                <!-- First Row - Search and View -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-dark-700 p-6 rounded-lg border border-cyan-600">
                        <div class="text-center">
                            <i class="fas fa-search text-cyan-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-cyan-400 mb-2">Search</h5>
                            <p class="text-gray-400 mb-4">Search for teams or participants in the database</p>
                            <a href="search_form.php" class="btn-info inline-flex items-center justify-center w-full">
                                <i class="fas fa-search mr-2"></i>Go to Search
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-dark-700 p-6 rounded-lg border border-yellow-600">
                        <div class="text-center">
                            <i class="fas fa-users text-yellow-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-yellow-400 mb-2">Manage Participants</h5>
                            <p class="text-gray-400 mb-4">View, edit, or delete participant records</p>
                            <a href="view_participants_edit_delete.php" class="btn-warning inline-flex items-center justify-center w-full">
                                <i class="fas fa-users-cog mr-2"></i>Manage Participants
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row - Add New Items -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-dark-700 p-6 rounded-lg border border-green-600">
                        <div class="text-center">
                            <i class="fas fa-user-plus text-green-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-green-400 mb-2">Add New Participant</h5>
                            <p class="text-gray-400 mb-4">Register a new participant to the league</p>
                            <a href="add_participant.php" class="btn-success inline-flex items-center justify-center w-full">
                                <i class="fas fa-plus-circle mr-2"></i>Add Participant
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-dark-700 p-6 rounded-lg border border-primary">
                        <div class="text-center">
                            <i class="fas fa-users text-blue-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-blue-400 mb-2">Add New Team</h5>
                            <p class="text-gray-400 mb-4">Create a new team in the league</p>
                            <a href="add_team.php" class="btn-primary inline-flex items-center justify-center w-full">
                                <i class="fas fa-plus-circle mr-2"></i>Add Team
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Third Row - Merchandise and System Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-dark-700 p-6 rounded-lg border border-purple-600">
                        <div class="text-center">
                            <i class="fas fa-gift text-purple-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-purple-400 mb-2">View Merchandise Registrations</h5>
                            <p class="text-gray-400 mb-4">See all merchandise registration requests</p>
                            <a href="view_merch.php" class="btn-purple inline-flex items-center justify-center w-full">
                                <i class="fas fa-box-open mr-2"></i>View Merchandise
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-dark-700 p-6 rounded-lg border border-red-600">
                        <div class="text-center">
                            <i class="fas fa-sign-out-alt text-red-400 text-4xl mb-4"></i>
                            <h5 class="text-lg font-semibold text-red-400 mb-2">Logout</h5>
                            <p class="text-gray-400 mb-4">End your admin session securely</p>
                            <a href="logout.php" class="btn-danger inline-flex items-center justify-center w-full">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>