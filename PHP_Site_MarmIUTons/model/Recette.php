<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Recette
{
    // ATTRIBUT
    private $numRecette;
    private $nomRecette;
    private $tempsRecette;
    private $tempsPreparation;
    private $tempsCuisson;
    private $dateCreation;
    private $dateModif;
    private $noteRecette;
    private $idDifficulte;
    private $niveau;
    private $login;
    private $idCategorie;

    // getters
    public function getNumRecette()
    {
        return $this->numRecette;
    }
    public function getNomRecette()
    {
        return $this->nomRecette;
    }
    public function getTempsRecette()
    {
        return $this->tempsRecette;
    }
    public function getTempsPreparation()
    {
        return $this->tempsPreparation;
    }
    public function getTempsCuisson()
    {
        return $this->tempsCuisson;
    }
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    public function getDateModification()
    {
        return $this->dateModif;
    }
    public function getNoteRecette()
    {
        return $this->noteRecette;
    }
    public function getNiveau()
    {
        return $this->niveau;
    }
    public function getIdDifficulte()
    {
        return $this->idDifficulte;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function getIdCategorie()
    {
        return $this->idCategorie;
    }
    //Ce getter permet de récuperer les attribut ajouter lors d'un join
    public function getPublicJoin($name)
    {
        return $this->$name;
    }
    //constructeur
    public function __construct($numr = NULL, $nomr = NULL, $tr = NULL, $tp = NULL, $tc = NULL, $dc = NULL, $dm = NULL, $idif = NULL, $l = NULL, $icat = NULL)
    {
        // Constructeur de la recette tel qu'elle apparait dans la BD
        if (!is_null($numr) && !is_null($nomr) && !is_null($tr) && !is_null($tp) && !is_null($tc) && !is_null($dc) && !is_null($dm) && !is_null($idif) && !is_null($l) && !is_null($icat)) {
            $this->numRecette = $numr;
            $this->nomRecette = $nomr;
            $this->tempsRecette = $tr;
            $this->tempsPreparation = $tp;
            $this->tempsCuisson = $tc;
            $this->dateCreation = $dc;
            $this->dateModification = $dm;
            $this->idDifficulte = $idif;
            $this->login = $l;
            $this->idCategorie = $icat;
        }
        // Constructeur étendu au different nom en lien avec les relation entité
    }

    //------Permet de supprimer une recette depuis la page profil---->
    public static function deleteRecetteFromUser($numRecette)
    {
        $requetePreparee = "DELETE FROM Recette WHERE numRecette = :tag_numRecette;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $recette = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($recette);
            return true;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return false;
    }


    public static function createRecette($nomRecette, $tempsPreparation, $tempsCuisson, $idDifficulte, $login, $idCategorie)
    {
        $tempsRecette = $tempsPreparation + $tempsCuisson;
        $requetePreparee = "INSERT INTO Recette (nomRecette, tempsRecette, tempsPreparation, tempsCuisson, idDifficulte, login, idCategorie) VALUES(:tag_nomRecette, :tag_tempsRecette, :tag_tempsPreparation, :tag_tempsCuisson, :tag_idDifficulte, :tag_login, :tag_idCategorie);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_nomRecette" => $nomRecette,
            "tag_tempsRecette" => $tempsRecette,
            "tag_tempsPreparation" => $tempsPreparation,
            "tag_tempsCuisson" => $tempsCuisson,
            "tag_idDifficulte" => $idDifficulte,
            "tag_login" => $login,
            "tag_idCategorie" => $idCategorie
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }


    //--- Toutes les fonctions liées à la modification de recette --//
    public static function updateRecette($numRecette, $nomRecette, $tempsPreparation, $tempsCuisson, $idDifficulte, $idCategorie)
    {
        $tempsRecette = $tempsPreparation + $tempsCuisson;
        $requetePreparee = "UPDATE Recette SET nomRecette = :tag_nomRecette, tempsRecette = :tag_TempsRecette, tempsPreparation = :tag_tempsPreparation, tempsCuisson = :tag_tempsCuisson,
                            idDifficulte = :tag_idDifficulte, idCategorie = :tag_idCategorie
                            WHERE numRecette = :tag_numRecette;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_nomRecette" => $nomRecette,
            "tag_TempsRecette" => $tempsRecette,
            "tag_tempsPreparation" => $tempsPreparation,
            "tag_tempsCuisson" => $tempsCuisson,
            "tag_idDifficulte" => $idDifficulte,
            "tag_idCategorie" => $idCategorie,
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

    /*  Recup varchar by id  */
    /*_______________________*/
    // Recuperer le numéro de recette associé à un utilisateur
    public static function getNumRecetteUserFromName($name, $login)
    {
        $requetePreparee = "SELECT numRecette FROM Recette WHERE nomRecette = :tag_name AND login = :tag_login;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_name" => $name,
            "tag_login" => $login
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Recette');
            $num = $req_prep->fetch();

            return $num;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $num;
    }


    /* Recup les objets liés à la recette */
    /*____________________________________*/
    public static function recupCategorie()
    {
        $requete = "SELECT * FROM Categorie;";
        $resultat = Connexion::pdo()->query($requete);
        $resultat->setFetchMode(PDO::FETCH_CLASS, 'Categorie');
        $tableauCat = $resultat->fetchAll();
        return $tableauCat;
    }
    /* Image */
    /*_______*/
    public static function insertImage($name, $file, $numRecette)
    {
        $requetePreparee = "INSERT INTO Photo (nomImage, image, numRecette) VALUES(:tag_name, :tag_file, :tag_numRecette)";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_name" => $name,
            "tag_file" => $file,
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public static function recupererImage($numRecette)
    {
        $requetePreparee = "SELECT image FROM Photo WHERE numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Photo');
            $image = $req_prep->fetch();

            return $image;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $image;
    }
    public static function getImageBlob($numPhoto)
    {
        $requetePreparee = "SELECT * FROM Photo WHERE idPhoto = :tag_numPhoto";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numPhoto" => $numPhoto);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Photo');
            $image = $req_prep->fetch();

            $imageData = $image['image'];

            return $imageData;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }

    // On get toutes les informations de recette qui tiennent sur 1 row
    public static function getDataRecette($numRecette)
    {
        $requetePreparee = "SELECT * FROM Recette
                            NATURAL JOIN Difficulte
                            NATURAL JOIN Categorie
                            WHERE numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Recette');
            $data = $req_prep->fetch();

            return $data;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $data;
    }

    // On fetch toutes les photos liées à une recette
    public static function getImagesRecette($numRecette)
    {
        $requetePreparee = "SELECT image FROM Photo
                            WHERE numRecette = :tag_numRecette";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Photo');
            $photos = $req_prep->fetchAll();

            return $photos;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $photos;
    }
    // Recuperer la difficulte depuis le nom pour parametrer l'insertion de recette
    public static function getIdDifficulteFromName($name)
    {
        $requetePreparee = "SELECT idDifficulte FROM Difficulte WHERE niveau = :tag_name;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_name" => $name);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Difficulte');
            $id = $req_prep->fetch();

            return $id;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $id;
    }
    // ----- Sert à créer et update un recette ----//
    public static function numeroCategorie($name)
    {
        $requetePreparee = "SELECT idCategorie FROM Categorie WHERE nomCategorie = :tag_name";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_name" => $name);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Categorie');
            $num = $req_prep->fetch();

            return $num;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $num;
    }

    public static function ingredientRecette($numRecette, $idIng, $qteIng, $typeMesure)
    {
        $requetePreparee = "INSERT INTO TypeIngredient (numRecette, idIngredient, quantite, typeMesure) VALUES(:tag_numRecette, :tag_idIng, :tag_qteIng, :tag_typeMesure);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette,
            "tag_idIng" => $idIng,
            "tag_qteIng" => $qteIng,
            "tag_typeMesure" => $typeMesure
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
    public static function ustensileRecette($numRecette, $idUst, $qteUst)
    {
        $requetePreparee = "INSERT INTO CompoUstensile (numRecette, idUstensile, qteUstensile) VALUES(:tag_numRecette, :tag_idUst, :tag_qteUst);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_numRecette" => $numRecette,
            "tag_idUst" => $idUst,
            "tag_qteUst" => $qteUst
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    public static function getAllRecettesUser()
    {
        $login = $_SESSION['pseudo'];
        $requete = "SELECT * FROM Recette NATURAL JOIN Difficulte WHERE login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requete);
        $valeurs = array("tag_login" => $login);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Recette');
            $recettes = $req_prep->fetchAll();
            return $recettes;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $recettes;
    }
    //----- On veux toutes les recettes mais seulement 1 image par recette
    public static function getAllRecettes()
    {
        $requete = "SELECT R.*,niveau, (SELECT idPhoto FROM Photo P WHERE R.numRecette = P.numRecette LIMIT 1) AS idPic FROM Recette R NATURAL JOIN Difficulte";
        $req_prep = Connexion::pdo()->prepare($requete);
        try {
            $req_prep->execute();
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Recette');
            $allRecettes = $req_prep->fetchAll();
            return $allRecettes;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $allRecettes;
    }

    public static function recherche($terme, $ing, $tag)
    {
        $query = Connexion::pdo()->prepare('CALL searchWithOptPara(?, ?, ?)');

        $query->bindParam(1, $tag, PDO::PARAM_STR);
        $query->bindParam(2, $ing, PDO::PARAM_STR);
        $query->bindParam(3, $terme, PDO::PARAM_STR);

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $recettes = $query->fetchAll();
        return $recettes;
    }
}
