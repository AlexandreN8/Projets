<?php
require_once("~/../conf/Connexion.php");
Connexion::connect();

class Commentaire
{

    // attributs
    private $idCommentaire;
    private $descriptionCommentaire;
    private $note;
    private $dateCreation;
    private $dateModif;
    private $login;
    private $numRecette;

    // getters
    public function getIdCommentaire()
    {
        return $this->idCommentaire;
    }
    public function getDescriptionCommentaire()
    {
        return $this->descriptionCommentaire;
    }
    public function getNote()
    {
        return $this->note;
    }
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
    public function getDateModif()
    {
        return $this->dateModif;
    }
    public function getLogin()
    {
        return $this->login;
    }
    public function getNumRecette()
    {
        return $this->numRecette;
    }



    // constructeur
    public function __construct($i = NULL, $d = NULL, $n = NULL, $dc = NULL, $dm = NULL, $l = NULL, $nr = NULL)
    {
        if (!is_null($i) && !is_null($d) && !is_null($dc) && !is_null($n) && !is_null($dm) && !is_null($l) && !is_null($nr)) {
            $this->idCommentaire = $i;
            $this->descriptionCommentaire = $d;
            $this->note = $n;
            $this->dateCreation = $dc;
            $this->dateModif = $dm;
            $this->login = $l;
            $this->numRecette = $nr;
        }
    }

    //---Affichage de tous les commentaires sur les recettes de l'utilisateur
    public static function getCommentaireProfile($login)
    {
        $requetePreparee = "SELECT C.login, nomRecette, C.note, C.descriptionCommentaire 
                            FROM Commentaire C
                            INNER JOIN  Recette R ON R.numRecette=C.numRecette 
                            INNER JOIN Utilisateur U ON U.login=R.login 
                            WHERE R.login = :tag_login";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_login" => $login);

        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'Commentaire');
            $commentaires = $req_prep->fetchAll();

            return $commentaires;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }

    public static function insertCommentaire($description, $note, $login, $numRecette)
    {
        $requetePreparee = "INSERT INTO Commentaire (descriptionCommentaire, note, login, numRecette)
                            VALUES (:tag_descritpion, :tag_note, :tag_login, :tag_numRecette) ";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array(
            "tag_descritpion" => $description,
            "tag_note" => $note,
            "tag_login" => $login,
            "tag_numRecette" => $numRecette
        );
        try {
            $req_prep->execute($valeurs);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }
    //--------Nombre de commentaires total d'une recette-------//
    public static function nbComTotal($numRecette)
    {
        $requetePreparee = "SELECT COUNT(idCommentaire) FROM Commentaire WHERE numRecette = :tag_numRecette;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
            $nb = $req_prep->fetch();

            return $nb;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }

    //----------Tous les commentaires, qu'ajax va afficher sur le details de la recette------//
    // dans un intervalle de 20 qu'on incrementeras avec ajax
    public static function fetchAllCom($numRecette, $debutIntervalle)
    {
        $requetePreparee = "SELECT C.idCommentaire,C.login, C.descriptionCommentaire, C.note, DATE_FORMAT(C.dateCreation, '%Y-%m-%d') As creation, C.dateModif FROM Commentaire C
                            INNER JOIN Utilisateur U
                            WHERE U.login = C.login AND C.numRecette = :tag_numRecette
                            ORDER BY C.idCommentaire DESC
                            LIMIT $debutIntervalle, 20;"; // La syntaxe pose problème en req préparé 
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_ASSOC);
            $allCom = $req_prep->fetchAll();

            return $allCom;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }
    //------ Même chose qu'au dessus, sauf qu'on veux récupéré le commentaire que le client vient de créer ----
    public static function fetchUnCom($numRecette)
    {
        $requetePreparee = "SELECT C.idCommentaire, C.login, C.descriptionCommentaire, C.note, DATE_FORMAT(C.dateCreation, '%Y-%m-%d') As creation, C.dateModif FROM Commentaire C
                            INNER JOIN Utilisateur U
                            WHERE U.login = C.login AND C.numRecette = :tag_numRecette
                            ORDER BY C.idCommentaire DESC
                            LIMIT 1";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_numRecette" => $numRecette);
        try {
            $req_prep->execute($valeurs);
            $req_prep->setFetchMode(PDO::FETCH_ASSOC);
            $allCom = $req_prep->fetchAll();

            return $allCom;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
    }

    //-----Commande admin : supprimer un commentaire -----//
    public static function deletedCommentaire($idCommentaire)
    {
        $requetePreparee = "DELETE FROM Commentaire WHERE idCommentaire = :tag_idCommentaire;";
        $req_prep = Connexion::pdo()->prepare($requetePreparee);
        $valeurs = array("tag_idCommentaire" => $idCommentaire);
        try {
            $req_prep->execute($valeurs);
            return true;
        } catch (PDOException $e) {
            echo "erreur : " . $e->getMessage() . "<br>";
        }
        return false;
    }
    //-------On crée autant de commentaire html que de commentaire fetch ----//
    //------Ce n'est pas le rôle du modèle, mais pas vraiment celui du controller non plus
    public static function creerAjaxCom($comment, $numRecette)
    {

        $innerHTML = "";
        $adminHTML = "";
        // Permet d'afficher la note attribué par un utilisateur
        for ($i = 1; $i <= 5; $i++) {
            if ($comment['note'] >= $i) {
                $innerHTML .= '<i class="fa fa-star" style="color:gold;"></i>';
            } else {
                $innerHTML .= '<i class="fa fa-star" style="color:black;"></i>';
            }
        }

        // Commande administrateur => si la session indique role = 'admin' on ajoute la comme pour supprimer les commentaires
        if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") {

            $adminHTML = '  
                    <div class="admin-overlay">
                        <a href="routeur.php?action=deleteCommentaire&numRecette=' . $numRecette . '&idCommentaire=' . $comment["idCommentaire"] . '">
                        <i class="fas fa-trash" id="trash" style="float:right;"></i></a>
                    </div>';
        }
        // on build notre contenu HTML
        $innerHTML = '
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="float-right">' . $adminHTML . '</p>
                            <p>' . $comment["descriptionCommentaire"] . '</p>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <img
                                        src="https://www.linkpicture.com/q/profile_1.jpg"
                                        alt="avatar"
                                        width="25"
                                        height="25"
                                    />
                                <p class="small mb-0 ms-2"><span style="color:blue; font-size:1.1rem; font-weight:blod;">' . $comment["login"] . '</span> le : ' . $comment["creation"] . '</p>
                                </div>
                            <div class="d-flex flex-row align-items-center text-primary">
                                ' . $innerHTML . '
                            </div>
                        </div>
                    </div>
                </div>';

        return $innerHTML;
    }
}
