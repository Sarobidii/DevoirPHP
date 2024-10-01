<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Entrez votre adresse email</h2>
    <form action="" method="post">
        <label for="email">E-mail : </label>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Valider"><br><br>
    </form>

    <?php
    session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include 'db_connect.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = $_POST['email'];

        $email = stripslashes($email);
        $email = mysqli_real_escape_string($conn, $email);

        $sql = "SELECT id FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $verification_code = rand(10000000, 99999999);
            
            $subject = "Votre code de vérification";
            $message = "Votre code de vérification est : $verification_code";
            $headers = "From: noreply@votre_site.com";

            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['verification_code'] = $verification_code;
                $_SESSION['email'] = $email;

                header("Location: verification_code.php");
                exit();
            } else {
                echo "Erreur lors de l'envoi de l'e-mail.";
            }
        } else {
            echo "E-mail non trouvé.";
        }
    }
    ?>
</body>
</html>
