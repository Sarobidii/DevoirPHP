<?php
    include 'db_connect.php';

    if(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["email"]) && isset($_POST["mdp"]) && isset($_POST["confirmer_mdp"]))
    {
        $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
        $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $mdp = mysqli_real_escape_string($conn, $_POST["mdp"]);
        $confirmer_mdp = mysqli_real_escape_string($conn, $_POST["confirmer_mdp"]);

        if($mdp != $confirmer_mdp)
        {
            $erreur = "Les mots de passe ne correspondent pas.";
            header("Location: inscription.php?erreur=$erreur");
            exit();
        }

        $verification = "SELECT * FROM compte WHERE (nom = '$nom' AND prenom = '$prenom') OR email = '$email'";
        $resultat = mysqli_query($conn, $verification);

        if(mysqli_num_rows($resultat) > 0) {
            $erreur = "Cet utilisateur existe déjà.";
            header("Location: inscription.php?erreur=$erreur");
            exit();
        } else {
            $nouveau_utilisateur = "INSERT INTO compte (nom, prenom, email, mdp) 
                                    VALUES ('$nom', '$prenom', '$email', '$mdp')";
            $insert = mysqli_query($conn, $nouveau_utilisateur);

            if(!$insert) {
                echo "Erreur: " . mysqli_error($conn);
            } else {
                header("Location: login_form.php");
                exit();
            }
        }
    }
?>
