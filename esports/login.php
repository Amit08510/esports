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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
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
                                echo "<div class='card-header bg-danger text-white text-center'>";
                                echo "<h3 class='card-title mb-0'>Login Failed</h3>";
                                echo "</div>";
                                echo "<div class='card-body text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<i class='text-danger' style='font-size: 4rem;'>✗</i>";
                                echo "</div>";
                                echo "<h5 class='card-title text-danger'>Authentication Failed</h5>";
                                echo "<div class='alert alert-danger'>";
                                echo "<p><strong>Invalid username or password.</strong></p>";
                                echo "<p class='mb-0'>Please check your credentials and try again.</p>";
                                echo "</div>";
                                echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>";
                                echo "<a href='admin_login.html' class='btn btn-primary'>Try Again</a>";
                                echo "<a href='index.html' class='btn btn-outline-secondary'>Back to Home</a>";
                                echo "</div>";
                                echo "</div>";
                            }
                            
                        }
                        catch(PDOException $e) {
                            // Database error 
                            echo "<div class='card-header bg-danger text-white text-center'>";
                            echo "<h3 class='card-title mb-0'>Database Error</h3>";
                            echo "</div>";
                            echo "<div class='card-body text-center'>";
                            echo "<div class='mb-4'>";
                            echo "<i class='text-danger' style='font-size: 4rem;'>⚠</i>";
                            echo "</div>";
                            echo "<h5 class='card-title text-danger'>Connection Error</h5>";
                            echo "<div class='alert alert-danger'>";
                            echo "<p><strong>There was an error connecting to the database.</strong></p>";
                            echo "<p class='mb-0'>Please try again later or contact the administrator.</p>";
                            echo "</div>";
                            echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>";
                            echo "<a href='admin_login.html' class='btn btn-primary'>Try Again</a>";
                            echo "<a href='index.html' class='btn btn-outline-secondary'>Back to Home</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    else{
                        // Invalid request method
                        echo "<div class='card-header bg-warning text-dark text-center'>";
                        echo "<h3 class='card-title mb-0'>Invalid Access</h3>";
                        echo "</div>";
                        echo "<div class='card-body text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='text-warning' style='font-size: 4rem;'>⚠</i>";
                        echo "</div>";
                        echo "<h5 class='card-title text-warning'>Unauthorized Access</h5>";
                        echo "<div class='alert alert-warning'>";
                        echo "<p><strong>You have accessed this page incorrectly.</strong></p>";
                        echo "<p class='mb-0'>Please use the login form to access the admin area.</p>";
                        echo "</div>";
                        echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>";
                        echo "<a href='admin_login.html' class='btn btn-primary'>Go to Login</a>";
                        echo "<a href='index.html' class='btn btn-outline-secondary'>Back to Home</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                    <div class="card-footer text-center text-muted">
                        <small>UK E-Sports League Web Portal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>