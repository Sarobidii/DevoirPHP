<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    if (isset($_POST['id_ami'])) {
        $id_ami = $_POST['id_ami']; 

        $sql = "INSERT INTO amis (id_compte, id_compte_amis) VALUES ($id_user, $id_ami)";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header("Location: suggestion.php");
        } else {
            echo '<p>Erreur lors de l\'ajout en tant qu\'ami.</p>';
        }
    }
} else {
    header("Location: login_form.php");
    exit();
}
?>
