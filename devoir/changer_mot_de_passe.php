<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password == $confirm_password) {

        $email = $_SESSION['email']; 
        $update_sql = "UPDATE admin SET mdp = '$new_password' WHERE email = '$email'";

        if (mysqli_query($conn, $update_sql)) {
            echo "Mot de passe mis à jour avec succès.";
            session_destroy(); 
            header("Location: login_form.php");
            exit(); 
        } else {
            echo "Erreur lors de la mise à jour du mot de passe : " . mysqli_error($conn);
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Changer de mot de passe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Changer votre mot de passe</h2>
    <form action="" method="post">
        <label for="new_password">Nouveau mot de passe : </label>
        <input type="password" name="new_password" required><br><br>

        <label for="confirm_password">Confirmer le mot de passe : </label>
        <input type="password" name="confirm_password" required><br><br>

        <input type="submit" value="Mettre à jour le mot de passe"><br><br>
    </form>
</body>
</html>
