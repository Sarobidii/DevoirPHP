<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['id_commentaire'])) {
    $id_commentaire = mysqli_real_escape_string($conn, $_POST['id_commentaire']);
    
    $sql = "
        SELECT rp.id_compte, rp.id_reaction, c.nom, c.prenom, r.type_reaction
        FROM reaction_commentaire rp
        JOIN compte c ON rp.id_compte = c.id
        JOIN reaction r ON rp.id_reaction = r.id
        WHERE rp.id_commentaire = $id_commentaire";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['prenom'] . ' ' . $row['nom'] . ' reacted with ' . $row['type_reaction'] . '<br>';
        }
    } else {
        echo 'No reactions found for this publication.';
    }
} else {
    echo 'Invalid request. Publication ID not provided.';
}
?>
