<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Ustensile
{

    // attributs
    private $nomUstensile;
    private $idUstensile;
    private $qteUstensile;


    // getters
    public function getNomUstensile()
    {
        return $this->nomUstensile;
    }
    public function getIdUstensile()
    {
        return $this->idUstensile;
    }
    public function getQteUstensile()
    {
        return $this->qteUstensile;
    }

    // constructeur
    public function __construct($n = NULL, $q = NULL)
    {
        if (!is_null($n) && !is_null($q)) {
            $this->nomUstensile = $n;
            $this->qteUstensile = $q;
        }
    }
    // Remplis la dropdown list d'ajout d'ustensile, avec ustensile de la BD
    public static function searchUst()
    {
        $requetePreparee = Connexion::pdo()->prepare("SELECT * FROM Ustensile ORDER BY nomUstensile");
        $requetePreparee->execute();

        $listeIng = $requetePreparee->fetchAll();
        return $listeIng;
    }
    // On fetch tout les ustensiles liés à une recette
    public static function getUstensilesRecette($numRecette)
    {
        $requetePreparee = "SELECT nomUstensile, qteUstensile  FROM Ustensile
                                NATURAL JOIN CompoUstensile
                                WHERE numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Ustensile');
            $ustensiles = $req_prep->fetchAll();

            return $ustensiles;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $ustensiles;
    }
    // Fonction destiné a stcoker les ingredient ajouté via ajax
    public static function liAjaxUst()
    {
        // On forme une array des valeurs choisis par l'utilisateur
        $ustSub = array(
            'nomUstensile' => $_POST['nomUstensile'],
            'qteUstensile' => (string)$_POST['qteUstensile'],
        );
        // On le retourne au controller pour le push dans la $_session
        return $ustSub;
    }
    //--- On va faire passé les information depuis la BD on load----//
    public static function liBDOnLoadUstensile($nom, $qte)
    {
        $ustSub = array(
            'nomUstensile' => $nom,
            'qteUstensile' => $qte,
        );
        return $ustSub;
    }
    //Get numéro d'ingredient pour insertion dans compoIngredien
    public static function getNumUstensileFromName($name)
    {
        $requetePreparee = "SELECT idUstensile FROM Ustensile WHERE nomUstensile = :tag_name;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_name" => $name);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Ustensile');
            $num = $req_prep->fetch();

            return $num;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $num;
    }
    //--------Supprimer un ustensile d'une recette--->
    public static function deleteOldUstensiles($numRecette)
    {
        $requetePreparee = "DELETE FROM CompoUstensile WHERE numRecette = :tag_numRecette;";
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
    public static function deleteUstensile($numRecette, $idUstensile, $quantite)
    {

        $requetePreparee = "DELETE FROM CompoUstensile 
                            WHERE numRecette = :tag_numRecette
                            AND qteUstensile = :tag_quantite
                            AND idUstensile = :tag_id";

        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette,
            "tag_quantite" => $quantite,
            ":tag_id" => $idUstensile,

        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
}
