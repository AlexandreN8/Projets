<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Utilisateur
{

    // attributs
    private $login;
    private $bio;
    private $nomUtilisateur;
    private $prenomUtilisateur;
    private $email;
    private $telUtilisateur;
    private $password;
    private $genreUtilisateur;
    private $role;
    private $photoUtilisateur;
    private $dateCreation;
    private $avertissement;

    // getters
    public function getLogin()
    {
        return $this->login;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getRole()
    {
        return $this->role;
    }
    public function getNom()
    {
        return $this->nomUtilisateur;
    }
    public function getPrenom()
    {
        return $this->prenomUtilisateur;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getTel()
    {
        return $this->telUtilisateur;
    }
    public function getPass()
    {
        return $this->password;
    }
    public function getGenre()
    {
        return $this->genreUtilisateur;
    }
    public function getPgotoUtilisateur()
    {
        return $this->photoUtilisateur;
    }
    public function getCreation()
    {
        return $this->dateCreation;
    }
    public function getAvertissement()
    {
        return $this->avertissement;
    }

    // constructeur
    public function __construct($l = NULL, $r = NULL, $n = NULL, $p = NULL, $e = NULL, $t = NULL, $pa = NULL, $g = NULL, $dc = NULL, $b = NULL, $a = NULL)
    {
        if (
            !is_null($l) && !is_null($r) && !is_null($n) && !is_null($p) && !is_null($e)
            && !is_null($t) && !is_null($pa) && !is_null($g) && !is_null($dc) && !is_null($b) && !is_null($a)
        ) {
            $this->login = $l;
            $this->role = $r;
            $this->nomUtilisateur = $n;
            $this->prenomUtilisateur = $p;
            $this->email = $e;
            $this->telUtilisateur = $t;
            $this->password = $p;
            $this->genreUtilisateur = $g;
            $this->dateCreation = $dc;
            $this->bio = $b;
            $this->avertissement = $a;
        }
    }
    //------ On récupère un mail, sert à vérifié sa disponibilité------//
    public static function emailExist($email)
    {
        $requetePreparee = "SELECT * FROM Utilisateur WHERE email = :tag_email;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_email" => $email);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $mail = $req_prep->fetch();

            return $mail;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $mail;
    }
    //------ On récupère un numéro de téléphonne, sert à vérifié sa disponibilité------//
    public static function telExist($tel)
    {
        $requetePreparee = "SELECT * FROM Utilisateur WHERE telUtilisateur = :tag_tel AND telUtilisateur != '00.00.00.00.00';";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_tel" => $tel);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $tel = $req_prep->fetch();

            return $tel;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $tel;
    }
    //------ On récupère un pseudo, sert à vérifié sa disponibilité------//
    public static function pseudoExist($login)
    {
        $requetePreparee = "SELECT * FROM Utilisateur WHERE login = :tag_pseudo;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_pseudo" => $login);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $pseudo = $req_prep->fetch();

            return $pseudo;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return $pseudo;
    }
    //--------Récupère un objet utilisateur----//
    public static function getUserData($login)
    {
        $requetePreparee = "SELECT * FROM Utilisateur WHERE login = :tag_pseudo;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_pseudo" => $login);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $data = $req_prep->fetch();

            if (!$data)
                return false;
            return $data;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }
    //---- Sert à remplir le panel administrateur
    public static function getAllUser()
    {
        $requetePreparee = "SELECT login, role FROM Utilisateur";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        try {
            $req_prep->execute();
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $name = $req_prep->fetchAll();

            return $name;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }

    public static function addUser($login, $password, $email, $nom, $prenom, $tel, $genre)
    {
        $requetePreparee = "INSERT INTO Utilisateur(login, role, password, email, nomUtilisateur, prenomUtilisateur, telUtilisateur, genreUtilisateur) VALUES(:tag_login,'User',:tag_password, :tag_email,:tag_nom, :tag_prenom, :tag_tel, :tag_genre);";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_login" => $login,
            "tag_password" => crypt($password, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t'), // encryptage du mot de passe dans la base de donnée
            "tag_email" => $email,
            "tag_nom" => $nom,
            "tag_prenom" => $prenom,
            "tag_tel" => $tel,
            "tag_genre" => $genre
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    //---Mettre à jour les informations de l'utilisateur---
    public static function updateUtilisateur($nom, $prenom, $bio, $login)
    {
        $requetePreparee = "UPDATE Utilisateur SET nomUtilisateur = :tag_nom, prenomUtilisateur = :tag_prenom, bio = :tag_bio WHERE login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_nom" => $nom,
            "tag_prenom" => $prenom,
            "tag_bio" => $bio,
            "tag_login" => $login
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    //---Mettre à jour le mot de passe---
    public static function updateUtilisateurSecu($ancien, $nouveau, $confirm, $baseMdp, $login)
    {
        $ancienMdp = crypt($ancien, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
        $nouveauMdp = crypt($nouveau, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
        $confirmMdp = crypt($confirm, '$2a$10$1qAz2wSx3eDc4rFv5tGb5t');
        if (strcmp($nouveauMdp, $confirmMdp) == 0 && $ancienMdp == $baseMdp) {
            $requetePreparee = "UPDATE Utilisateur SET Utilisateur.password = :tag_password WHERE login = :tag_login";
            $req_prep = Connexion::pdo()->prepare($requetePreparee);
            $valeurs = array(
                "tag_password" => $nouveauMdp,
                "tag_login" => $login
            );
            try {
                $req_prep->execute($valeurs);
            } catch (PDOException $e) {
                return false;
            }
            return true;
        }
    }

    //---Mettre à jour l'email---
    public static function updateUtilisateurEmail($email, $login)
    {
        $requetePreparee = "UPDATE Utilisateur SET email = :tag_email WHERE login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_email" => $email,
            "tag_login" => $login
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    //---Affichage de tous les commentaires sur les recettes de l'utilisateur
    public static function getCommentaireProfile($login)
    {
        $requetePreparee = "SELECT * FROM Commentaire C INNER JOIN  Recette R ON R.numRecette=C.numRecette INNER JOIN Utilisateur U ON U.login=R.login WHERE U.login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_login" => $login
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    //----Modifier le role de l'utilisateur ---
    public static function updatedRole($value, $login)
    {
        $requetePreparee = "UPDATE Utilisateur SET role = :tag_value WHERE login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_value" => $value,
            "tag_login" => $login
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
}
