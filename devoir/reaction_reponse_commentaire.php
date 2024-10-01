<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    if (isset($_POST['id_publication']) && isset($_POST['fichier']) && isset($_POST['id_commentaire']) && isset($_POST['id_reponse_commentaire']) && isset($_POST['reaction_' . $_POST['id_commentaire']])) {
        $id_reponse_commentaire = $_POST['id_reponse_commentaire'];
        $id_reaction = $_POST['reaction_' . $_POST['id_commentaire']];
        $id_publication = $_POST['id_publication'];
        $id_compte = $_POST['id_compte'];
        $fichier = $_POST['fichier'];

        if (!empty($id_reaction)) {
            $sql_verification = "SELECT id_reaction FROM reaction_reponse_commentaire WHERE id_reponse_commentaire = $id_reponse_commentaire AND id_compte = $id_user";
            $result_verification = mysqli_query($conn, $sql_verification);

            if (mysqli_num_rows($result_verification) == 1) {
                $row = mysqli_fetch_assoc($result_verification);
                $id_reaction_existante = $row['id_reaction'];
                $sql_update = "UPDATE reaction_reponse_commentaire SET id_reaction = $id_reaction WHERE id_reponse_commentaire = $id_reponse_commentaire AND id_compte = $id_user";
                if (mysqli_query($conn, $sql_update)) {
                    header("Location: $fichier");
                    exit();
                } else {
                    echo '<p>Erreur lors de la mise à jour de la réaction.</p>';
                }
            } else {
                $sql_insert = "INSERT INTO reaction_reponse_commentaire (id_compte,id_reponse_commentaire,id_reaction) VALUES ($id_user,$id_reponse_commentaire,$id_reaction);";
                if (mysqli_query($conn, $sql_insert)) {
                    header("Location: $fichier");
                    exit();
                } else {
                    echo '<p>Erreur lors de l\'enregistrement de la réaction.</p>';
                }
            }  
        } else {
            echo '<p>Aucune réaction sélectionnée.</p>';
        } 
    } else {
        echo '<p>Données non valides.</p>';
    }
} else {
    echo '<p>Veuillez vous connecter pour réagir aux publications.</p>';
} 
?>
