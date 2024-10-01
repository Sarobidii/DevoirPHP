<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $email = stripslashes($email);
    $mdp = stripslashes($mdp);
    $email = mysqli_real_escape_string($conn, $email);
    $mdp = mysqli_real_escape_string($conn, $mdp);

    $sql = "SELECT id, mdp FROM compte WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['mdp'];

        if ($mdp === $hashed_password) { 
            $_SESSION['email_user'] = $email;
            $_SESSION['id_user'] = $row['id'];
            header("Location: welcome.php");
            exit();
        } else {
            $_SESSION['error'] = '*Adresse email ou mot de passe invalide.';
            $_SESSION['email'] = $email;
            $_SESSION['mdp'] = $mdp;
            header("Location: login_form.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "*Cet adresse email n'est associé à aucun compte.";
        header("Location: login_form.php");
        $_SESSION['email'] = $email;
        $_SESSION['mdp'] = $mdp;
        exit();
    }
} 
?>
