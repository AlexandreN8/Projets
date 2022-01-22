<?php
require_once("~/../model/Recette.php");
require_once("~/../model/Ingredient.php");
require_once("~/../model/Tag.php");
require_once("~/../model/Ustensile.php");
require_once("~/../model/Etape.php");
require_once("~/../model/Commentaire.php");
require_once("~/../model/Utils.php");


class controllerRecette
{
    //--- Nous permet de savoir si le nombre d'etape sur une soumission de recette a été défini
    private static $fromdefineEtapeBox = "";

    public static function recherche_recettes()
    {
        // Variable permettant d'afficher tout notre HTML
        $allCategories = Recette::recupCategorie();
        $allRecettes = Recette::getAllRecettes();
        $tagRecettes = Tag::recupAllTag();
        $allIngredient = Ingredient::recupIngredient();

        // Terme recherché et requete du modèle
        $optionTag = $_GET['sTag'];
        $optionIng = $_GET['sIng'];
        $terme = $_GET['terme'];

        $nbCom = array(); // On recupere les requetes pour chaque recettes
        foreach ($allRecettes as $recette) {
            array_push($nbCom, Commentaire::nbComTotal($recette->getNumRecette()));
        }

        $recettes_trouvees = Recette::recherche($terme, $optionIng, $optionTag);
        Utils::clearArrayTemp();
        require("~/../vue/rechercheRecette.php");
    }

    //------Permet de definir le nombre de div etape depuis la modal-----
    public static function defineEtapeBox()
    {
        self::$fromdefineEtapeBox = $_POST['nbEtapeForm'];
        self::afficherRecetteForm();
    }

