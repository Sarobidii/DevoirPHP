<?php
header("Content-Type: application/javascript");
include 'db_connect.php';

// Fonction pour générer du JavaScript
function generateJS($id_publication) {
    echo "const reactions" . htmlspecialchars($id_publication) . " = document.querySelectorAll('.reaction_" . htmlspecialchars($id_publication) . "');";
    echo "const likeButton" . htmlspecialchars($id_publication) . " = document.querySelector('.like-button_" . htmlspecialchars($id_publication) . "');";
    echo "reactions" . htmlspecialchars($id_publication) . ".forEach(reaction => {";
    echo "    reaction.addEventListener('click', function() {";
    echo "        const reactionId = this.getAttribute('data-reaction');";
    echo "        let form = document.createElement('form');";
    echo "        form.method = 'POST';";
    echo "        form.action = 'reaction.php';";
    echo "        let reactionInput = document.createElement('input');";
    echo "        reactionInput.type = 'hidden';";
    echo "        reactionInput.name = 'reaction_" . htmlspecialchars($id_publication) . "';";
    echo "        reactionInput.value = reactionId;";
    echo "        form.appendChild(reactionInput);";
    echo "        let publicationInput = document.createElement('input');";
    echo "        publicationInput.type = 'hidden';";
    echo "        publicationInput.name = 'id_publication';";
    echo "        publicationInput.value = " . htmlspecialchars($id_publication) . ";";
    echo "        form.appendChild(publicationInput);";
    echo "        let fichierInput = document.createElement('input');";
    echo "        fichierInput.type = 'hidden';";
    echo "        fichierInput.name = 'fichier';";
    echo "        fichierInput.value = 'welcome.php';";
    echo "        form.appendChild(fichierInput);";
    echo "        document.body.appendChild(form);";
    echo "        form.submit();";
    echo "    });";
    echo "});"; 
}

// Récupérer les publications
$sql_publication = "SELECT id FROM publication";
$result_sql_publication = mysqli_query($conn, $sql_publication);
if (!$result_sql_publication) {
    die("Erreur lors de la récupération des publications: " . mysqli_error($conn));
}

// Boucle à travers chaque publication pour générer le JS
while ($row = mysqli_fetch_assoc($result_sql_publication)) {
    $id_publication = htmlspecialchars($row['id']);
    generateJS($id_publication);
}

mysqli_close($conn);
?>
