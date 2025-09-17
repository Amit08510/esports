<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Processing - UK E-Sports League</title>
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
            
            .btn-success {
                @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300;
            }
            
            .btn-secondary {
                @apply border border-gray-600 hover:bg-gray-700 text-gray-300 font-medium py-2 px-4 rounded-md transition-colors duration-300;
            }
        }
    </style>
</head>
<body class="bg-dark-900 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-2xl">
        <div class="bg-dark-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
            <?php
            //connection variables  
            include 'dbconnect.php';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Get form data
                $firstname = $_POST['firstname'];
                $surname = $_POST['surname'];
                $email = $_POST['email'];
                $terms = isset($_POST['terms']) ? 1 : 0; // Convert checkbox to boolean

                // Validate required fields
                if (empty($firstname) || empty($surname) || empty($email)) {
                    echo "<div class='bg-gradient-to-r from-red-700 to-red-800 py-5 px-6 text-center'>";
                    echo "<h3 class='text-xl font-bold text-white'>Registration Failed</h3>";
                    echo "</div>";
                    echo "<div class='p-6 text-center'>";
                    echo "<div class='mb-4'>";
                    echo "<i class='fas fa-times-circle text-red-500 text-5xl'></i>";
                    echo "</div>";
                    echo "<h5 class='text-xl font-semibold text-red-400 mb-4'>Registration Unsuccessful</h5>";
                    echo "<div class='bg-red-900/30 border border-red-800 rounded-lg p-4 mb-6'>";
                    echo "<p class='text-red-300'>Please fill in all required fields.</p>";
                    echo "</div>";
                    echo "<a href='register_form.html' class='btn-primary inline-flex items-center justify-center'>";
                    echo "<i class='fas fa-arrow-left mr-2'></i>Go Back";
                    echo "</a>";
                    echo "</div>";
                } else {
                    // Prepare and execute insert query
                    $stmt = $conn->prepare("INSERT INTO merchandise (firstname, surname, email, terms) VALUES (:firstname, :surname, :email, :terms)");
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':surname', $surname);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':terms', $terms);
                    
                    if ($stmt->execute()) {
                        echo "<div class='bg-gradient-to-r from-green-700 to-green-800 py-5 px-6 text-center'>";
                        echo "<h3 class='text-xl font-bold text-white'>Registration Successful!</h3>";
                        echo "</div>";
                        echo "<div class='p-6 text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='fas fa-check-circle text-green-500 text-5xl'></i>";
                        echo "</div>";
                        echo "<h5 class='text-xl font-semibold text-green-400 mb-4'>Welcome to UK E-Sports League!</h5>";
                        echo "<div class='bg-green-900/30 border border-green-800 rounded-lg p-6 mb-6'>";
                        echo "<p class='text-green-300 font-medium mb-2'>Thank you for registering, <span class='text-white font-bold'>" . htmlspecialchars($firstname) . "</span>!</p>";
                        echo "<p class='text-green-400'>We will contact you at <span class='text-white font-medium'>" . htmlspecialchars($email) . "</span> when merchandise becomes available.</p>";
                        echo "</div>";
                        echo "<a href='index.html' class='btn-success inline-flex items-center justify-center'>";
                        echo "<i class='fas fa-home mr-2'></i>Return to Home Page";
                        echo "</a>";
                        echo "</div>";
                    } else {
                        echo "<div class='bg-gradient-to-r from-red-700 to-red-800 py-5 px-6 text-center'>";
                        echo "<h3 class='text-xl font-bold text-white'>Registration Failed</h3>";
                        echo "</div>";
                        echo "<div class='p-6 text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='fas fa-times-circle text-red-500 text-5xl'></i>";
                        echo "</div>";
                        echo "<h5 class='text-xl font-semibold text-red-400 mb-4'>Registration Unsuccessful</h5>";
                        echo "<div class='bg-red-900/30 border border-red-800 rounded-lg p-4 mb-6'>";
                        echo "<p class='text-red-300'>There was an error processing your registration. Please try again.</p>";
                        echo "</div>";
                        echo "<a href='register_form.html' class='btn-primary inline-flex items-center justify-center'>";
                        echo "<i class='fas fa-arrow-left mr-2'></i>Go Back";
                        echo "</a>";
                        echo "</div>";
                    }
                }

            }
            catch(PDOException $e) {
                echo "<div class='bg-gradient-to-r from-red-700 to-red-800 py-5 px-6 text-center'>";
                echo "<h3 class='text-xl font-bold text-white'>Database Error</h3>";
                echo "</div>";
                echo "<div class='p-6 text-center'>";
                echo "<div class='mb-4'>";
                echo "<i class='fas fa-database text-red-500 text-5xl'></i>";
                echo "</div>";
                echo "<h5 class='text-xl font-semibold text-red-400 mb-4'>Connection Error</h5>";
                echo "<div class='bg-red-900/30 border border-red-800 rounded-lg p-4 mb-6'>";
                echo "<p class='text-red-300'>There was an error connecting to the database.</p>";
                echo "</div>";
                echo "<a href='register_form.html' class='btn-primary inline-flex items-center justify-center'>";
                echo "<i class='fas fa-arrow-left mr-2'></i>Go Back";
                echo "</a>";
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