    public static function afficherRecetteForm()
    {
        Utils::clearArrayTemp();
        $showModalNbEtape = false;
        if (!isset($_SESSION['nbEtape'])) {
            $_SESSION['nbEtape'] = "";
            $showModalNbEtape = true; // On affiche la forme pour obtenir le nombre d'étapes
        }

        $lesCat = Recette::recupCategorie(); // Remplis les cat depuis la BD
        $lesTag = Tag::recupAllTag(); // Remplis les tag depuis la BD
        $lesIngredients = Ingredient::recupIngredient();
        $listeIng = Ingredient::searchIng(); // remplis les liste avec les ustensiles de la BD
        $listeUst = Ustensile::searchUst();

        //On set une variable qui va donner soit la valeurs soumise par l'utilisateur aux radio difficulte
        // soit facile s'il n'y a rien de soumis
        $difficulteSoumise = "facile";
        if (isset($_POST['difficulte'])) {
            $difficulteSoumise = $_POST['difficulte'];
        }

        if (self::$fromdefineEtapeBox != "") { // Le nb d'etapes a été renseigné
            $showModalNbEtape = false;  // On n'affiche plus la forme
            $_SESSION['nbEtape'] = self::$fromdefineEtapeBox;
            self::$fromdefineEtapeBox = "";
        }

        require("~/../vue/recetteForm.php");
    }
    // On va fetch toutes les données lié à une recette et les renvoyé vers la pages de détails de la recette
    public static function detailsRecette()
    {
        if (isset($_POST['numRecette'])) {
            $numRecette = $_POST['numRecette'];
        } else {
            $numRecette = $_GET['numRecette'];
        }

        // Récupérer les informations de la recette, la difficulte et la categorie
        $recette = Recette::getDataRecette($numRecette);
        // Recuperer les images
        $imageRecette = Recette::getImagesRecette($numRecette);
        // Recuperer les tag
        $tagRecettes = Tag::getTagsRecette($numRecette);
        // Recuperer les ingredients
        $lesIngredients = Ingredient::getIngredientsRecette($numRecette);
        // Recuperer les ustensiles
        $lesUstensiles = Ustensile::getUstensilesRecette($numRecette);
        // Recuperer les explications
        $lesExplication = Etape::getEtapesRecette($numRecette);
        $nbCom = Commentaire::nbComTotal($numRecette);
        Utils::clearArrayTemp(); // remet à 0 les ustensiles et ingredients d'une création/modif recette


        require("~/../vue/recetteDetails.php");
    }
    public static function recherche_ingredient()
    {
        self::afficherRecetteForm();
    }
    public static function chosePathRecette()
    {
        // On as deux bouton pour une même forme, cette fonction permet de determiner l'action en fonction du bouton
        if (isset($_POST['submit'])) {
            self::creerRecette();
        } else if (isset($_POST['ajouterIng'])) {
            self::recherche_ingredient();
        } else if (isset($_POST['update'])) {
            self::updatedRecette();
        }
    }
    public static function showImage()
    {
        $imageData = Recette::getImageBlob($_POST['idImage']);
        echo "$imageData";
        return $imageData;
    }
    public static function creerRecette()
    {
        // permet de remplir les boutons de selections de la page
        $lesCat = Recette::recupCategorie();
        $lesTag = Tag::recupAllTag();
        $lesIngredients = Ingredient::recupIngredient();
        $listeIng = Ingredient::searchIng();
        $listeUst = Ustensile::searchUst();


        $message = array();
        $fileResult = "";
        if (isset($_POST['submit'])) {

            // Insertion recette
            $nomRecette = htmlspecialchars(trim($_POST['nomRecette']));
            //Variable temps
            $tempsPreparationH = htmlspecialchars(trim($_POST['tempsPreparationH']));
            $tempsPreparationM = htmlspecialchars(trim($_POST['tempsPreparationM']));

            $tempsCuissonH = htmlspecialchars(trim($_POST['tempsCuissonH']));
            $tempsCuissonM = htmlspecialchars(trim($_POST['tempsCuissonM']));

            //Conversion en minute avant insertion
            $tempsPreparation = Utils::hoursToMinutes($tempsPreparationH) +  $tempsPreparationM;
            $tempsCuisson = Utils::hoursToMinutes($tempsCuissonH) +  $tempsCuissonM;


            $tags = array();

            // On cherche les tagActive en retirant la partie numéroté du string
            // Puis on les mets dans une array
            foreach ($_POST as $key => $value) {
                if (strstr($key, "_", true) == "tagActive") {
                    $tags[] = $value;
                }
            }

            // Recuperer les explications par étapes
            foreach ($_POST as $key => $value) {
                if (strstr($key, "_", true) == "boxExplain") {
                    if ($value != "") {
                        $etapes[] = $value;
                    }
                }
            }


            $valid = true;

            // verif image set
            if (isset($_FILES['image'])) {
                if (empty($_FILES['image']['name'][0]) && empty($_FILES['image']['name'][1]) && empty($_FILES['image']['name'][2])) {
                    $message[] = "Vous devez ajouter au moins une image.";
                    $valid = false;
                } else {
                    for ($i = 0; $i < sizeof($_FILES['image']['name']); $i++) {
                        $fname = $_FILES['image']['name'][$i]; // nom de l'image
                        $filesize = $_FILES['image']['size'][$i];
                        $filetype = $_FILES['image']['type'][$i];
                        $filetmp = $_FILES['image']['tmp_name'][$i]; // represente le Blob
                        $tmp = (explode('.', $_FILES['image']['name'][$i]));
                        $fileext = strtolower(end($tmp));

                        $expension = array("jpeg", "jpg", "png");

                        if (!empty($fname)) {
                            if (in_array($fileext, $expension) === false) {
                                $message[] = "L'extension de votre image n°" . $i  . " n'est pas valide. Veuillez selectionner des formats : jpg, jpeg ou png.";
                                $valid = false;
                            }
                            if ($filesize >= 2097152) {
                                $message[] = "Votre fichier" . $i  . " est supérieur à 2MB.";
                                $valid = false;
                            }
                        }
                    }
                }
            } else {
                $message[] = "Vous devez ajouter au moins une image.";
                $valid = false;
            }
            if (isset($_POST['difficulte'])) {
                $temp = Recette::getIdDifficulteFromName($_POST['difficulte']);
                $difficulte = $temp['idDifficulte'];
            }
            if (empty($_SESSION['ingredient_soumis'])) {
                $message[] = "Veuillez saisir au moins 1 ingredient pour votre recette.";
                $valid = false;
            }
            if (empty($_SESSION['ustensile_soumis'])) {
                $message[] = "Veuillez saisir au moins 1 ustensile pour votre recette.";
                $valid = false;
            }
            if (isset($_SESSION['pseudo'])) {
                $login = $_SESSION['pseudo'];
            } else {
                $message[] = "Veuillez vous connecter afin de poster une recette.";
                $valid = false;
            }
            if (isset($_POST['categoriePlatActive'])) {
                $categorie = htmlspecialchars(trim($_POST['categoriePlatActive']));
                // on recupere le numero de categorie pour creer la recette
                $categorie = Recette::numeroCategorie($categorie);
                $categorie = $categorie['idCategorie'];
            } else {
                $message[] = "Veuillez selectionner une catégorie pour votre plat.";
                $valid = false;
            }
            // Check des valeurs null
            if (empty($nomRecette)) {
                $message[] = "Vous devez entrer un nom pour votre recette.";
                $valid = false;
            }
            if ($tempsPreparation == 0) {
                $message[] = "Veuillez indiquer le temps de préparation.";
                $valid = false;
            }
            if (empty($etapes)) {
                $message[] = "Veuillez renseigner les explications de la recette";
                $valid = false;
            }
        }
        if (!$valid) {
            require("~/../vue/recetteForm.php");
        } else {
            $bool = Recette::createRecette($nomRecette, $tempsPreparation, $tempsCuisson, $difficulte, $login, $categorie);
            if ($bool) {
                $bool2 = false;
                $bool3 = false;
                $bool4 = false;
                $bool5 = false;

                $numRecette = Recette::getNumRecetteUserFromName($nomRecette, $login)->getNumRecette();
                //insertion bd tag
                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        Tag::creerRealationTag($tag, $numRecette);
                    }
                }
                // insertion bd etapes
                for ($i = 0; $i < sizeof($etapes); $i++) {
                    $bool2 = Etape::creerEtape($etapes[$i], $i, $numRecette);
                }
                //insertion bd photo
                for ($i = 0; $i < sizeof($_FILES['image']['name']); $i++) {
                    if (!empty($_FILES['image']['name'][$i])) {
                        $blob = file_get_contents($_FILES['image']['tmp_name'][$i]); // on extrait le byte64 du txt pour le stocker en Blob
                        $bool3 = Recette::insertImage($_FILES['image']['name'][$i], $blob, $numRecette);
                    }
                }
                foreach ($_SESSION['ingredient_soumis'] as $ingredient) {
                    $temp = Ingredient::getNumIngredientFromName($ingredient['nomIngredient']);
                    $idIngredient = $temp->getIdIngredient();
                    $bool4 = Recette::ingredientRecette($numRecette, $idIngredient, $ingredient['quantite'], $ingredient['typeMesure']);
                }
                foreach ($_SESSION['ustensile_soumis'] as $ustensile) {
                    $temp = Ustensile::getNumUstensileFromName($ustensile['nomUstensile']);
                    $idUstensile = $temp->getIdUstensile();
                    $bool5 = Recette::UstensileRecette($numRecette, $idUstensile, $ustensile['qteUstensile']);
                }


                if ($bool2) {
                    $fileResult = Recette::recupererImage($numRecette);
                    Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
                    unset($_SESSION['nbEtape']);

                    $message[] = "Votre recette a bien était soumise.";

                    $_GET['numRecette'] = $numRecette;
                    self::detailsRecette();
                }
            } else {
                $message[] = "Une erreure s'est produite lors de la création de votre recette.";
                require("~/../vue/recetteForm.php");
            }
        }
    }

    //----Modifier une recette-----//
    public static function updatedRecette()
    {
        $nomRecette = htmlspecialchars(trim($_POST['nomRecette']));
        $numRecette = $_POST['numRecette'];
        //--- permet de remplir les boutons de selections de la page---//
        $recette = Recette::getDataRecette($numRecette);
        $imageRecette = Recette::getImagesRecette($numRecette);
        $tagRecettes = Tag::getTagsRecette($numRecette);
        $lesIngredients = Ingredient::getIngredientsRecette($numRecette);
        $lesUstensiles = Ustensile::getUstensilesRecette($numRecette);
        $lesCat = Recette::recupCategorie();
        $lesTag = Tag::recupAllTag();
        $listeIng = Ingredient::searchIng();
        $listeUst = Ustensile::searchUst();
        $lesExplication = Etape::getEtapesRecette($numRecette);


        Utils::setArrayTemp(); // Index ingredient_soumis et ustensile_soumis
        foreach ($lesIngredients as $ingredient) {
            $ingSub = Ingredient::liBDOnLoadIng($ingredient->getNomIngredient(), $ingredient->getQuantite(), $ingredient->getTypeMesure());
            array_push($_SESSION['ingredient_soumis'], $ingSub);
        }
        foreach ($lesUstensiles as $ustensile) {
            $ustSub = Ustensile::liBDOnLoadUstensile($ustensile->getNomUstensile(), $ustensile->getQteUstensile());
            array_push($_SESSION['ustensile_soumis'], $ustSub);
        }
        // On va maintenant distinguer les tag appartenant à la recette des autres
        $tmp = [];
        for ($i = 0; $i < sizeof($lesTag); $i++) {
            for ($j = 0; $j < sizeof($tagRecettes); $j++) {
                if (($lesTag[$i]->getNomTag() == $tagRecettes[$j]->getNomTag())) {
                    array_push($tmp, $i);
                }
            }
        }
        foreach ($tmp as $i) {
            unset($lesTag[$i]);
        }

        //Re-convertir les minutes en heures-minute
        $tmp = Utils::minutesToHours($recette->getTempsPreparation());
        $arrayPrepa = explode(":", $tmp);
        $tmp = Utils::minutesToHours($recette->getTempsCuisson());
        $arrayCuisson = explode(":", $tmp);
        //--- Fin du remplissage---//

        $message = array();
        $fileResult = "";
        if (isset($_POST['update'])) {

            // Insertion recette
            //Variable temps
            $tempsPreparationH = htmlspecialchars(trim($_POST['tempsPreparationH']));
            $tempsPreparationM = htmlspecialchars(trim($_POST['tempsPreparationM']));

            $tempsCuissonH = htmlspecialchars(trim($_POST['tempsCuissonH']));
            $tempsCuissonM = htmlspecialchars(trim($_POST['tempsCuissonM']));

            //Conversion en minute avant insertion
            $tempsPreparation = Utils::hoursToMinutes($tempsPreparationH) +  $tempsPreparationM;
            $tempsCuisson = Utils::hoursToMinutes($tempsCuissonH) +  $tempsCuissonM;

            $temp = Recette::getIdDifficulteFromName($_POST['difficulte']);
            $difficulte = $temp['idDifficulte'];

            $tags = array();

            // On cherche les tagActive en retirant la partie numéroté du string
            // Puis on les mets dans une array
            foreach ($_POST as $key => $value) {
                if (strstr($key, "_", true) == "tagActive") {
                    $tags[] = $value;
                }
            }

            // Recuperer les explications par étapes
            foreach ($_POST as $key => $value) {
                if (strstr($key, "_", true) == "boxExplain") {
                    if ($value != "") {
                        $etapes[] = $value;
                    }
                }
            }


            $valid = true;

            if (empty($_SESSION['ingredient_soumis'])) {
                $message[] = "Veuillez saisir au moins 1 ingredient pour votre recette.";
                $valid = false;
            }
            if (empty($_SESSION['ustensile_soumis'])) {
                $message[] = "Veuillez saisir au moins 1 ustensile pour votre recette.";
                $valid = false;
            }
            if (isset($_SESSION['pseudo'])) {
                $login = $_SESSION['pseudo'];
            } else {
                $message[] = "Veuillez vous connecter afin de poster une recette.";
                $valid = false;
            }
            if (isset($_POST['categoriePlatActive'])) {
                $categorie = htmlspecialchars(trim($_POST['categoriePlatActive']));
                // on recupere le numero de categorie pour creer la recette
                $categorie = Recette::numeroCategorie($categorie);
                $categorie = $categorie['idCategorie'];
            } else {
                $message[] = "Veuillez selectionner une catégorie pour votre plat.";
                $valid = false;
            }
            // Check des valeurs null
            if (empty($nomRecette)) {
                $message[] = "Vous devez entrer un nom pour votre recette.";
                $valid = false;
            }
            if ($tempsPreparation == 0) {
                $message[] = "Veuillez indiquer le temps de préparation.";
                $valid = false;
            }
            if (empty($etapes)) {
                $message[] = "Veuillez renseigner les explications de la recette";
                $valid = false;
            }
        }
        if (!$valid) {
            self::detailsRecette();
        } else {
            $bool = Recette::updateRecette($numRecette, $nomRecette, $tempsPreparation, $tempsCuisson, $difficulte, $categorie);
            if ($bool) {
                $bool2 = false;

                Tag::deleteOldTag($numRecette);
                //insertion bd tag
                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        Tag::creerRealationTag($tag, $numRecette);
                    }
                }
                // insertion bd etapes
                for ($i = 0; $i < sizeof($etapes); $i++) {
                    $bool2 = Etape::updateEtape($etapes[$i], $i, $numRecette);
                }

                // Pour éviter les doublons
                Ingredient::deleteOldIngredients($numRecette);
                foreach ($_SESSION['ingredient_soumis'] as $ingredient) {
                    $temp = Ingredient::getNumIngredientFromName($ingredient['nomIngredient']);
                    $idIngredient = $temp->getIdIngredient();
                    Recette::ingredientRecette($numRecette, $idIngredient, $ingredient['quantite'], $ingredient['typeMesure']);
                }
                Ustensile::deleteOldUstensiles($numRecette);
                foreach ($_SESSION['ustensile_soumis'] as $ustensile) {
                    $temp = Ustensile::getNumUstensileFromName($ustensile['nomUstensile']);
                    $idUstensile = $temp->getIdUstensile();
                    Recette::UstensileRecette($numRecette, $idUstensile, $ustensile['qteUstensile']);
                }


                if ($bool2) {
                    $fileResult = Recette::recupererImage($numRecette);
                    Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
                    $message[] = "Votre recette a bien était modifié.";

                    $_GET['numRecette'] = $numRecette;
                    self::detailsRecette();
                }
            } else {
                $message[] = "Une erreure s'est produite lors de la modification de votre recette.";
                require("~/../vue/updateRecette.php");
            }
        }
    }
}
