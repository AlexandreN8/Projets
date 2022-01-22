<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Recette détail</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Calligraffitti">
    <link rel="stylesheet" href="~/../css/ficheRecette.css">
    <title>MarmIUTon - Fiche recette</title>
    <!--On défini la variable numeroRecette pour la passer au JS et pouvoir inserer des commentaires-->
    <script>
        ajax_numRecette = "<?php echo $recette->getNumRecette(); ?>";
        ajax_nbCom = <?php echo $nbCom[0] ?>;
    </script>
</head>

<body>
    <!-- Page Content -->
    <?php include("~/../vue/header.php"); ?>
    <div class="wrapper">
        <div class="container main">
            <div class="card-body tab-content border-0">
                <h1 class="my-2" style="color:#0088a9;" id="nomRecette"><?php echo $recette->getNomRecette() ?></h1>
                <p>
                    <span>Créé le <?php echo $recette->getDateCreation() ?></span>
                    <span>Par <?php echo $recette->getLogin() ?></span>
                </p>
                <div class=" row mb-1">
                    <div class="container">
                        <ul class="list-group flex-sm-row">
                            <li class="list-group-item">
                                <span><i class="fab fa-cuttlefish"></i> Catégorie:</span>
                                <strong><?php echo $recette->getPublicJoin('nomCategorie') ?></strong>
                            </li>
                            <li class="list-group-item">
                                <span><i class="far fa-clock"></i> Temps recette:</span>
                                <strong><?php echo $recette->getTempsRecette() ?>mn</strong>
                            </li>
                            <li class="list-group-item">
                                <span><i class="fas fa-utensils"></i> Temps de préparation:</span>
                                <strong><?php echo $recette->getTempsPreparation() ?>mn</strong>
                            </li>
                            <li class="list-group-item">
                                <span><i class="fas fa-fire"></i> Temps de cuisson:</span>
                                <strong><?php echo $recette->getTempsCuisson() ?>mn</strong>
                            </li>
                            <li class="list-group-item">
                                <span><i class="far fa-star"></i> Difficulté:</span>
                                <strong><?php echo $recette->getPublicJoin('niveau') ?></strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false" data-bs-interval="false">
                            <div class="carousel-inner">
                                <?php
                                $bool = true;
                                foreach ($imageRecette as $img) {
                                    if ($bool) {
                                        echo "<div class='carousel-item active'>
                                        <img class='d-block w-100' src='data:image/jpg;base64," . base64_encode($img['image']) . "' alt='Images de la recette' style='height:400px; width:400px;'/> 
                                </div>";
                                        $bool = false;
                                    } else {
                                        echo "<div class='carousel-item'>
                                        <img class='d-block w-100' src='data:image/jpg;base64," . base64_encode($img['image']) . "' alt='Images de la recette' style='height:400px; width:400px;'/> 
                                </div>";
                                    }
                                }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <h3 class="my-m3 after-h3 mb-3">Tags recette</h3>
                            <div class="info-recette">
                                <?php
                                echo "<ul>";
                                foreach ($tagRecettes as $tag) {
                                    echo "<li class='tag-r'>
                                    {$tag->getNomTag()}
                                      </li>";
                                }
                                echo "</ul>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-around border mt-3">
                    <div class="col-md-4">
                        <h3 class="my-3 after-h3">Ingrédients</h3>
                        <ul>
                            <?php
                            foreach ($lesIngredients as $ingredient) {
                                echo "<li style='color:black;'> {$ingredient->getQuantite()}{$ingredient->getTypeMesure()} : {$ingredient->getNomIngredient()}</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h3 class="my-3 after-h3">Ustensiles</h3>
                        <ul>
                            <?php
                            foreach ($lesUstensiles as $ustensile) {
                                echo "<li style='color:black;'> {$ustensile->getQteUstensile()} : {$ustensile->getNomUstensile()} </li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <h3 class="my-3 after-h3 mb-5">Explications</h3>
                    <?php
                    foreach ($lesExplication as $explication) {
                        $num = $explication->getPosEtape() + 1;
                        echo "<h4><i class='fas fa-check-circle' style='color:#0088a9;'></i> Etape {$num}</h4>";
                        echo "<div>{$explication->getDescriptionEtape()}</div>";
                        echo "<br>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!--COMMENTS START-->
        <div class="container  style='margin-top: 150px;'">
            <!--afficher l'input pour commenter ou non selon que l'utilisateur soit connécté ou pas-->
            <!-- DEBUT : UTILISATEUR CONNECTE + CREATEUR DE LA RECETTE-->
            <?php
            if (isset($_SESSION['logged_in']) && $_SESSION['pseudo'] == $recette->getLogin()) {
            ?>
                <div class="row drow">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="card-body" id="flex-stars">
                                <h3 class="my-4 after-h3" style="text-align:center;">Vous ne pouvez pas donner un avis sur vos propres recettes</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN : UTILISATEUR CONNECTE + CREATEUR DE LA RECETTE-->
                <!-- DEBUT UTILISATEUR CONNECTE ET IL N'EST PAS LE CREATEUR-->
            <?php
            } else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            ?>
                <div class="row drow">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="card-body">
                                <div class="who">
                                    <h3><?php echo $_SESSION['pseudo'] ?></h3>
                                    <small><?php echo $_SESSION['role'] ?></small>
                                </div>
                                <div class="star-widget" id="star-widget">
                                    <input class="star next" type="radio" name="rate" id="rate-5">
                                    <label for="rate-5" name="5" class="fas fa-star"></label>
                                    <input class="star next" type="radio" name="rate" id="rate-4">
                                    <label for="rate-4" name="4" class="fas fa-star"></label>
                                    <input class="star next" type="radio" name="rate" id="rate-3">
                                    <label for="rate-3" name="3" class="fas fa-star"></label>
                                    <input class="star next" type="radio" name="rate" id="rate-2">
                                    <label for="rate-2" name="2" class="fas fa-star"></label>
                                    <input class="star" type="radio" name="rate" id="rate-1">
                                    <label for="rate-1" name="1" class="fas fa-star"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" id="mainComment" placeholder="Ajouter un commentaire..." cols="30" rows="4"></textarea><br>
                                <button style="float:right" class="btn-primary btn" id="addComment" onclick="repondre=false">Envoyer</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN UTILISATEUR CONNECTE ET IL N'EST PAS LE CREATEUR-->
                <!-- DEBUT : UTILISATEUR NON CONNECTE-->
            <?php
            } else {
            ?>
                <div class="row drow">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="card-body" id="flex-stars">
                                <h3 class="my-4" style="text-align:center;">Veuillez vous connecter pour donner votre avis.</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIN : UTILISATEUR CONNECTE + CREATEUR DE LA RECETTE-->
            <?php
            }
            ?>
            <div class="row answer mt-5" id="com">
                <div class="col-md-12">
                    <h2><b id="#nbCom"><span style="color:#0088a9"><?php echo $nbCom[0] ?> </span>Avis</b></h2>
                    <div class='userComments'>
                        <!--Commentaire crée via ajax dynamiquement-->
                        <!-- Si role = admin on ajoute supprimer commentaire via ajax-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- COMMENTS END-->
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src=" ~/../js/recetteDetails.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>