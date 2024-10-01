<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Requête pour récupérer uniquement les utilisateurs qui ne sont pas encore amis
    $sql = "
        SELECT id, nom, prenom 
        FROM compte 
        WHERE id != $id_user 
        AND id NOT IN (
            SELECT id_compte_amis FROM amis WHERE id_compte = $id_user
            UNION 
            SELECT id_compte FROM amis WHERE id_compte_amis = $id_user
        )";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            $id_ami = $row['id'];
            $prenom = htmlspecialchars($row['prenom']);
            $nom = htmlspecialchars($row['nom']);
            
            echo '<li>';
            echo $prenom . ' ' . $nom;

            // Afficher le bouton pour ajouter en ami
            echo '<form action="ajout_amis.php" method="post">';
            echo '<input type="hidden" name="id_ami" value="'. $id_ami .'" >';
            echo '<button type="submit">Ajouter comme ami</button>';
            echo '</form>';

            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Aucun utilisateur trouvé.</p>';
    }

} else {
    header("Location: login_form.php");
    exit();
}
?>
