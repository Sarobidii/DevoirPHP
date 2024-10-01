<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}
$id_compte = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="publication">
    <?php
    $sqlPublication = "SELECT * FROM compte WHERE id = $id_compte";

$resultPublication = mysqli_query($conn, $sqlPublication);

if (mysqli_num_rows($resultPublication) > 0) {
    $rowPublication = mysqli_fetch_assoc($resultPublication);
    echo '<div class="nom_prenom">';
    echo '<div class="h-10 w-10 overflow-hidden rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="h-10 w-10 p-2 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    ';
    echo '<p>' . htmlspecialchars($rowPublication['prenom']) . ' ' . htmlspecialchars($rowPublication['nom']);
    echo '</p>';
    echo '</div>';
    ?>

    <form class="publier_form" action="" method="post">
        <textarea name="contenu" placeholder="Écrire quelque chose..." cols="30" rows="3"></textarea>
        <br>
        <button class="publier" type="submit">Envoyer</button>
        </form>
    <?php

    echo '</div>';
}
    ?>
    

    <?php
    if (isset($_POST['contenu'])) {
        $contenu = $_POST['contenu'];
        $id_compte = $_SESSION['id_user']; 
        
        $contenu = mysqli_real_escape_string($conn, $contenu);

        $sql = "INSERT INTO publication (contenu, id_compte, date_lance) VALUES ('$contenu', $id_compte, NOW())";

        if (mysqli_query($conn, $sql)) {
            header("Location: welcome.php");
            exit();
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
    ?>
</body>
</html>
