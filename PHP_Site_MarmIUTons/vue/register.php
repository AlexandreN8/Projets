<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="~/../css/register.css">
    <title>MarmIUTons - S'inscrire</title>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>

        <form class="container" action="routeur.php" method="POST">
            <input type="hidden" name="action" value="created">
            <div class="en-tete">
                <span class='titre'>S'inscrire</span>
                <?php
                extract($_POST);

                if (!empty($message)) {
                    echo "<p>$message</p>";
                }
                ?>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Nom</span>
                    <input type="text" value="<?php if (isset($_POST['nom'])) echo $nom ?>" name="nom" placeholder="Entrez votre nom">
                </div>
                <div class="input-box">
                    <span class="details">Prenom</span>
                    <input type="text" value="<?php if (isset($_POST['prenom'])) echo $prenom ?> " name="prenom" placeholder="Entrez votre prenom">
                </div>
                <div class="input-box">
                    <span class="details">Mail</span>
                    <input type="email" value="<?php if (isset($_POST['email'])) echo $email ?>" name="email" placeholder="Entre votre adresse email">
                </div>
                <div class="input-box">
                    <span class="details">Telephonne (optionnel)</span>
                    <input type="text" value="<?php if (isset($_POST['tel'])) echo $tel ?>" name="tel" placeholder="Entrez votre numéro">
                </div>
                <div class="input-box">
                    <span class="details">Nom d'utilisateur</span>
                    <input type="text" value="<?php if (isset($_POST['pseudo'])) echo $pseudo ?>" name="pseudo" placeholder="Nom d'utilisateur">
                </div>
                <div class="input-box">
                    <span class="details">Mot de passe</span>
                    <input type="password" name="password" placeholder="Entrez votre mot de passe">
                </div>
                <div class="input-box">
                    <span class="details">Confirmer mot de passe</span>
                    <input type="password" name="passwordConf" placeholder="Confirmer votre mot de passe">
                </div>
            </div>
            <div class="genre">
                <div class="titre-genre"><span>Sexe</span></div>
                <div class="categorie">
                    <label>Homme
                        <input type="radio" id="radio1" name="genre" value="homme" checked>
                    </label>
                    <label>Femme
                        <input type="radio" id="radio2" name="genre" value="femme">
                    </label>
                </div>
            </div>
            <div class="btnSub">
                <input type="submit" name="envoyer" value="S'inscrire">
            </div>
            <a href="routeur.php?action=login">Deja inscrit ?</a>
        </form>
    </div>
    <?php include("~/../vue/footer.php"); ?>
</body>

</html>