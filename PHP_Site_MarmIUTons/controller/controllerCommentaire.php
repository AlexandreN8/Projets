<?php
require_once("~/../model/Commentaire.php");

class controllerCommentaire
{
    public static function deleteCommentaire()
    {
        $idCommentaire = $_GET['idCommentaire'];
        $numRecette = $_GET['numRecette'];

        Commentaire::deletedCommentaire($idCommentaire);
        controllerRecette::detailsRecette();
    }
    //------Objectif : Inserer les commentaire coté client via ajax ----//
    public static function commentaireInsert()
    {
        if (isset($_POST['addComment']) && $_SESSION['logged_in'] == true) {
            $comment = $_POST['comment'];
            $numRecette = $_POST['nomR'];
            $note = $_POST['note'];


            Commentaire::insertCommentaire($comment, $note, $_SESSION['pseudo'], $numRecette); // on insert le commentaire input par l'utilisateur
            $newCom = Commentaire::fetchUnCom($numRecette); // On récupère le commentaire créé
            // on crée le commentaire récupéré après insertion et on envois à ajax
            exit(Commentaire::creerAjaxCom($newCom, $numRecette));
        }
    }
    //------Objectif : afficher tout les commentaire lié à la recette au load via ajax----//
    public static function commentaireAll()
    {
        if (isset($_POST['getAllComments'])) {
            $debutIntervalle = $_POST['start'];
            $numRecette = $_POST['nomR'];

            //On recupere les commentaires
            $allComments = Commentaire::fetchAllCom($numRecette, $debutIntervalle);
            $htmlComments = "";

            // on utilise les données récupéré dans du HTML
            foreach ($allComments as $comment) {
                $htmlComments .= Commentaire::creerAjaxCom($comment, $numRecette); // on les stock
            }
            // on retourne les information formatté en HTML à la fonction success ajax
            // pour l'append
            exit($htmlComments);
        }
    }
}
