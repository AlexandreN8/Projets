<?php
require_once("~/../model/Ustensile.php");

class controllerUstensile
{
    // Fonction d'ajout d'ustensiles à la création d'une recette
    public static function addLiUstensile()
    {
        // On crée un index dans la session qui recupéreras les données des ingrédients
        if (!isset($_SESSION['ustensile_soumis'])) {
            $_SESSION['ustensile_soumis'] = array();
        }
        // On récupère l'array formé via ajax
        $ustSub = Ustensile::liAjaxUst();
        //on la push dans la session
        if ($_POST['qteUstensile'] != "NaN" && !empty($_POST['nomUstensile'])) {
            array_push($_SESSION['ustensile_soumis'], $ustSub);
        }
    }
    public static function delLiUstensile()
    {
        // On doit distinguer les div qui viennent d'être créé dans la $_SESSION de celle récupérer dans la BD
        $dynamiqueIngredient = false;
        if (isset($_POST['name']) && $_POST['name'] == 'removeUpdate') {
            echo var_dump("bd one");
            self::deleteUstensileRecette($_POST['numRecette'], $_POST['ustensile'], $dynamiqueIngredient = false); // Dans la BD
        } else {
            echo var_dump("dynamic one");
            self::deleteUstensileRecette($_POST['numRecette'], $_POST['ustensile'], $dynamiqueIngredient = true); // Div dynamique dans la SESSION
        }
    }
    //---- La même chose que pour au dessus avec les ingrédients, mais une structure de donnée différente
    public static function deleteUstensileRecette($numRecette, $ust, $dynamiqueIngredient)
    {
        $ustensile = explode("-", $ust);
        $nomComplet = trim($ustensile[1]);

        $quantite = trim($ustensile[0]);

        // On unset en dehors de la boucle pour éviter les offset, nécéssite ces variables
        $i = 0;
        $trouve = false;
        if (isset($_SESSION['ustensile_soumis'])) {
            foreach ($_SESSION['ustensile_soumis'] as $ustensile) {
                if ($nomComplet == $ustensile['nomUstensile']) {
                    $trouve = true;
                    break;
                }
                $i++;
            }
            if ($trouve) {
                unset($_SESSION['ustensile_soumis'][$i]);
            }
            //On réindexe les keys
            $_SESSION['ustensile_soumis'] = array_values($_SESSION['ustensile_soumis']);
        }

        if ($dynamiqueIngredient == false) { // Div dans la BD
            $idUstensile = Ustensile::getNumUstensileFromName($nomComplet)->getIdUstensile();
            $bool = Ustensile::deleteUstensile($numRecette, $idUstensile, $quantite);
        }
    }
}
