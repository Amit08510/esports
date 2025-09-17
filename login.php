<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Processing - UK E-Sports League</title>
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
            
            .btn-danger {
                @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-warning {
                @apply bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
        }
    </style>
</head>
<body class="bg-dark-900 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <?php
            include 'dbconnect.php';
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Get form data
                    $login_username = $_POST['username'];
                    $login_password = $_POST['password'];

                    // Prepare and execute query to check credentials
                    $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username AND password = :password");
                    $stmt->bindParam(':username', $login_username);
                    $stmt->bindParam(':password', $login_password);
                    $stmt->execute();

                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        // Login successful - create session
                        $_SESSION['logged_in'] = true;
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_id'] = $user['id'];
                        
                        // Redirect to admin menu
                        header("Location: admin_menu.php");
                        exit();
                    } else {
                        // Login failed
                        echo "<div class='bg-gradient-to-r from-red-700 to-red-800 py-5 px-6 text-center'>";
                        echo "<h3 class='text-xl font-bold text-white'>Login Failed</h3>";
                        echo "</div>";
                        echo "<div class='p-6 text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='fas fa-times-circle text-red-500 text-5xl'></i>";
                        echo "</div>";
                        echo "<h5 class='text-xl font-semibold text-red-400 mb-4'>Authentication Failed</h5>";
                        echo "<div class='bg-red-900/30 border border-red-800 rounded-lg p-4 mb-6'>";
                        echo "<p class='text-red-300 font-medium'>Invalid username or password.</p>";
                        echo "<p class='text-red-400 text-sm mt-1'>Please check your credentials and try again.</p>";
                        echo "</div>";
                        echo "<div class='flex flex-col sm:flex-row gap-3 justify-center'>";
                        echo "<a href='admin_login.html' class='btn-primary inline-flex items-center justify-center'>";
                        echo "<i class='fas fa-redo mr-2'></i>Try Again";
                        echo "</a>";
                        echo "<a href='index.html' class='btn-secondary inline-flex items-center justify-center'>";
                        echo "<i class='fas fa-home mr-2'></i>Back to Home";
                        echo "</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    
                }
                catch(PDOException $e) {
                    // Database error 
                    echo "<div class='bg-gradient-to-r from-red-700 to-red-800 py-5 px-6 text-center'>";
                    echo "<h3 class='text-xl font-bold text-white'>Database Error</h3>";
                    echo "</div>";
                    echo "<div class='p-6 text-center'>";
                    echo "<div class='mb-4'>";
                    echo "<i class='fas fa-database text-red-500 text-5xl'></i>";
                    echo "</div>";
                    echo "<h5 class='text-xl font-semibold text-red-400 mb-4'>Connection Error</h5>";
                    echo "<div class='bg-red-900/30 border border-red-800 rounded-lg p-4 mb-6'>";
                    echo "<p class='text-red-300 font-medium'>There was an error connecting to the database.</p>";
                    echo "<p class='text-red-400 text-sm mt-1'>Please try again later or contact the administrator.</p>";
                    echo "</div>";
                    echo "<div class='flex flex-col sm:flex-row gap-3 justify-center'>";
                    echo "<a href='admin_login.html' class='btn-primary inline-flex items-center justify-center'>";
                    echo "<i class='fas fa-redo mr-2'></i>Try Again";
                    echo "</a>";
                    echo "<a href='index.html' class='btn-secondary inline-flex items-center justify-center'>";
                    echo "<i class='fas fa-home mr-2'></i>Back to Home";
                    echo "</a>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            else{
                // Invalid request method
                echo "<div class='bg-gradient-to-r from-yellow-700 to-yellow-800 py-5 px-6 text-center'>";
                echo "<h3 class='text-xl font-bold text-white'>Invalid Access</h3>";
                echo "</div>";
                echo "<div class='p-6 text-center'>";
                echo "<div class='mb-4'>";
                echo "<i class='fas fa-exclamation-triangle text-yellow-500 text-5xl'></i>";
                echo "</div>";
                echo "<h5 class='text-xl font-semibold text-yellow-400 mb-4'>Unauthorized Access</h5>";
                echo "<div class='bg-yellow-900/30 border border-yellow-800 rounded-lg p-4 mb-6'>";
                echo "<p class='text-yellow-300 font-medium'>You have accessed this page incorrectly.</p>";
                echo "<p class='text-yellow-400 text-sm mt-1'>Please use the login form to access the admin area.</p>";
                echo "</div>";
                echo "<div class='flex flex-col sm:flex-row gap-3 justify-center'>";
                echo "<a href='admin_login.html' class='btn-warning inline-flex items-center justify-center'>";
                echo "<i class='fas fa-sign-in-alt mr-2'></i>Go to Login";
                echo "</a>";
                echo "<a href='index.html' class='btn-secondary inline-flex items-center justify-center'>";
                echo "<i class='fas fa-home mr-2'></i>Back to Home";
                echo "</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <div class="bg-dark-700 px-6 py-4 text-center text-gray-400 text-sm">
                <p>UK E-Sports League Web Portal</p>
            </div>
        </div>
    </div>

</body>
</html>