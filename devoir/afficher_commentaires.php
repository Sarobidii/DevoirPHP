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

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}
$id_compte = $_SESSION['id_user'];

if (isset($_POST['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_POST['id_publication']);
} elseif (isset($_GET['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
} else {
    echo 'Invalid request. Publication ID not provided.';
    exit();
}

// Fetch the publication data
$sqlPublication = "
    SELECT p.id, p.contenu, c.nom, c.prenom, p.date_lance
    FROM publication p
    JOIN compte c ON p.id_compte = c.id
    WHERE p.id = $id_publication";

$resultPublication = mysqli_query($conn, $sqlPublication);

if (mysqli_num_rows($resultPublication) > 0) {
    $rowPublication = mysqli_fetch_assoc($resultPublication);

    // Display the publication
    echo '<div class="publication">';
    echo '<div class="nom_prenom">';
    echo '<div class="h-10 w-10 overflow-hidden rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="h-10 w-10 p-2 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    ';

    echo '<p>' . htmlspecialchars($rowPublication['prenom']) . ' ' . htmlspecialchars($rowPublication['nom']);
    echo '</br><small>' . htmlspecialchars($rowPublication['date_lance']) . '</small>';
    echo '</p>';
    echo '</div>';
    echo '<p class="contenu">' . htmlspecialchars($rowPublication['contenu']) . '</p>';

    $id_publication = $rowPublication['id'];
    $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_publication WHERE id_publication = $id_publication";
    $resultCount = mysqli_query($conn, $sqlCount);
    $rowCount = mysqli_fetch_assoc($resultCount);
    $nombre_reactions = $rowCount['total_reactions'];

    echo '<div class="nombre_reactions_commentaires">';
    if ($nombre_reactions > 0) {
        echo '<form class="voir_reaction" action="afficher_reactions.php" method="get">';
        echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
        echo '<input type="submit" value="';
        $reactions_existant = "SELECT r.type_reaction FROM reaction_publication rp
        JOIN reaction r ON rp.id_reaction = r.id
        WHERE rp.id_publication = $id_publication";
        $result_reactions_existant = mysqli_query($conn, $reactions_existant);
        while($row = mysqli_fetch_assoc($result_reactions_existant))
        {
            echo $row['type_reaction'];
        }
        if($nombre_reactions != 0)
        {
            echo $nombre_reactions .'">';
        } 
        echo '</div>';
        echo '</form>';
    } 
    echo '<div class="buttons">';
    echo '<div class="reaction-container_' . htmlspecialchars($id_publication) . '">';
    $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_publication rp
            JOIN reaction r ON rp.id_reaction = r.id
            WHERE rp.id_publication = $id_publication AND rp.id_compte = $id_compte";
    $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
    if(mysqli_num_rows($resultUserReaction) > 0) {
        $userReaction = mysqli_fetch_assoc($resultUserReaction);
        echo '<div class="like-button_' . htmlspecialchars($id_publication) . '">' . $userReaction['type_reaction'];
        echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
        echo $userReaction['nom_reaction'];
        echo '</span> ';
        echo '</div>';     
    } else {
        echo '<div class="like-button_' . htmlspecialchars($id_publication) . '">👍 J\'aime';
        echo '</div>';  
    }
            echo '<div class="reaction-icons_' . htmlspecialchars($id_publication) . '">';
            include 'db_connect.php';
            $sql2 = "SELECT id, type_reaction FROM reaction";
            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2) > 0) {
                mysqli_data_seek($result2, 0);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<div class='reaction_" . htmlspecialchars($id_publication) . "' data-reaction='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</div>";
                }
            }
            echo '</div>';
            echo '</div>'; 
            echo '<script src="essai.js"></script>'; 
            echo '<script>';
            echo "const reactions" . htmlspecialchars($id_publication) . " = document.querySelectorAll('.reaction_" . htmlspecialchars($id_publication) . "');";
            echo "const likeButton" . htmlspecialchars($id_publication) . " = document.querySelector('.like-button_" . htmlspecialchars($id_publication) . "');";
            echo "likeButton" . htmlspecialchars($id_publication) . ".addEventListener('click', function() {";
            echo "    const reactionId = this.getAttribute('data-reaction');"; // Assurez-vous que le bouton a cet attribut
            echo "    let form = document.createElement('form');";
            echo "    form.method = 'POST';";
            echo "    form.action = 'supprimer_reaction.php';";

            // Ajoutez les champs cachés pour l'ID de la publication et la réaction
            echo "    addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
            echo "    addHiddenInput(form, 'fichier', 'afficher_commentaires.php?id_publication=$id_publication');";

            echo "    document.body.appendChild(form);";
            echo "    form.submit();";
            echo "});";
            echo "reactions" . htmlspecialchars($id_publication) . ".forEach(reaction => {";
            echo "    reaction.addEventListener('click', function() {";
            echo "        const reactionId = this.getAttribute('data-reaction');";
            echo "        let form = document.createElement('form');";
            echo "        form.method = 'POST';";
            echo "        form.action = 'reaction.php';";

            echo "        addHiddenInput(form, 'reaction_" . htmlspecialchars($id_publication) . "', reactionId);";
            echo "        addHiddenInput(form, 'id_publication', " . htmlspecialchars($id_publication) . ");";
            echo "        addHiddenInput(form, 'fichier', 'afficher_commentaires.php?id_publication=" . htmlspecialchars($id_publication) . "');";

            echo "        document.body.appendChild(form);";
            echo "        form.submit();";
            echo "    });";
            echo "});"; 

            echo '</script>';

            echo '<div class="emoji_commenter"><a href="afficher_commentaires.php?id_publication='. $id_publication . '">💬 Commenter</a></div>';
    echo '</div>';
    echo '</div>'; // End of publication div

    echo '<div class="ensemble_commentaires">';
    
    // Now fetch and display comments
    $sql = "
        SELECT rp.date_lance, rp.id, rp.id_compte, rp.contenu, c.nom, c.prenom
        FROM commentaire rp
        JOIN compte c ON rp.id_compte = c.id
        WHERE rp.id_publication = $id_publication";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
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
            echo "<p class='nom_prenom'>" . $row['prenom'] . ' ' . $row['nom'] . '<br>';
            echo '</div>'; // End of commentaire div
            echo '<div class="contenu_commentaire">' . htmlspecialchars($row['contenu']) . '</div>';
            $id_commentaire = $row['id'];
            echo '</div>'; // End of commentaire div
            echo '<div class="bouton_commentaire">';
            echo "<div><small class='date_lance_commentaire'> " . htmlspecialchars($row['date_lance']) . "</small></div>";
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
            echo "    addHiddenInput(form, 'fichier', 'afficher_commentaires.php?id_publication=$id_publication');";
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
            echo "        addHiddenInput(form, 'fichier', 'afficher_commentaires.php?id_publication=" . htmlspecialchars($id_publication) . "');";

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
        }
    }
    echo '</div>'; 
} else {
    echo 'No publication found with this ID.';
}
echo '<form class="form_ecrire_commentaire" action="commentaire.php" method="post">';
    echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
    echo '<input class="ecrire_commentaire" name="contenu" placeholder="Ecrivez un commentaire...">';
    echo '<button type="submit">Envoyer</button>';
    echo '</form>';
?>

</body>
</html>