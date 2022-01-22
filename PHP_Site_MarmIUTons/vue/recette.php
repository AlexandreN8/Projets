<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="~/../css/recette.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>MarmIUTon - Recettes</title>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>
        <?php include("~/../vue/boot.php"); ?>

        <form action="routeur.php" method="get" class="recherchePage">
            <input type="hidden" name="action" value="recherche_recettes">
            <!--search bar-->
            <div class="search-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <div>
                                <div class="input-group">
                                    <input type="search" placeholder="Rechercher une recette" name="terme" class="form-control searchControl">
                                    <div class="input-group-append" id="append">
                                        <!--Filtres-->
                                        <div class="btn-group " id="btn-grp-col-1">
                                            <select class="btn dropdown-toggle tagHide" name="sTag">
                                                <option> Catégorie </option>
                                                <?php
                                                foreach ($tagRecettes as $tag) {
                                                ?>
                                                    <option value="<?php echo $tag->getNomTag(); ?>"><?php echo $tag->getNomTag(); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <select class="btn dropdown-toggle ingHide" name="sIng">
                                                <option> Ingrédient </option>
                                                <?php
                                                foreach ($allIngredient as $ingredient) {
                                                ?>
                                                    <option value="<?php echo  $ingredient->getNomIngredient(); ?>"><?php echo  $ingredient->getNomIngredient(); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-link submit-button"><i class="fas fa-search magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="items-type">
                <div class="head-cat">
                    <h4>Personaliser votre séléction.</h4>
                    <h2>Quel type de recette souhaitez vous ?</h2>
                </div>
                <div class="row-sel">
                    <?php
                    $cpt = 0;
                    foreach ($allCategories as $categorie) {
                        echo "<div class='type-col type-col$cpt' name='{$categorie["idCategorie"]}' onclick='toggleFilter(this)'>
                        <h3>{$categorie['nomCategorie']}</h3>
                      </div>";
                        $cpt++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="container justifiy-content-center mb-5">
            <div class="row row-cols-1 row-cols-md-4 g-4 d-flex justify-content-center">

                <?php
                $j = 0; //Sert a inserer les commentaires dans l'ordre
                foreach ($allRecettes as $recette) {
                    $b = Recette::getImageBlob($recette->getPublicJoin('idPic'));
                    //On formate la date de publication
                    $date = new DateTime($recette->getDateCreation());
                    $date = $date->format('d-m-Y');

                ?>
                    <div class="col d-flex justify-content-center ">
                        <div class="card column-noboot" style="width: 18rem;" name='<?php echo $recette->getIdCategorie() ?>'>
                            <a class='ref' href='routeur.php?action=detailsRecette&numRecette=<?php echo $recette->getNumRecette() ?>'>
                                <?php
                                // On affiche les option de modification pour les admins
                                if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") {
                                ?>
                                    <div class="admin-overlay">
                                        <span>Options admin : </span>
                                        <a href="routeur.php?action=deleteRecetteByNum&numRecette=<?php echo $recette->getNumRecette() ?>"><i class="far fa-times-circle cross"></i></a>
                                        <a href='routeur.php?action=detailsRecette&numRecette=<?php echo $recette->getNumRecette() ?>'><i class="far fa-eye show"></i></a>
                                        <a href="routeur.php?action=updateRecette&numRecette=<?php echo $recette->getNumRecette() ?>"><i class="fas fa-edit edit"></i></a>
                                    </div>
                                <?php
                                }
                                ?>
                                <img class="card-img-top" src="data:image/jpg;base64,<?php echo base64_encode($b) ?>" style="height:200px" alt="Image recette">
                                <div class="row diff diffi">
                                    <?php echo $recette->getPublicJoin('niveau') ?>
                                </div>
                                <div class="card-body">

                                    <div class="note">
                                        <?php
                                        if (empty($recette->getNoteRecette())) {
                                            echo "N/N";
                                        } else {
                                            for ($i = 1; $i <= intval($recette->getNoteRecette()); $i++) {
                                                echo "<i class='fas fa-star'></i>";
                                            }
                                            for ($i = 1; $i <= 5 - intval($recette->getNoteRecette()); $i++) {
                                                echo "<i class='far fa-star'></i>";
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <h2 class=' card-title nom-recette'><?php echo $recette->getNomRecette() ?></h2>
                                    </div>
                                    <div class="row justify-content-evenly">
                                        <div class="col-sm">
                                            <div class='tempsR '>
                                                <i class='fa fa-clock colour-icon d-flex justify-content-center'></i>
                                                <p class="d-flex justify-content-center"><?php echo "{$recette->getTempsRecette()}mn" ?></p>
                                            </div>
                                        </div>
                                        <div class="col-sm ">
                                            <div class='datePost justify-content-center'>
                                                <i class='far fa-calendar-alt colour-icon d-flex justify-content-center "'></i>
                                                <p class="d-flex justify-content-center"><?php echo $date ?></p>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class='nbCommentaire '>
                                                <i class='fa fa-comments d-flex justify-content-center colour-icon align-bottom'></i>
                                                <p class="d-flex justify-content-center"><?php echo "{$nbCom[$j]['0']}";
                                                                                            $j++; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src="~/../js/recette.js"></script>
</body>

</html>