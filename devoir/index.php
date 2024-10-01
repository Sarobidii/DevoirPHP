<?php
header("Content-Type: text/css");
include 'db_connect.php';

function generateCSS($id, $isComment = false) {
    $hoverBgColor = $isComment ? "transparent" : "#f3f0f0d7"; // Couleur de fond pour hover
    $containerClass = "reaction-container_$id"; // Simplification ici
    
    // Styles généraux
    echo "    .$containerClass {";
    echo "        position: relative;";
    echo "        height: 25px;";
    if (!$isComment) {
        echo "        padding: 10px;";
        echo "        display: flex;";
        echo "        justify-content: center;";
        echo "        align-items: center;";
        echo "        width: 50%;";
        echo "        border-radius: 25px;";
        echo "        box-shadow: 0 0 2px 2px rgba(80, 80, 80, 0.1);";
    }

    echo "    }";
    
    echo "    .$containerClass:hover {";
    echo "        background-color: $hoverBgColor;";
    echo "    }";

    echo "    .like-button_$id {";
    echo "        cursor: pointer;";
    echo "        display: inline-block;";
    echo "        font-size: 16px;";
    echo "    }";

    // Styles pour les icônes de réaction
    echo "    .reaction-icons_$id {";
    echo "        display: none;";
    echo "        position: absolute;";
    echo "        top: -60px;";
    echo "        left: 20px;";
    echo "        background-color: white;";
    echo "        border: 1px solid #ccc;";
    echo "        padding: 10px;";
    echo "        border-radius: 15px;";
    echo "        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);";
    echo "        flex-direction: row;";
    echo "        z-index: 10;";
    echo "    }";

    // Styles pour les réactions
    echo "    .reaction_$id {";
    echo "        display: inline-block;";
    echo "        margin: 0 5px;";
    echo "        cursor: pointer;";
    echo "        font-size: 25px;";
    echo "        transition: transform 0.3s ease;";
    echo "    }";

    echo "    .reaction_$id:hover {";
    echo "        transform: scale(1.5);";
    echo "    }";

    echo "    .$containerClass:hover .reaction-icons_$id {";
    echo "        display: flex;";
    echo "    }";
}

// Générer le CSS pour les publications
$sql_publication = "SELECT id FROM publication";
$result_sql_publication = mysqli_query($conn, $sql_publication);
if (!$result_sql_publication) {
    die("Erreur lors de la récupération des publications: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result_sql_publication)) {
    $id_publication = htmlspecialchars($row['id']);
    generateCSS($id_publication);
}

// Générer le CSS pour les commentaires
$sql_commentaire = "SELECT id FROM commentaire";
$result_sql_commentaire = mysqli_query($conn, $sql_commentaire);
if (!$result_sql_commentaire) {
    die("Erreur lors de la récupération des commentaires: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result_sql_commentaire)) {
    $id_commentaire = htmlspecialchars($row['id']);
    generateCSS($id_commentaire, true); // Passer true pour les commentaires
}

// Générer le CSS pour les réponses aux commentaires
$sql_reponse_commentaire = "SELECT id FROM reponse_commentaire";
$result_sql_reponse_commentaire = mysqli_query($conn, $sql_reponse_commentaire);
if (!$result_sql_reponse_commentaire) {
    die("Erreur lors de la récupération des réponses aux commentaires: " . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result_sql_reponse_commentaire)) {
    $id_reponse_commentaire = htmlspecialchars($row['id']);
    generateCSS($id_reponse_commentaire, true); // Passer true pour les réponses aux commentaires
}
?>
