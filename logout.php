<?php
session_start();

// Destroy all session data
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logout - UK E-Sports League</title>
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
            
            .btn-secondary {
                @apply border border-gray-600 hover:bg-gray-700 text-gray-300 font-medium py-3 px-6 rounded-md transition-colors duration-300;
            }
        }
    </style>
</head>
<body class="bg-dark-900 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-green-700 to-green-800 py-5 px-6 text-center">
                <h3 class="text-xl font-bold text-white">Logout Successful</h3>
                <p class="text-green-100 text-sm mt-1">UK E-Sports League Admin Portal</p>
            </div>
            
            <div class="p-6 text-center">
                <div class="mb-6">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                
                <h5 class="text-xl font-semibold text-green-400 mb-4">You have been successfully logged out</h5>
                
                <div class="bg-green-900/30 border border-green-800 rounded-lg p-4 mb-6">
                    <p class="text-green-300">Thank you for using the UK E-Sports League Admin Portal.</p>
                    <p class="text-green-400 text-sm mt-2">Your session has been securely ended.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="index.html" class="btn-primary inline-flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Home Page
                    </a>
                    <a href="admin_login.html" class="btn-secondary inline-flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                </div>
            </div>
            
            <div class="bg-dark-700 px-6 py-4 text-center text-gray-400 text-sm">
                <p>UK E-Sports League Web Portal</p>
            </div>
        </div>
    </div>
</body>
</html>