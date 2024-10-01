<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    if (isset($_POST['id_publication']) && isset($_POST['fichier']) && is_numeric($_POST['id_publication'])) {
        $id_publication = intval($_POST['id_publication']); // Convertir en entier
        $fichier = $_POST['fichier'];

        // Préparer la requête SQL pour supprimer la réaction
        $sql = "DELETE FROM reaction_publication WHERE id_publication = $id_publication AND id_compte = $id_user";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Vérifier si une ligne a été affectée
            if (mysqli_affected_rows($conn) > 0) {
                header("Location: $fichier");
                exit();
            } else {
                $sql_insert = "INSERT INTO reaction_publication (id_publication, id_compte, id_reaction) 
                               VALUES ($id_publication, $id_user, 1)";
                $result_insert = mysqli_query($conn, $sql_insert);
                if ($result_insert) {
                    // Redirection après l'insertion
                    header("Location: $fichier");
                    exit();
                }
            }
        } else {
            echo '<p>Erreur lors de la suppression de la réaction : ' . mysqli_error($conn) . '</p>';
        }
    } else {
        echo '<p>Données non valides.</p>';
    }
} else {
    echo '<p>Veuillez vous connecter pour réagir aux publications.</p>';
}
?>
