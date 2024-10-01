<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Requête pour récupérer uniquement les utilisateurs qui sont déjà amis
    $sql = "
        SELECT c.id, c.nom, c.prenom 
        FROM compte c
        JOIN amis a ON (c.id = a.id_compte_amis OR c.id = a.id_compte)
        WHERE (a.id_compte = $id_user OR a.id_compte_amis = $id_user)
        AND c.id != $id_user";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            $id_ami = $row['id'];
            $prenom = htmlspecialchars($row['prenom']);
            $nom = htmlspecialchars($row['nom']);
            
            echo '<li>';
            echo $prenom . ' ' . $nom;

            // Afficher le bouton "Amis"
            echo '<button>Amis</button>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Aucun ami trouvé.</p>';
    }

} else {
    header("Location: login_form.php");
    exit();
}
?>
