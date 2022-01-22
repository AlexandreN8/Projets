<?php

require_once("./controller/controllerUtilisateur.php");
require_once("./controller/controllerRecette.php");
require_once("./controller/controllerIngredient.php");
require_once("./controller/controllerUstensile.php");
require_once("./controller/controllerCommentaire.php");
// On cherche si l'action est set, si c'est un type GET ou POST et enfin à quel controler appartient cette action
$action = "recette";
// Dans le controller utilisateur
if (isset($_POST["action"]) && in_array($_POST["action"], get_class_methods("controllerUtilisateur"))) {
    $action = $_POST["action"];
    controllerUtilisateur::$action();
} else if (isset($_GET["action"]) && in_array($_GET["action"], get_class_methods("ControllerUtilisateur"))) {
    $action = $_GET["action"];
    controllerUtilisateur::$action();
}
// Dans le controller recette
else if (isset($_POST["action"]) && in_array($_POST["action"], get_class_methods("controllerRecette"))) {
    $action = $_POST["action"];
    controllerRecette::$action();
} else if (isset($_GET["action"]) && in_array($_GET["action"], get_class_methods("controllerRecette"))) {
    $action = $_GET["action"];
    controllerRecette::$action();
}

// Dans le controller ingredient
else if (isset($_POST["action"]) && in_array($_POST["action"], get_class_methods("controllerIngredient"))) {
    $action = $_POST["action"];
    controllerIngredient::$action();
} else if (isset($_GET["action"]) && in_array($_GET["action"], get_class_methods("controllerIngredient"))) {
    $action = $_GET["action"];
    controllerIngredient::$action();
}

// Dans le controller ingredient
else if (isset($_POST["action"]) && in_array($_POST["action"], get_class_methods("controllerUstensile"))) {
    $action = $_POST["action"];
    controllerUstensile::$action();
} else if (isset($_GET["action"]) && in_array($_GET["action"], get_class_methods("controllerUstensile"))) {
    $action = $_GET["action"];
    controllerUstensile::$action();
}

// Dans le controller ingredient
else if (isset($_POST["action"]) && in_array($_POST["action"], get_class_methods("controllerCommentaire"))) {
    $action = $_POST["action"];
    controllerCommentaire::$action();
} else if (isset($_GET["action"]) && in_array($_GET["action"], get_class_methods("controllerCommentaire"))) {
    $action = $_GET["action"];
    controllerCommentaire::$action();
} else {
    controllerUtilisateur::$action();
}
