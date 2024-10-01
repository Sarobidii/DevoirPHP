<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['code'])) {
    $input_code = $_POST['code'];

    if ($input_code == $_SESSION['verification_code']) {
        header("Location: changer_mot_de_passe.php");
        exit(); 
    } else {
        echo "Code incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vérification du code</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Entrez votre code de vérification</h2>
    <form action="" method="post">
        <label for="code">Code de vérification : </label>
        <input type="text" name="code" required><br><br>
        <input type="submit" value="Vérifier"><br><br>
    </form>
</body>
</html>
