<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Etape
{

    // attributs
    private $idEtape;
    private $descriptionEtape;
    private $posEtape;

    // getters
    public function getIdEtape()
    {
        return $this->idEtape;
    }
    public function getDescriptionEtape()
    {
        return $this->descriptionEtape;
    }
    public function getPosEtape()
    {
        return $this->posEtape;
    }



    // constructeur
    public function __construct($i = NULL, $d = NULL, $p = NULL)
    {
        if (!is_null($i) && !is_null($d) && !is_null($p)) {
            $this->idEtape = $i;
            $this->descriptionEtape = $d;
            $this->posEtape = $p;
        }
    }
    // On fetch toutes les étapes liés à une recette
    public static function getEtapesRecette($numRecette)
    {
        $requetePreparee = "SELECT idEtape, descriptionEtape, posEtape
                            FROM Etape 
                            NATURAL JOIN Recette
                            WHERE numRecette = :tag_numRecette
                            ORDER BY posEtape;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Etape');
            $etapes = $req_prep->fetchAll();

            return $etapes;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $etapes;
    }
    //----Creer etape lors de la creation recette---//
    public static function creerEtape($etape, $posEtape, $numRecette)
    {
        $requetePreparee = "INSERT INTO Etape (descriptionEtape, posEtape, numRecette) VALUES(:tag_etape, :tag_posEtape, :tag_numRecette);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_etape" => $etape,
            "tag_posEtape" => $posEtape,
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
    //----Modifier etape lors de l'update de la recette---//
    public static function updateEtape($etape, $posEtape, $numRecette)
    {
        $requetePreparee = "UPDATE Etape SET descriptionEtape = :tag_etape
                            WHERE  numRecette = :tag_numRecette
                            AND posEtape = :tag_posEtape;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_etape" => $etape,
            "tag_posEtape" => $posEtape,
            "tag_numRecette" => $numRecette


        );
        try {
            $req_prep->execute($valeurs);
            return true;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
            return false;
        }
    }
}
