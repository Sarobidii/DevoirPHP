<?php
    session_start();
    include 'db_connect.php';

    if (!isset($_SESSION['id_user'])) {
        header("Location: login_form.php");
        exit();
    }

    if (isset($_POST['contenu']) && isset($_POST['id_publication'])) {
        $contenu = $_POST['contenu'];
        $id_compte = $_SESSION['id_user']; 
        $id_publication = $_POST['id_publication'];
        
        $contenu = mysqli_real_escape_string($conn, $contenu);

        $sql = "INSERT INTO commentaire (contenu, id_publication, id_compte, date_lance) VALUES ('$contenu',$id_publication, $id_compte, NOW())";

        if (mysqli_query($conn, $sql)) {
            header("Location: afficher_commentaires.php?id_publication=$id_publication");
            exit();
        } else {
            echo "Erreur : " . mysqli_error($conn);
        } 
    }
?>
