<?php
session_start();
include 'db_connect.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

// Vérifiez si les paramètres requis sont définis
if (isset($_POST['id_commentaire']) && isset($_POST['fichier']) && isset($_POST['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_POST['id_publication']);
    $id_commentaire = mysqli_real_escape_string($conn, $_POST['id_commentaire']);
    $fichier = mysqli_real_escape_string($conn, $_POST['fichier']);
} else {
    echo 'Invalid request. Publication ID not provided.';
    exit();
}

// Vérifiez si le contenu est défini
if (isset($_POST['contenu'])) {
    $contenu = mysqli_real_escape_string($conn, $_POST['contenu']);
} else {
    echo 'Invalid request. Content not provided.';
    exit();
}

// Récupérer l'id_compte de la session
$id_compte = $_SESSION['id_user']; // Assurez-vous que c'est la bonne valeur

// Modifiez la requête SQL pour inclure l'id_compte, le contenu et la date de réponse
$sql = "INSERT INTO reponse_commentaire (id_commentaire, contenu, id_compte, date_lance) VALUES ('$id_commentaire', '$contenu', '$id_compte', NOW())";

if (mysqli_query($conn, $sql)) {
    // Rediriger après l'insertion réussie
    header("Location: $fichier");
    exit();
} else {
    echo "Erreur : " . mysqli_error($conn);
}

// Fermez la connexion
mysqli_close($conn);
?>
