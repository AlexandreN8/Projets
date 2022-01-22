<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Ingredient
{

    // attributs
    private $nomIngredient;
    private $idIngredient;
    private $quantite;
    private $typeMesure;

    // getters
    public function getNomIngredient()
    {
        return $this->nomIngredient;
    }
    public function getIdIngredient()
    {
        return $this->idIngredient;
    }
    public function getQuantite()
    {
        return $this->quantite;
    }
    public function getTypeMesure()
    {
        return $this->typeMesure;
    }



    // setters
    public function setNomIngredient($n)
    {
        $this->nomIngredient = $n;
    }


    // constructeur
    public function __construct($n = NULL, $i = NULL, $q = NULL, $tm = NULL)
    {
        if (!is_null($n)) {
            $this->nomIngredient = $n;
        } else if (!is_null($n) && !is_null($i) && !is_null($q) && !is_null($tm)) {
            $this->nomIngredient = $n;
            $this->idIngredient = $i;
            $this->quantite = $q;
            $this->typeMesure = $tm;
        }
    }
    public static function recupIngredient()
    {
        $requete = "SELECT * FROM Ingredient;";
        $resultat = Connexion::pdo()->query($requete);
        $resultat->setFetchMode(PDO::FETCH_CLASS, 'Ingredient');
        $tableauIngredient = $resultat->fetchAll();
        return $tableauIngredient;
    }
    // On fetch tout les ingrédients liés à une recette
    public static function getIngredientsRecette($numRecette)
    {
        $requetePreparee = "SELECT nomIngredient, quantite, typeMesure FROM Ingredient I
                                JOIN TypeIngredient TI
                                WHERE I.idIngredient = TI.idIngredient
                                AND TI.numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Ingredient');
            $ingredients = $req_prep->fetchAll();

            return $ingredients;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $ingredients;
    }
    // Remplis la dropdown list d'ajout d'ingredient, avec ingredient de la BD
    public static function searchIng()
    {
        $requetePreparee = Connexion::pdo()->prepare("SELECT * FROM Ingredient ORDER BY nomIngredient");
        $requetePreparee->execute();

        $listeIng = $requetePreparee->fetchAll();
        return $listeIng;
    }
    //--- On va faire passé les information depuis la BD on load----//
    public static function liBDOnLoadIng($nIng, $qte, $mesure)
    {
        $ingSub = array(
            'nomIngredient' => $nIng,
            'quantite' => $qte,
            'typeMesure' => $mesure,
        );
        return $ingSub;
    }
    // Dans la page update on veux les suprimer à la fois dans la BD et la session

    //Get numéro d'ingredient pour insertion dans compoIngredien
    public static function getNumIngredientFromName($name)
    {
        $requetePreparee = "SELECT idIngredient FROM Ingredient WHERE nomIngredient = :tag_name;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_name" => $name);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Ingredient');
            $num = $req_prep->fetch();

            return $num;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }
    //-----Supprimé les anciens ingredients avant d'en inserer de nouveau pour l'updateRecette---- //
    public static function deleteOldIngredients($numRecette)
    {
        $requetePreparee = "DELETE FROM TypeIngredient WHERE numRecette = :tag_numRecette;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
    //---Supprimer les ingredient de la base, nécéssaire pour l'update et ajax----/
    public static function deleteIngredient($numRecette, $idIngredient, $quantite, $typeMesure)
    {

        $requetePreparee = "DELETE FROM TypeIngredient 
                            WHERE numRecette = :tag_numRecette
                            AND quantite = :tag_quantite
                            AND idIngredient = :tag_id
                            AND typeMesure = :tag_typeMesure;";

        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette,
            "tag_quantite" => $quantite,
            ":tag_id" => $idIngredient,
            ":tag_typeMesure" => $typeMesure
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
}
