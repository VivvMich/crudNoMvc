<?php
include_once "vendor/autoload.php";

use App\Crud\Form;
use App\Crud\BDD;
use App\Crud\User;
use App\Crud\Table;


$form = new Form();
$bdd = new BDD("mysql:host=localhost", "dbname=crudpoo", "root", "");
$jobArray = ['Agent immobillier', 'Architecte', 'Commerce et Artisanat'];
$countryArray = ['Votre Choix...', 'France', 'Angleterre', 'Népal'];
$languageArray = ["Français", "Népalais", "Autruche", "Anglais"];
$leisureArray = ["Sport", "Musique", "Internet", "Voyage", "Lecture", "Autre"];
$bdd->initBdd();

if (isset($_POST["verb"]))
{
    if($_POST["verb"] == "update"){
        $newUser = new User();
        $newUser->createUserToForm($_POST, $_POST['verb']);
        $bdd->update($newUser);
    }
}

if (isset($_POST["verb"]))
{
    if($_POST["verb"] == "create"){
        $newUser = new User();
        $newUser->createUserToForm($_POST, $_POST['verb']);
        $bdd->insert($newUser);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hello, world!</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="?crud=create">Create</a></li>
            <li><a href="?crud=read&id=1">Read</a></li>
            <li><a href="?crud=update&id=1">Update</a></li>
            <li><a href="?crud=delete">Delete</a></li>
        </ul>
    </nav>

    <?php

        if ( isset($_GET['error'])){
            $message = $_GET['error'];
            echo "<h2 class='text-danger text-center'>$message</h2>";
        }

    if ( isset($_GET['succes'])){
        $message = $_GET['succes'];
        echo "<h2 class='text-success text-center'>$message</h2>";
    }

    if(isset($_GET['crud'])){

        if ($_GET["crud"] === "create"){
            $form->createForm("", "post", "container");
            $form->createInput("Nom d'utilisateur ", "text", "", "form-control", "username", "form-label");
            $form->createInput("Mot de passe ", "password", "", "form-control", "password", "form-label");
            $form->createSelect("Profession ", "", $jobArray, "form-control", "job", "form-label", true);
            $form->createSelect("Pays", "", $countryArray, "form-control", "country", "form-label");
            $form->createDoubleRadio("M", "F", "", "M", "F", "form-check-input", "form-check-label", "sex");
            $form->createSelect("Langue ", "", $languageArray, "form-control", "language", "form-label", true);
            $form->createCheckBox($leisureArray, "leisure", "form-check-input mx-1", "form-check-label mx-1", "");
            $form->createHidden("create", "verb");
            $form->createSubmit("text-center my-2","btn btn-primary","Valider");
        }

        if ($_GET["crud"] === "update" && isset($_GET["id"])){
            $user = $bdd->select('user', $_GET["id"]);

            $form->createForm("", "post", "container");
            $form->createInput("Nom d'utilisateur ", "text", $user->getUsername(), "form-control", "username", "form-label");
            $form->createInput("Mot de passe ", "text", $user->getPassword(), "form-control", "password", "form-label");
            $form->createSelect("Profession ", $user->getJob(), $jobArray, "form-control", "job", "form-label", true);
            $form->createSelect("Pays", $user->getCountry(), $countryArray, "form-control", "country", "form-label");
            $form->createDoubleRadio("M", "F", $user->getSex(), "M", "F", "form-check-input", "form-check-label", "sex");
            $form->createSelect("Langue ", $user->getLanguage(), $languageArray, "form-control", "language", "form-label", true);
            $form->createCheckBox($leisureArray, "leisure", "form-check-input mx-1", "form-check-label mx-1", $user->getLeisure());
            $form->createHidden("update", "verb");
            $form->createHidden($user->getId(), "id");
            $form->createSubmit("text-center my-2","btn btn-primary","Valider");
        }

        if ($_GET["crud"] === "delete" && isset($_GET["id"])) {
            $user = $bdd->select('user', $_GET["id"]);
            $bdd->delete($user);
        }

        if($_GET["crud"] === "read"){
            $users = $bdd->selectAll('user');
            Table::drawTable($users);
        }

//        if($_GET["crud"] !== "read" || $_GET["crud"] !== "create" || $_GET["crud"] !== "update" || $_GET["crud"] !== "delete"){
//
//            header("Location:error404.html");
//        }
    }
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>