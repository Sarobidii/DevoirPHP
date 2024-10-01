<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Formulaire d'inscription</h2>
    <form action="insert.php" method="post">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" ><br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" ><br><br>

        <label for="email">Adresse email :</label>
        <input type="email" name="email"><br><br>

        <?php
            if(isset($_GET['erreur']))
            {
                echo $_GET['erreur'];
            }
        ?>
        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp"><br><br>

        <label for="confirmer_mdp">Confirmer le mot de passe :</label>
        <input type="password" name="confirmer_mdp">

        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>

