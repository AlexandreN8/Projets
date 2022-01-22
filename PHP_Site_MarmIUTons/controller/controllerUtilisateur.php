<?php

require_once("~/../model/Utilisateur.php");
require_once("~/../model/Recette.php");
require_once("~/../model/Etape.php");
require_once("~/../model/Commentaire.php");
require_once("~/../model/Tag.php");
require_once("~/../model/Ingredient.php");
require_once("~/../model/Ustensile.php");
require_once("~/../model/Utils.php");


class controllerUtilisateur
{
    public static function modifierProfileSecu()
    {
        $lesInfos = Utilisateur::getUserData($_SESSION['pseudo']);
        if (!empty($_POST['email'])) {
            $email = htmlspecialchars($_POST['email']);
            Utilisateur::updateUtilisateurEmail($email, $_SESSION['pseudo']);
        }

        if (!empty($_POST['ancienMdp']) && !empty($_POST['confirmMdp']) && !empty($_POST['nouveauMdp'])) {
            $ancienMdp = htmlspecialchars($_POST['ancienMdp']);
            $nouveauMdp = htmlspecialchars($_POST['nouveauMdp']);
            $confirmMdp = htmlspecialchars($_POST['confirmMdp']);
            Utilisateur::updateUtilisateurSecu($ancienMdp, $nouveauMdp, $confirmMdp, $lesInfos->getPass(), $_SESSION['pseudo']);
        }

        self::profile();
    }


    public static function modifierProfile()
    {
        $lesInfos = Utilisateur::getUserData($_SESSION['pseudo']);
        $nouveauNom = $lesInfos->getNom();
        $nouveauPrenom = $lesInfos->getPrenom();
        $bio = $lesInfos->getBio();

        if (!empty($_POST['nouveauNom'])) {
            $nouveauNom = htmlspecialchars($_POST['nouveauNom']);
        }
        if (!empty($_POST['nouveauPrenom'])) {
            $nouveauPrenom = htmlspecialchars($_POST['nouveauPrenom']);
        }
        if (!empty($_POST['bio'])) {
            $bio = htmlspecialchars($_POST['bio']);
        }
        $login = $_SESSION['pseudo'];
        $bool = Utilisateur::updateUtilisateur($nouveauNom, $nouveauPrenom, $bio, $login);
        if ($bool) {
            $_SESSION['nomUtilisateur'] =  $_POST['nouveauNom'];
            $_SESSION['prenomUtilisateur'] = $_POST['nouveauPrenom'];
        }
        self::profile();
    }
    public static function accueil()
    {
        Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
        require("~/../vue/index.php");
    }
    public static function inscription()
    {
        Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
        require("~/../vue/register.php");
    }
    public static function login()
    {
        Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
        require("~/../vue/login.php");
    }
    public static function recette()
    {
        $allCategories = Recette::recupCategorie();
        $allRecettes = Recette::getAllRecettes();
        $tagRecettes = Tag::recupAllTag();
        $allIngredient = Ingredient::recupIngredient();
        $nbCom = array();
        foreach ($allRecettes as $recette) {
            array_push($nbCom, Commentaire::nbComTotal($recette->getNumRecette()));
        }
        require("~/../vue/recette.php");
    }
    // Variable globale, besoin de la partager entre login() et upodateRole() 
    // pour savoir si l'action vient du routeur
    private static $fromUpdateRole = "";

    public static function profile()
    {
        // Restreint l'accès à la pages aux session True (empeche les bannis, et session expiré)
        if (isset($_SESSION) && $_SESSION['logged_in'] == true) {
            $lesComms = Commentaire::getCommentaireProfile($_SESSION['pseudo']);
            $lesInfos = Utilisateur::getUserData($_SESSION['pseudo']);
            $lesRecettes = Recette::getAllRecettesUser();
            $lesUtilisateurs = Utilisateur::getAllUser();
            // on utilise les variable globale pour faire apparaitre le modal d'updateRole
            // Puis on reset a false la valeur d'affichage du modal
            $showModal = false;
            $successValue = false;
            if (self::$fromUpdateRole != "") {
                $showModal = true; // L'action vient d'update role, on affiche le modal de succès ou d'echec dans la view
                $boolRequete = self::$fromUpdateRole; // on récupère la valeur de retour de la requete
                self::$fromUpdateRole = "";
            }

            $noteTotale = 0;
            $nbNoteUser = 0;
            foreach ($lesComms as $commentaire) {
                $noteTotale += $commentaire->getNote();
                $nbNoteUser++;
            }
            if ($noteTotale != 0) { // on fait la moyenne si il y a des notes
                $noteTotale = bcdiv($noteTotale / $nbNoteUser, 1, 1);
            }
            Utils::clearArrayTemp(); // On remet à 0 la liste d'ingredient/ustensile ajouté pour la recette soumise
            require("~/../vue/profile.php");
        }
    }
    //-----Modifier role utilisateur (commande admin) ----/
    public static function updateRole()
    {
        $newRole = "";
        foreach ($_POST as $key => $value) {
            if (strstr($key, "_", true) == "btnradio") {
                $newRole = $value;
            }
        }
        $login = $_POST['loginRole'];
        self::$fromUpdateRole = Utilisateur::updatedRole($newRole, $login);
        self::profile();
    }
    public static function detailsRecetteFromProfil()
    {
        $tmp = Recette::getNumRecetteUserFromName($_GET['nomRecette'], $_SESSION['pseudo']);
        $numRecette = $tmp->getNumRecette();
        // Récupérer les informations de la recette, la difficulte et la categorie
        $recette = Recette::getDataRecette($numRecette);
        // Recuperer )les images
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

        require("~/../vue/recetteDetails.php");
    }

