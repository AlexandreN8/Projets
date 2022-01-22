<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="~/../css/profile.css">
    <title>MarmIUTons - Mon compte</title>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>
        <!--Modal administrateur si changement de role validé ou non -->
        <div class="main-container">
            <?php
            if ($showModal) { // Si l'action du routeur provient de la fonction updateRole on affiche le resultat de cette action
            ?>
                <div class="modal" tabindex="-1" id="success">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modification utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    <?php
                                    // Variable de la requete SQL, controller : updateRecette
                                    if ($boolRequete == true) { // Requete reussi
                                        echo "Changement de rôle réussis.";
                                    } else {
                                        echo "Error : changement de rôle non effectué.";
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="head">
                <div class="pic">
                    <!-- Si l'utilisateur as une image on la met snon on met celle par défaut-->
                    <img class="img1" src="<?php if (isset($_SESSION['photo'])) {
                                                echo "{$_SESSION['photo']}";
                                            } else  echo '~/../images/3.jpg' ?>" width="200">
                    <form action="routeur.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="chosePathRecette">
                    </form>
                </div>
                <div class="nav-info">
                    <h3 class="name"><?php echo " Pseudo : {$lesInfos->getLogin()}" ?></h3>
                    <h3>Titre : <?php echo "{$lesInfos->getRole()}" ?></h3>
                </div>
                <form action="routeur.php?action=signout" method="POST">
                    <button class="option">
                        <div class="notif"><i class="fas fa-sign-out-alt"></i></div>
                    </button>
                </form>
            </div>
            <div class="main-body">
                <!--COLONNE GAUCHE-->
                <div class="left-col">
                    <div class="profile-s">
                        <span>Nom : <?php echo "{$lesInfos->getNom()} {$lesInfos->getPrenom()}" ?></span><br>
                        <span>Membre depuis : <?php echo "{$_SESSION['creation']}" ?></span>
                        <div class="bio-container">
                            <h3>Bio</h3>
                            <p class="bio">
                                <span><?php echo $lesInfos->getBio() ?></span>
                            </p>
                        </div>
                        <div class="comu-btn">
                            <button class="creer" onclick="location.href='routeur.php?action=afficherRecetteForm'">
                                <i class="fa fa-plus"></i>Recette
                            </button>
                        </div>
                        <div class="user-note">
                            <h3 class="note"><?php if ($noteTotale == 0) echo "N/N";
                                                else echo $noteTotale; ?></h3>
                            <div class="etoiles">
                                <div class="rate">
                                    <?php
                                    if ($noteTotale == 0) {
                                        echo "<i class='fa fa-star'></i>";
                                    } else {
                                        $noteTotale = round($noteTotale, 0);
                                        for ($i = 1; $i <= $noteTotale; $i++) {
                                            echo "<i class='fas fa-star'></i>";
                                        }
                                        for ($i = 1; $i <= 5 - $noteTotale; $i++) {
                                            echo "<i class='far fa-star'></i>";
                                        }
                                    }
                                    ?>

                                </div>
                                <span class="reviews"><span><?php if ($noteTotale == 0) echo "-";
                                                            else echo $nbNoteUser;  ?></span> notes</span>
                            </div>
                        </div>
                    </div>
                    <!-- Modal admin permettant de modifier les rôles utilisateurs-->
                    <?php
                    if (isset($_SESSION) && isset($_SESSION['role'])) {
                        if ($_SESSION['role'] == 'Admin') {
                    ?>
                            <div class="commande-admin">
                                <h4>Panel administrateur</h4>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="far fa-edit"></i> Utilisateurs
                                </button>
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Gestionnaire d'utilisateur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input id="srch" type="search" placeholder="Rechercher un utilisateur" class="form-control  srch-U">
                                                <div id="user-list">

                                                    <?php
                                                    // Les groupe doivent posséder les mêmes nom mais pas les mêmes ID pour les label
                                                    $id = 0;
                                                    $i = 0;
                                                    foreach ($lesUtilisateurs as $user) {
                                                    ?>
                                                        <form action="routeur.php" method="POST">
                                                            <input type="hidden" name="action" value="updateRole">
                                                            <div class="input-group mt-2">
                                                                <span class="input-group-text w-80">
                                                                    <?php echo $user->getLogin() ?>
                                                                    <input type="hidden" name="loginRole" value="<?php echo $user->getLogin() ?>">
                                                                </span>
                                                                <div class="btn-group" role="group">
                                                                    <input type="radio" class="btn-check" name="btnradio_<?php echo $i ?>" value="Admin" id="btnradio<?php echo $id ?>" <?php if ($user->getRole() == 'Admin') echo "checked"; ?>>
                                                                    <label class="btn btn-outline-primary" for="btnradio<?php echo $id++ ?>">Admin</label>

                                                                    <input type="radio" class="btn-check" name="btnradio_<?php echo $i ?>" value="User" id="btnradio<?php echo $id ?>" <?php if ($user->getRole() == 'User') echo "checked"; ?>>
                                                                    <label class="btn btn-outline-primary" for="btnradio<?php echo $id++ ?>">User</label>

                                                                    <input type="radio" class="btn-check" name="btnradio_<?php echo $i ?>" value="Bannis" id="btnradio<?php echo $id ?>" <?php if ($user->getRole() == 'User') echo "checked"; ?>>
                                                                    <label class="btn btn-outline-primary" for="btnradio<?php echo $id++ ?>">Bannis</label>

                                                                    <input type="submit" class="btn btn-outline-danger" value="Modifier">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    <?php
                                                        $i++;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
                <!--COLONNE DROITE-->
                <div class="right-col">
                    <div class="navd">
                        <ul>
                            <li onclick="tabs(0)" class="post active">Mes recettes</li>
                            <li onclick="tabs(1)" class="review">Mes avis</li>
                            <li onclick="tabs(2)" class="settings">Paramètres</li>
                        </ul>
                    </div>
                    <div class="pr-body">
                        <div class="pr-posts tab-bootless">
                            <h1 class="titre-s">Recettes</h1>
                            <table class="tab-recettes">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th class="hide640">Difficulté</th>
                                        <th class="hide">Dernière modification</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($lesRecettes as $recette) {
                                    ?>
                                        <tr>
                                            <td><?php echo $recette->getNomRecette(); ?></td>
                                            <td class="hide640"><?php echo $recette->getPublicJoin('niveau'); ?></td>
                                            <td class="hide"><?php echo $recette->getDateModification(); ?></td>
                                            <td><a class="hideLabel" href="<?php echo "routeur.php?action=detailsRecetteFromProfil&nomRecette={$recette->getNomRecette()}" ?>"><i class="far fa-eye"></i></a></td>
                                            <td><a class="hideLabel" href="<?php echo "routeur.php?action=updateRecette&nomRecette={$recette->getNomRecette()}" ?>">edit <i class="fas fa-pen"></i></a></td>
                                            <td><a class="hideLabel" href="<?php echo "routeur.php?action=deleteRecetteUser&nomRecette={$recette->getNomRecette()}" ?>">Supprimer <i class="fas fa-times"></i></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </p>
                        </div>
                        <div class="pr-review tab-bootless">
                            <h1 class="titre-s">Avis</h1>
                            <?php
                            foreach ($lesComms as $comm) {
                            ?>
                                <p>
                                    <tr>
                                        <div class="avis-box">
                                            <div class="nomPrenomAvis">
                                                <td><?php echo $comm->getLogin(); ?></td>
                                            </div>
                                            <div class="nomRecetteAvis">
                                                <td><?php echo $comm->nomRecette; ?></td>
                                            </div>
                                            <div class="noteAvis">
                                                <td><?php if (empty($comm->getNote())) {
                                                        echo "N/N";
                                                    } else {
                                                        for ($i = 1; $i <= intval($comm->getNote()); $i++) {
                                                            echo "<i class='fas fa-star'></i>";
                                                        }
                                                        for ($i = 1; $i <= 5 - intval($comm->getNote()); $i++) {
                                                            echo "<i class='far fa-star'></i>";
                                                        }
                                                    } ?>
                                                </td>
                                            </div>
                                            <p class="textCommentaire">
                                                <td><?php echo $comm->getDescriptionCommentaire(); ?></td>
                                            </p>
                                        </div>
                                    </tr>
                                </p>
                            <?php
                            }
                            ?>
                            </p>
                        </div>
                        <div class="pr-setting tab-bootless">
                            <h1 class="titre-s">Paramètres</h1>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 d-md-block">
                                    <div class="card bg-common card-left shadow">
                                        <div class="card-body">
                                            <nav class="nav d-md-block d-none">
                                                <a data-bs-toggle="tab" class="nav-link active" aria-current="page" href="#profile"><i class="fas fa-user"></i>Profile</a>
                                                <a data-bs-toggle="tab" class="nav-link" href="#securite"><i class="fas fa-shield-alt"></i>Sécurité</a>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-9">
                                    <div class="card">
                                        <div class="card-header border-bottom mb-3 d-md-none">
                                            <ul class="nav nav-tabs card-header-tabs nav-fill">
                                                <li class="nav-item">
                                                    <a data-bs-toggle="tab" class="nav-link active" aria-current="page" href="#profile"><i class="fas fa-user"></i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a data-bs-toggle="tab" class="nav-link" href="#securite"><i class="fas fa-shield-alt"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--contenu tab-->
                                        <div class="card-body tab-content border-0 shadow">
                                            <div class="tab-pane active" id="profile">
                                                <h3>Profile</h3>
                                                <form action="routeur.php" method="post">
                                                    <input type="hidden" name="action" value="modifierProfile">
                                                    <div class="mb-3">
                                                        <label for="nameControl" class="form-label">Modifier votre nom</label>
                                                        <input type="text" class="form-control" id="nameControl" name="nouveauNom" value="<?php echo $lesInfos->getNom(); ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="prenomControl" class="form-label">Modifier votre prénom</label>
                                                        <input type="text" class="form-control" id="prenomControl" name="nouveauPrenom" value="<?php echo $lesInfos->getPrenom(); ?>">

                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="bioControl" class="form-label">Bio</label>
                                                        <textarea rows="3" class="form-control" id="bioControl" name="bio"><?php echo $lesInfos->getBio(); ?></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="formFile" class="form-label">Image de profile</label>
                                                        <input class="form-control" type="file" id="formFile" name="pp">
                                                    </div>
                                                    <input type="submit" class="btn btn-outline-info" value="Mettre à jour">
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="securite">
                                                <h3>Sécurité</h3>
                                                <form action="routeur.php" method="post">
                                                    <input type="hidden" name="action" value="modifierProfileSecu">
                                                    <div class="mb-3">
                                                        <label for="mailControl" class="form-label">Modifier votre email</label>
                                                        <input type="email" class="form-control" id="mailControl" name="email" value="<?php echo $lesInfos->getEmail(); ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="acPassControl" class="form-label">Changer de mot de passe</label>
                                                        <input type="password" class="form-control" id="acPassControl" placeholder="Mot de passe actuel" name="ancienMdp">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="newPassControl" class="form-label"></label>
                                                        <input type="password" class="form-control" id="newPassControl" placeholder="Nouveau mot de passe" name="nouveauMdp">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="confNewPassControl" class="form-label"></label>
                                                        <input type="password" class="form-control" id="confNewPassControl" placeholder="Confirmation nouveau mot de passe" name="confirmMdp">
                                                    </div>

                                                    <input type="submit" class="btn btn-outline-info" value="Mettre à jour">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="~/../js/profile.js"></script>
</body>

</html>