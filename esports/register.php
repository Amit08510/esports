<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration Processing - UK E-Sports League</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
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
                            echo "<div class='card-header bg-danger text-white text-center'>";
                            echo "<h3 class='card-title mb-0'>Registration Failed</h3>";
                            echo "</div>";
                            echo "<div class='card-body text-center'>";
                            echo "<div class='mb-4'>";
                            echo "<i class='text-danger' style='font-size: 4rem;'>✗</i>";
                            echo "</div>";
                            echo "<h5 class='card-title text-danger'>Registration Unsuccessful</h5>";
                            echo "<p class='card-text'>Please fill in all required fields.</p>";
                            echo "<a href='register_form.html' class='btn btn-primary'>Go Back</a>";
                            echo "</div>";
                        } else {
                            // Prepare and execute insert query
                            $stmt = $conn->prepare("INSERT INTO merchandise (firstname, surname, email, terms) VALUES (:firstname, :surname, :email, :terms)");
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':surname', $surname);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':terms', $terms);
                            
                            if ($stmt->execute()) {
                                echo "<div class='card-header bg-success text-white text-center'>";
                                echo "<h3 class='card-title mb-0'>Registration Successful!</h3>";
                                echo "</div>";
                                echo "<div class='card-body text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<i class='text-success' style='font-size: 4rem;'>✓</i>";
                                echo "</div>";
                                echo "<h5 class='card-title text-success'>Welcome to UK E-Sports League!</h5>";
                                echo "<div class='alert alert-success'>";
                                echo "<p><strong>Thank you for registering, " . htmlspecialchars($firstname) . "!</strong></p>";
                                echo "<p>We will contact you at <strong>" . htmlspecialchars($email) . "</strong> when merchandise becomes available.</p>";
                                echo "</div>";
                                echo "<a href='index.html' class='btn btn-primary'>Return to Home Page</a>";
                                echo "</div>";
                            } else {
                                echo "<div class='card-header bg-danger text-white text-center'>";
                                echo "<h3 class='card-title mb-0'>Registration Failed</h3>";
                                echo "</div>";
                                echo "<div class='card-body text-center'>";
                                echo "<div class='mb-4'>";
                                echo "<i class='text-danger' style='font-size: 4rem;'>✗</i>";
                                echo "</div>";
                                echo "<h5 class='card-title text-danger'>Registration Unsuccessful</h5>";
                                echo "<p class='card-text'>There was an error processing your registration. Please try again.</p>";
                                echo "<a href='register_form.html' class='btn btn-primary'>Go Back</a>";
                                echo "</div>";
                            }
                        }

                    }
                    catch(PDOException $e) {
                        echo "<div class='card-header bg-danger text-white text-center'>";
                        echo "<h3 class='card-title mb-0'>Database Error</h3>";
                        echo "</div>";
                        echo "<div class='card-body text-center'>";
                        echo "<div class='mb-4'>";
                        echo "<i class='text-danger' style='font-size: 4rem;'>⚠</i>";
                        echo "</div>";
                        echo "<h5 class='card-title text-danger'>Connection Error</h5>";
                        echo "<p class='card-text'>There was an error connecting to the database.</p>";
                        echo "<a href='register_form.html' class='btn btn-primary'>Go Back</a>";
                        echo "</div>";
                        // For debugging only - remove in production
                        // echo $e->getMessage();
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