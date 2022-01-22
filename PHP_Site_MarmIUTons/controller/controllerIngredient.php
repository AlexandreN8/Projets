<?php
require_once("~/../model/Commentaire.php");

class controllerIngredient
{
    // Fonction d'ajout d'ingredient à la création d'une recette
    public static function addLiIngredient()
    {
        // On crée un index dans la session qui recupéreras les données des ingrédients
        if (!isset($_SESSION['ingredient_soumis'])) {
            $_SESSION['ingredient_soumis'] = array();
        }
        // On forme une array des valeurs choisis par l'utilisateur
        $ingSub = array(
            'nomIngredient' => $_POST['nomIngredient'],
            'quantite' => (string)$_POST['quantite'],
            'typeMesure' => $_POST['typeMesure'],
        );

        //on la push dans la session
        if ($_POST['quantite'] != "NaN" && !empty($_POST['nomIngredient'])) {
            array_push($_SESSION['ingredient_soumis'], $ingSub);
            $_SESSION['ingredient_soumis'] = array_values($_SESSION['ingredient_soumis']);
        }
    }

    public static function delLiIngredient()
    {
        // On doit distinguer les div qui viennent d'être créé dans la $_SESSION de celle récupérer dans la BD
        $dynamiqueIngredient = false;
        if (isset($_POST['name']) && $_POST['name'] == 'removeUpdate') {
            echo var_dump("bd one");
            self::deleteIngredientRecette($_POST['numRecette'], $_POST['ingredient'], $dynamiqueIngredient = false); // Dans la BD
        } else {
            echo var_dump("dynamic one");
            self::deleteIngredientRecette("", $_POST['ingredient'], $dynamiqueIngredient = true); // Div dynamique dans la SESSION
        }
    }
    //---- On veux supprimer un ingredient soit de la session s'il n'est pas encore dans la BD
    // Soit on le supprime de la Base et de la session dans le cas ou on est dans updateRecette
    public static function deleteIngredientRecette($numRecette, $nomIng, $dynamiqueIngredient)
    {
        $ingredient = explode(" ", $nomIng);
        $temp = explode("-", $nomIng); // permet de retrouver les noms composés de plusieurs mots
        $nomComplet = trim($temp[1]);

        $quantite = $ingredient[0];
        $typeMesure = $ingredient[1];

        // On unset en dehors de la boucle pour éviter les offset, nécéssite ces variables
        $i = 0;
        $trouve = false;
        if (isset($_SESSION['ingredient_soumis'])) {
            foreach ($_SESSION['ingredient_soumis'] as $ingredient) {
                if ($nomComplet == $ingredient['nomIngredient']) {
                    $trouve = true;
                    break;
                }
                $i++;
            }
            if ($trouve) {
                unset($_SESSION['ingredient_soumis'][$i]);
            }
            //On réindexe les keys
            $_SESSION['ingredient_soumis'] = array_values($_SESSION['ingredient_soumis']);
        }

        if ($dynamiqueIngredient == false) { // Div dans la BD
            $idIngredient = Ingredient::getNumIngredientFromName($nomComplet)->getIdIngredient();
            $bool = Ingredient::deleteIngredient($numRecette, $idIngredient, $quantite, $typeMesure);
        }
    }
}
