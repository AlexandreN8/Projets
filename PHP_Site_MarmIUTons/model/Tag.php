<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Tag
{

    // attributs
    private $idTag;
    private $nomTag;

    // getters
    public function getIdTag()
    {
        return $this->idTag;
    }
    public function getNomTag()
    {
        return $this->nomTag;
    }
    public function getPublicJoin($name)
    {
        return $this->$name;
    }

    // constructeur
    public function __construct($n = NULL, $i = NULL)
    {
        if (!is_null($n)) {
            $this->nomTag = $n;
        } else if (!is_null($n) && !is_null($i)) {
            $this->nomTag = $n;
            $this->idTag = $i;
        }
    }
    public static function recupAllTag()
    {
        $requete = "SELECT nomTag FROM TagRecette;";
        $resultat = Connexion::pdo()->query($requete);
        $resultat->setFetchMode(PDO::FETCH_CLASS, 'Tag');
        $tableauTag = $resultat->fetchAll();
        return $tableauTag;
    }
    // On fetch tout les tag liés à une recette
    public static function getTagsRecette($numRecette)
    {
        $requetePreparee = "SELECT idTag, nomTag FROM TagRecette
                             NATURAL JOIN ComposantRecette
                             WHERE numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Tag');
            $tags = $req_prep->fetchAll();

            return $tags;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $tags;
    }
    public static function recupIdTag($name)
    {
        $requetePreparee = "SELECT idTag FROM TagRecette WHERE nomTag = :tag_name;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_name" => $name);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Tag');
            $id = $req_prep->fetch();

            return $id;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $id;
    }
    //---Inserer des tag lié à une recette ---//
    public static function creerRealationTag($tag, $numRecette)
    {
        // On recupere l'id du tag depuis son nom
        $temp = self::recupIdTag($tag);
        $tag = (int)$temp->getIdTag();

        $requetePreparee = "INSERT INTO ComposantRecette (idTag, numRecette) VALUES(:tag_tag, :tag_numRecette);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_tag" => $tag,
            "tag_numRecette" => $numRecette,
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
    //-----Supprimé les anciens tag avant d'en inserer de nouveau pour l'updateRecette---- //
    public static function deleteOldTag($numRecette)
    {
        $requetePreparee = "DELETE FROM ComposantRecette WHERE numRecette = :tag_numRecette;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public static function removeArrayFromArray($array, $array2)
    {
        $tmp = [];
        for ($i = 0; $i < sizeof($array); $i++) {
            for ($j = 0; $j < sizeof($array2); $j++) {
                if (in_array($array[$i]['nomTag'], $array2[$j])) {
                    array_push($tmp, $i);
                }
            }
        }
        foreach ($tmp as $i) {
            unset($array[$i]);
        }
    }
}