    public static function updateRecette()
    {
        if (isset($_GET['nomRecette'])) { // Si ca passe par un user
            $tmp = Recette::getNumRecetteUserFromName($_GET['nomRecette'], $_SESSION['pseudo']);
            $numRecette = $tmp->getNumRecette();
        } else if ($_GET['numRecette']) { // Si ca passe par l'administrateur
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
        $lesCat = Recette::recupCategorie(); // Remplis les cat depuis la BD
        $lesTag = Tag::recupAllTag(); // Remplis les tag depuis la BD
        $listeIng = Ingredient::searchIng(); // remplis les liste avec les ustensiles de la BD
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

        require("~/../vue/updateRecette.php");
    }

    public static function deleteRecetteUser()
    {
        $tmp = Recette::getNumRecetteUserFromName($_GET['nomRecette'], $_SESSION['pseudo']);
        $numRecette = $tmp->getNumRecette();

        Recette::deleteRecetteFromUser($numRecette);

        self::profile();
    }
    //-----Fonction reservé à l'administrateur qui peut supprimer depuis l'index-----
    public static function deleteRecetteByNum()
    {
        $numRecette = $_GET['numRecette'];

        Recette::deleteRecetteFromUser($numRecette);

        self::recette();
    }
    public static function created()
    {
        $message = "";
        if (!empty($_POST)) {
            // Recuperation des données POST
            $pseudo = htmlspecialchars(trim($_POST['pseudo'])); // Trim() = on empêche les éspaces htmlspecialchars() : protège de certaines injections
            $email = trim($_POST['email']);
            $nom = htmlspecialchars(trim($_POST['nom']));
            $prenom = htmlspecialchars(trim($_POST['prenom']));
            $genre = htmlspecialchars($_POST['genre']);
            $password = htmlspecialchars($_POST['password']);
            if (empty($_POST['tel'])) {
                $tel = "00.00.00.00.00";
            } else {
                $tel = htmlspecialchars(trim($_POST['tel']));
            }
            $valid = true;

            // Vérification que toutes les cases soient bien remplis
            if (empty($pseudo)) {
                $message = "ATTENTION : Vous devez remplir votre pseudo";
                $valid = false;
            } else if (empty($email)) {
                $message = "Veuillez renseigner un email";
                $valid = false;
            } else if (empty($nom)) {
                $message = "Veuillez renseigner votre nom";
                $valid = false;
            } else if (empty($prenom)) {
                $message = "Veuillez renseigner un prénom";
                $valid = false;
            } else if (empty($genre)) {
                $message = "Veuillez cocher la case correspondante à votre genre.";
                $valid = false;
            } else if (empty($password)) {
                $message = "Veuillez renseigner un mot de passe.";
                $valid = false;
            } else if (!empty($password) && !empty($_POST['passwordConf'])) {
                if ($password != $_POST['passwordConf']) {
                    $message = "Vos mot de passes ne correspondent pas";
                    $valid = false;
                } else if ((strlen($password) < 8)) {
                    $message = "Votre mot de passe doit contenu au minimum 8 caractères.";
                    $valid = false;
                }
            }

            if ($valid) {
                // Verification des données qui doivent êtres uniques
                $result = Utilisateur::emailExist($email); // verification mail pas déjà dans la base
                if (!empty($result)) {
                    $message = "Un utilisateur avec cet email existe déjà.";
                    $valid = false;
                }
                $result = Utilisateur::telExist($tel); // Verification tel pas déjà dans la base
                if (!empty($result)) {
                    $message = "Un utilisateur avec cet numéro de téléphonne existe déjà.";
                    $valid = false;
                }
                if ($valid) {
                    $put = Utilisateur::addUser($pseudo, $password, $email, $nom, $prenom, $tel, $genre); // On verifie que le pseudo soit pas déjà dans la base
                    if ($put) {
                        $valid = true;
                    } else {
                        $message = "Pseudo déjà utilisé.";
                        $valid = false;
                    }
                }
            }
        }
        if (!$valid) { // Si un paramètre est invalide on reviens sur la page d'inscription
            require("~/../vue/register.php");
        } else { // Sinon on va sur le profil
            self::connectionUser();
        }
    }

    public static function connectionUser()
    {
        if (isset($_POST["pseudo"])) {
            $pseudo = htmlspecialchars($_POST["pseudo"]);
            $result = Utilisateur::pseudoExist($pseudo);
            $message = "";

            if (empty($result)) {
                $message = "Nous ne parvenons pas à trouver votre compte";
                require("~/../vue/login.php");
            } else {
                $user = Utilisateur::getUserData($pseudo);
                $password = crypt($_POST["password"], '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
                if ($password == $user->getPass()) {
                    $_SESSION['pseudo'] = $user->getLogin();
                    $_SESSION['email'] = $user->getEmail();
                    $_SESSION['tel'] = $user->getTel();
                    $_SESSION['nom'] = $user->getNom();
                    $_SESSION['prenom'] = $user->getPrenom();
                    $_SESSION['genre'] = $user->getGenre();
                    $_SESSION['role'] = $user->getRole();
                    $_SESSION['creation'] = substr($user->getCreation(), 0, 10);

                    if ($_SESSION['role'] != 'Bannis') {
                        $_SESSION['logged_in'] = true;
                        self::profile();
                    } else {
                        $message = "Ce compte utilisateur est banni.";
                        session_unset();
                        require("~/../vue/login.php");
                    }
                } else {
                    $message = "Nous ne parvenons pas à trouver votre compte.";
                    require("~/../vue/login.php");
                }
            }
        }
    }
    //----Deconnection----//
    public static function signout()
    {
        session_destroy();
        $_SESSION['logged_in'] = false;
        self::login();
    }
}
