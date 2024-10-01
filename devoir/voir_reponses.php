<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="feuille.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

// Check if required parameters are set via GET
if (isset($_GET['id_commentaire']) && isset($_GET['id_compte']) && isset($_GET['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
    $id_commentaire = mysqli_real_escape_string($conn, $_GET['id_commentaire']);
    $id_compte = mysqli_real_escape_string($conn, $_GET['id_compte']);
} else {
    echo 'Invalid request. Publication ID not provided.';
    exit();
}

// Fetch the comment based on id_commentaire
$comment_query = "SELECT * FROM commentaire WHERE id = '$id_commentaire'";
$comment_result = mysqli_query($conn, $comment_query);

if (mysqli_num_rows($comment_result) > 0) {
    $comment = mysqli_fetch_assoc($comment_result);
    
    // Fetch user information
    $sql_compte = "SELECT nom, prenom FROM compte WHERE id = '$id_compte'";
    $compte_result = mysqli_query($conn, $sql_compte);
    
    if (!$compte_result) {
        echo "Database query failed: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($compte_result) > 0) {
        $row = mysqli_fetch_assoc($compte_result);
        echo '<div class="commentaire">';
        echo '<div class="nom_prenom">';
        echo '<div class="h-7 w-7 overflow-hidden rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="h-7 w-7 p-1 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    ';
        echo "<p class='nom_prenom'>" . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '</p>';
    } 
    echo '</div>';
    echo "<p>" . htmlspecialchars($comment['contenu']) . "</p>";
    
                echo '</div>';
                echo '<div class="bouton_commentaire">';
                echo "<div><small class='date_lance_commentaire'> " . htmlspecialchars($comment['date_lance']) . "</small></div>";
                        echo '<div class="reaction-container_' . htmlspecialchars($id_commentaire) . '">';
                        // echo '<div class="reaction_par_defaut_commentaire" class="like-button_' . htmlspecialchars($id_commentaire) . '">J\'aime</div>';  
                        $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_commentaire rp
                                JOIN reaction r ON rp.id_reaction = r.id
                                WHERE rp.id_commentaire = $id_commentaire AND rp.id_compte = $id_compte";
                        $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
                        if(mysqli_num_rows($resultUserReaction) > 0) {
                            $userReaction = mysqli_fetch_assoc($resultUserReaction);
                            echo '<div class="like-button_' . htmlspecialchars($id_commentaire) . '">' . $userReaction['type_reaction'];
                            echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
                            echo $userReaction['nom_reaction'];
                            echo '</span> ';
                            echo '</div>';   
                        } else {
                            echo '<div class="like-button_' . htmlspecialchars($id_commentaire) . '">J\'aime';
                            echo '</div>';
                        }
                        echo '<div class="reaction-icons_' . htmlspecialchars($id_commentaire) . '">';
                        include 'db_connect.php';
                        $sql2 = "SELECT id, type_reaction FROM reaction";
                        $result2 = mysqli_query($conn, $sql2);
                        if (mysqli_num_rows($result2) > 0) {
                            mysqli_data_seek($result2, 0);
                            while ($row2 = mysqli_fetch_assoc($result2)) {
                                echo "<div class='reaction_" . htmlspecialchars($id_commentaire) . "' data-reaction='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</div>";
                            }
                        }
                        echo '</div>';
                        echo '</div>'; 
                        echo '<script src="essai.js"></script>'; 
                        echo '<script>';
                        echo "const reactions" . htmlspecialchars($id_commentaire) . " = document.querySelectorAll('.reaction_" . htmlspecialchars($id_commentaire) . "');";
                        echo "const likeButton" . htmlspecialchars($id_commentaire) . " = document.querySelector('.like-button_" . htmlspecialchars($id_commentaire) . "');";
                        echo "likeButton" . htmlspecialchars($id_commentaire) . ".addEventListener('click', function() {";
                        echo "    let form = document.createElement('form');";
                        echo "    form.method = 'POST';";
                        echo "    form.action = 'supprimer_reaction_commentaire.php';";
            
                        // Ajoutez les champs cachés pour l'ID de la publication et la réaction
                        echo "    addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
                        echo "    addHiddenInput(form, 'fichier', 'voir_reponses.php?id_compte=" . htmlspecialchars($id_compte) . "&id_commentaire=" . htmlspecialchars($id_commentaire) . "&id_publication=" . htmlspecialchars($id_publication) . "');";
                        echo "    addHiddenInput(form, 'id_commentaire', " . htmlspecialchars($id_commentaire) . ");";
            
                        echo "    document.body.appendChild(form);";
                        echo "    form.submit();";
                        echo "});";
                        echo "reactions" . htmlspecialchars($id_commentaire) . ".forEach(reaction => {";
                        echo "    reaction.addEventListener('click', function() {";
                        echo "        const reactionId = this.getAttribute('data-reaction');";
                        echo "        let form = document.createElement('form');";
                        echo "        form.method = 'POST';";
                        echo "        form.action = 'reaction_commentaire.php';";

                        echo "        addHiddenInput(form, 'reaction_" . htmlspecialchars($id_commentaire) . "', reactionId);";
                        echo "        addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
                        echo "        addHiddenInput(form, 'id_commentaire', " . htmlspecialchars($id_commentaire) . ");";
                        echo "        addHiddenInput(form, 'fichier', 'voir_reponses.php?id_compte=" . htmlspecialchars($id_compte) . "&id_commentaire=" . htmlspecialchars($id_commentaire) . "&id_publication=" . htmlspecialchars($id_publication) . "');";

                        echo "        document.body.appendChild(form);";
                        echo "        form.submit();";
                        echo "    });";
                        echo "});"; 

                        echo '</script>';

            
                        echo '<div><a href="voir_reponses.php?id_compte=' . $id_compte . '&id_commentaire=' . $id_commentaire . '&id_publication=' . $id_publication .'">Répondre</a></div>';
                        echo '<div class="nombre_reaction_commentaire">';
                        // Count reactions for each comment
                        $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_commentaire WHERE id_commentaire = $id_commentaire";
                        $resultCount = mysqli_query($conn, $sqlCount);
                        $rowCount = mysqli_fetch_assoc($resultCount);
                        $nombre_reactions = $rowCount['total_reactions'];
                        $reactions_existant = "SELECT r.type_reaction FROM reaction_commentaire rp
                            JOIN reaction r ON rp.id_reaction = r.id
                            WHERE rp.id_commentaire = $id_commentaire";
                            $result_reactions_existant = mysqli_query($conn, $reactions_existant);
                            while($row = mysqli_fetch_assoc($result_reactions_existant))
                            {
                                echo $row['type_reaction'];
                            }
                            if($nombre_reactions != 0)
                            {
                                echo $nombre_reactions;
                            } 
                            echo '</div>';
                            echo '</div>';
    echo '<div class="ensemble_reponses_commentaire">';
    // Fetch responses corresponding to the comment
    $response_query = "SELECT * FROM reponse_commentaire WHERE id_commentaire = '$id_commentaire'";
    $response_result = mysqli_query($conn, $response_query);

    if (mysqli_num_rows($response_result) > 0) {
        while ($response = mysqli_fetch_assoc($response_result)) {
            $id_compte1 = $response['id_compte'];
            $sql_compte1 = "SELECT nom, prenom FROM compte WHERE id = '$id_compte1'";
        $compte_result1 = mysqli_query($conn, $sql_compte1);
    
    if (!$compte_result1) {
        echo "Database query failed: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($compte_result1) > 0) {
        $row1 = mysqli_fetch_assoc($compte_result1);
        echo '<div class="commentaire">';
        echo '<div class="nom_prenom">';
        echo '<div class="h-7 w-7 overflow-hidden rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="h-7 w-7 p-1 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    ';
        echo "<p class='nom_prenom'>" . htmlspecialchars($row1['prenom']) . ' ' . htmlspecialchars($row1['nom']) . '</p>';
    } 
    echo '</div>';
            echo "<p>" . htmlspecialchars($response['contenu']) . "</p>";

$id_reponse_commentaire = $response['id'];
echo '</div>';   

echo '<div class="bouton_commentaire">';
            echo "<small>" . htmlspecialchars($response['date_lance']) . "</small>";
            $sql2 = "SELECT id, type_reaction FROM reaction";
            $result2 = mysqli_query($conn, $sql2);
            echo '<div class="reaction-container_' . htmlspecialchars($id_reponse_commentaire) . '">';
            $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_reponse_commentaire rp
                    JOIN reaction r ON rp.id_reaction = r.id
                    WHERE rp.id_reponse_commentaire = $id_reponse_commentaire AND rp.id_compte = $id_compte";
            $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
            if(mysqli_num_rows($resultUserReaction) > 0) {
                $userReaction = mysqli_fetch_assoc($resultUserReaction);
                echo '<div class="like-button_' . htmlspecialchars($id_reponse_commentaire) . '">' . $userReaction['type_reaction'];
                echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
                echo $userReaction['nom_reaction'];
                echo '</span> ';
                echo '</div>';   
            } else {
                echo '<div class="like-button_' . htmlspecialchars($id_reponse_commentaire) . '">J\'aime';
                echo '</div>';
            }
            echo '<div class="reaction-icons_' . htmlspecialchars($id_reponse_commentaire) . '">';
            $sql2 = "SELECT id, type_reaction FROM reaction";
            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2) > 0) {
                mysqli_data_seek($result2, 0);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<div class='reaction_" . htmlspecialchars($id_reponse_commentaire) . "' data-reaction='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</div>";
                }
            }
            echo '</div>';
            echo '</div>'; 
            echo '<script src="essai.js"></script>'; 
            echo '<script>';
            echo "const reactions" . htmlspecialchars($id_reponse_commentaire) . " = document.querySelectorAll('.reaction_" . htmlspecialchars($id_reponse_commentaire) . "');";
            echo "const likeButton" . htmlspecialchars($id_reponse_commentaire) . " = document.querySelector('.like-button_" . htmlspecialchars($id_reponse_commentaire) . "');";
            echo "likeButton" . htmlspecialchars($id_reponse_commentaire) . ".addEventListener('click', function() {";
            echo "    let form = document.createElement('form');";
            echo "    form.method = 'POST';";
            echo "    form.action = 'supprimer_reaction_reponse_commentaire.php';";

            // Ajoutez les champs cachés pour l'ID de la publication et la réaction
            echo "    addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
            echo "    addHiddenInput(form, 'fichier', 'voir_reponses.php?id_compte=" . htmlspecialchars($id_compte) . "&id_commentaire=" . htmlspecialchars($id_commentaire) . "&id_publication=" . htmlspecialchars($id_publication) . "');";
            echo "    addHiddenInput(form, 'id_commentaire', " . htmlspecialchars($id_commentaire) . ");";
            echo "    addHiddenInput(form, 'id_reponse_commentaire', " . htmlspecialchars($id_reponse_commentaire) . ");";

            echo "    document.body.appendChild(form);";
            echo "    form.submit();";
            echo "});";
            echo "reactions" . htmlspecialchars($id_reponse_commentaire) . ".forEach(reaction => {";
            echo "    reaction.addEventListener('click', function() {";
            echo "        const reactionId = this.getAttribute('data-reaction');";
            echo "        let form = document.createElement('form');";
            echo "        form.method = 'POST';";
            echo "        form.action = 'reaction_reponse_commentaire.php';";

            echo "        addHiddenInput(form, 'reaction_" . htmlspecialchars($id_commentaire) . "', reactionId);";
            echo "        addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
            echo "        addHiddenInput(form, 'id_commentaire', " . htmlspecialchars($id_commentaire) . ");";
            echo "        addHiddenInput(form, 'id_reponse_commentaire', " . htmlspecialchars($id_reponse_commentaire) . ");";
            echo "        addHiddenInput(form, 'fichier', 'voir_reponses.php?id_compte=" . htmlspecialchars($id_compte) . "&id_commentaire=" . htmlspecialchars($id_commentaire) . "&id_publication=" . htmlspecialchars($id_publication) . "');";

            echo "        document.body.appendChild(form);";
            echo "        form.submit();";
            echo "    });";
            echo "});"; 

            echo '</script>';

            echo '<div><a href="voir_reponses.php?id_compte=' . $id_compte . '&id_commentaire=' . $id_commentaire . '&id_publication=' . $id_publication .'">Répondre</a></div>';
            echo '<div class="nombre_reaction_commentaire">';
            // Count reactions for each comment
            $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_reponse_commentaire WHERE id_reponse_commentaire = $id_reponse_commentaire";
            $resultCount = mysqli_query($conn, $sqlCount);
            $rowCount = mysqli_fetch_assoc($resultCount);
            $nombre_reactions = $rowCount['total_reactions'];
            $reactions_existant = "SELECT r.type_reaction FROM reaction_reponse_commentaire rp
                JOIN reaction r ON rp.id_reaction = r.id
                WHERE rp.id_reponse_commentaire = $id_reponse_commentaire";
                $result_reactions_existant = mysqli_query($conn, $reactions_existant);
                while($row = mysqli_fetch_assoc($result_reactions_existant))
                {
                    echo $row['type_reaction'];
                }
                if($nombre_reactions != 0)
                {
                    echo $nombre_reactions;
                } 
                echo '</div>';
                echo '</div>';
        }
    } 
    echo '</div>';
} else {
    echo "Comment not found.";
}
echo '<form class="form_ecrire_commentaire" action="reponse_commentaire.php" method="post">';
echo '<input type="hidden" name="fichier" value="voir_reponses.php?id_compte=' . htmlspecialchars($id_compte) . '&id_commentaire=' . htmlspecialchars($id_commentaire) . '&id_publication=' . htmlspecialchars($id_publication) . '">';
echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
echo '<input class="ecrire_commentaire" name="contenu" placeholder="Répondre au commentaire...">';
echo '<br>';
echo '<button type="submit">Répondre</button>';
echo '</form>'; 

// Close the database connection
mysqli_close($conn);
?>

</body>
</html>