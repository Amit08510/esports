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
    <title>Search - UK E-Sports League Admin</title>
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
                @apply bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300;
            }
            
            .btn-success {
                @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300;
            }
            
            .btn-secondary {
                @apply border border-gray-600 hover:bg-gray-700 text-gray-300 font-medium py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .form-input {
                @apply w-full px-4 py-3 bg-dark-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent;
            }
            
            .form-label {
                @apply block text-gray-300 mb-2 font-medium;
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
                    <a href="admin_menu.php" class="btn-secondary inline-flex items-center text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-cyan-700 to-cyan-800 py-5 px-6 text-center">
                <h2 class="text-2xl font-bold text-white">Search for Participants or Teams</h2>
                <p class="text-cyan-100 text-sm mt-1">UK E-Sports League Admin Portal</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Participant Search Card -->
                    <div class="bg-dark-700 p-6 rounded-lg border border-primary">
                        <div class="text-center mb-4">
                            <i class="fas fa-user text-blue-400 text-4xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-blue-400 mb-4 text-center">Search Individual Participant</h4>
                        <form action="search_result.php" method="POST">
                            <div class="mb-4">
                                <label for="firstname_surname" class="form-label">Participant First Name or Surname</label>
                                <input type="text" class="form-input" id="firstname_surname" name="firstname_surname" required 
                                    placeholder="Enter name to search">
                            </div>
                            <input type="hidden" name="participant" value="1">
                            <button type="submit" class="btn-primary w-full inline-flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>Search Participant
                            </button>
                        </form>
                    </div>

                    <!-- Team Search Card -->
                    <div class="bg-dark-700 p-6 rounded-lg border border-green-600">
                        <div class="text-center mb-4">
                            <i class="fas fa-users text-green-400 text-4xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-green-400 mb-4 text-center">Search Team</h4>
                        <form action="search_result.php" method="POST">
                            <div class="mb-4">
                                <label for="team" class="form-label">Team Name</label>
                                <input type="text" class="form-input" id="team" name="team" required 
                                    placeholder="Enter team name to search">
                            </div>
                            <button type="submit" class="btn-success w-full inline-flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>Search Team
                            </button>
                        </form>
                    </div>
                </div>
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