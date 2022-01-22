<!DOCTYPE html>
<html>

<head>
    <title>Mise à jour recette</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> </script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="~/../css/recetteForm.css">
    <title>MarmIUTons - Update recette</title>
    <script>
        // On veux faire passer la variable numRecette à ajax
        ajax_numRecette = <?php echo $recette->getNumRecette() ?>;
    </script>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>

        <div class="container" id="mb">
            <!--DEBUT PANEL-->
            <form class="formMain" action="routeur.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="chosePathRecette">
                <input type="hidden" name="numRecette" value="<?php echo $recette->getNumRecette() ?>">

                <div class="container w-60" id="container-tab-form">
                    <div class="row justify-content-center p-5 " id="header-pnl">
                        <p class="h1">Mettre à jour une recette</p>
                        <p class="h5">Veuillez remplir les informations dans chaque onglets.</p>
                        <?php
                        extract($_POST);

                        if (!empty($message)) {
                            echo "<ul>";
                            foreach ($message as $error)
                                echo "<li class='errorBox'>$error</li>";
                        }
                        ?>
                    </div>

                    <ul class="nav nav-tabs m-0 nav-fill nav-justified justify-content-center " id="over-ul-boot">
                        <li class="nav-item">
                            <a class="nav-link active" href="#Informations" data-bs-toggle="tab"><i class="fas fa-table"></i> <br> Informations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#Catégories" data-bs-toggle="tab"><i class="fas fa-tags"></i> <br> Catégories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#Ingrédients" data-bs-toggle="tab"><i class="fas fa-lemon"></i> <br> Ingrédients et ustensiles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#Explications" data-bs-toggle="tab"><i class="fas fa-book"></i> <br> Explications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#Soumettre" data-bs-toggle="tab"><i class="fas fa-paper-plane"></i> <br> Soumettre</a>
                        </li>
                    </ul>
                    <div class="tab-content p-3" id="body-pnl">
                        <!--DEBUT PANEL INFORMATIONS-->
                        <div id="Informations" class="tab-pane active">
                            <div class="container">
                                <p class="h3">Informations</p>
                                <div class="mb-5" id="name-input">
                                    <label class="form-label" for="name-recette">Nom de la recette*: </label>
                                    <input class="form-control" id="name-recette" type="text" value="<?php echo $recette->getNomRecette() ?>" name="nomRecette" placeholder="Entrez un nom pour votre recette">
                                </div>
                                <div class="mb-5">
                                    <div class="row" id="break-time-col">
                                        <div class="col">
                                            <div class="row">
                                                <label class="form-label details">Temps de préparation*</label>
                                                <div class="input-group mb-3 grp-time">
                                                    <input type="number" class="form-control temps" min="0" name="tempsPreparationH" value="<?php echo $arrayPrepa[0] ?>">
                                                    <span class="input-group-text">h</span>
                                                    <input type="number" class="form-control temps" min="0" max="59" name="tempsPreparationM" value="<?php echo $arrayPrepa[1] ?>">
                                                    <span class=" input-group-text">mn</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <label class="form-label details">Temps de cuisson</label>
                                                <div class="input-group mb-3 grp-time">
                                                    <input class="form-control temps" type="number" min="0" name="tempsCuissonH" value="<?php echo $arrayCuisson[0] ?>">
                                                    <span class="input-group-text">h</span>
                                                    <input class="form-control temps" type="number" min="0" max="59" name="tempsCuissonM" value="<?php echo $arrayCuisson[1] ?>">
                                                    <span class="input-group-text">mn</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FIN PANEL INFORMATIONS-->
                        <!--DEBUT PANEL CATEGORIE-->
                        <div id="Catégories" class="tab-pane">
                            <p class="h3">Catégorie de la recette</p>
                            <p class="h5 mb-3">Veuillez séléctionner une catégorie pour votre recette.</p>
                            <div class="grid">
                                <div class="input-box mb-3">
                                    <div class="flex-row">
                                        <?php
                                        // On assigne la classe active a celle qui est associé à la recette
                                        foreach ($lesCat as $categorie => $value) {
                                            if ($value['idCategorie'] == $recette->getIdCategorie()) {
                                                echo "<input class='categorie-plat active' name='categoriePlatActive' value='{$value['nomCategorie']}' onclick='toggleCat()' readonly>";
                                            } else {
                                                echo "<input class='categorie-plat' name='categoriePlat' value='{$value['nomCategorie']}' onclick='toggleCat()' readonly>";
                                            }
                                        }

                                        ?>
                                    </div>
                                </div>
                                <div class="input-box mb-3">
                                    <p class="h3">Tag recette*</p>
                                    <p class="h5">Ajouter des mots clés à votre recette.</p>
                                    <div class="flex-row">
                                        <?php
                                        // On crée les tag inactif
                                        foreach ($lesTag as $tag => $value) {
                                            echo "<input class='tagBtn' name='tag' value='{$value->getNomTag()}' > ";
                                        }
                                        // puis la tag actif récupéré avant
                                        $i = 999;
                                        foreach ($tagRecettes as $tag) {
                                            echo "<input class='tagBtn active' name='tagActive_$i' value='{$tag->getNomTag()}' > ";
                                            $i++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="difficulte">
                                <p class="h3">Difficulte*</p>
                                <div class="categorie mb-5 mt-5">
                                    <input class="hidden radio-label" type="radio" id="radio1" name="difficulte" value="facile" <?php if ($recette->getIdDifficulte() == 1) echo "checked" ?>>
                                    <label class="radiolbl" for="radio1">
                                        <span>Facile</span>
                                    </label>

                                    <input class="hidden radio-label" type="radio" id="radio2" name="difficulte" value="moyen" <?php if ($recette->getIdDifficulte() == 2) echo "checked" ?>>
                                    <label class="radiolbl" for="radio2">
                                        <span>Moyen</span>
                                    </label>

                                    <input class="hidden radio-label" type="radio" id="radio3" name="difficulte" value="difficile" <?php if ($recette->getIdDifficulte() == 3) echo "checked" ?>>
                                    <label class="radiolbl" for="radio3">
                                        <span>Difficile</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--FIN PANEL CATEGORIE-->
                        <!--DEBUT PANEL INGREDIENT/USTENSILE-->
                        <div id="Ingrédients" class="tab-pane">
                            <div class="ingredient_ustensile">
                                <p class="h3">Ingredients* & Ustensiles*</p>
                                <div class="ingredient-box">
                                    <div class="nav-noboot">
                                        <ul>
                                            <li onclick="tabs(0)" class="ingredient active">Ingredient</li>
                                            <li onclick="tabs(1)" class="ustensile">Ustensile</li>
                                        </ul>
                                    </div>
                                    <div class="pr-body">
                                        <div class="ingredient2 tab">
                                            <div class="grpBoxIng">
                                                <!--Tab ingredients-->
                                                <div class="input-ing">
                                                    <div class="searchIng">
                                                        <select name="nomIngredient" id="searchIng">
                                                            <option>Chercher un ingredient</option>
                                                            <?php
                                                            foreach ($listeIng as $ing) {
                                                                echo "<option> {$ing['nomIngredient']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="searchIng">
                                                        <input class="qte" type="number" min="0" placeholder="Quantité" name="quantite">
                                                    </div>
                                                    <div class="searchIng">
                                                        <select name="typeMesure" id="typeM">
                                                            <option value="kg">kg</option>
                                                            <option value="g">g</option>
                                                            <option value="mg">mg</option>
                                                            <option value="l">l</option>
                                                            <option value="ml">ml</option>
                                                            <option value="cl">cl</option>
                                                            <option value="u">unité</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="btn-ing">
                                                    <input type="submit" name="ajouterIng" value="Ajouter" id="submit2" onclick="return addLiIngredient()">
                                                </div>
                                            </div>
                                            <p class="h5" id="mesIngredients">Mes ingredients</p>
                                            <ul id="ingList">
                                                <!--Les li créé dynamiquement avant un refresh-->
                                                <?php
                                                if (!empty($lesIngredients)) {

                                                    foreach ($lesIngredients as $ingredient) {
                                                        echo "<li>{$ingredient->getQuantite()} {$ingredient->getTypeMesure()} - {$ingredient->getNomIngredient()}
                                                    <button type='button' name='removeUpdate' class='$i remove'><i class='fas fa-minus'></i>
                                                    </button>
                                              </li>";
                                                        $i++;
                                                    }
                                                }
                                                ?>
                                                <!--Ici les li créé dynamiquement-->
                                            </ul>
                                        </div>
                                        <!--Fin Tab ingredients-->
                                    </div>
                                    <div class="ustensile tab">
                                        <!--Tab Ustensiles-->
                                        <div class="grpBoxIng">
                                            <div class="input-ing">
                                                <div class="searchUst">
                                                    <select name="nomUstensile" id="searchUst">
                                                        <option>Chercher un ustensile</option>
                                                        <?php
                                                        foreach ($listeUst as $ust) {
                                                            echo "<option> {$ust['nomUstensile']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="searchUst">
                                                    <input class="qteUst" type="number" min="0" placeholder="Quantité" name="quantite">
                                                </div>
                                            </div>
                                            <div class="btn-ing">
                                                <input type="submit" name="ajouterUst" value="Ajouter" id="submit3" onclick="return addLiUstensile()">
                                            </div>
                                        </div>
                                        <p class="h5" id="mesUstensiles">Mes ustensiles</p>
                                        <ul id="ustList">
                                            <!--Les li créé dynamiquement avant un refresh-->
                                            <?php
                                            if (!empty($lesUstensiles)) {
                                                $i = 0;
                                                foreach ($lesUstensiles as $ustensile) {
                                                    echo "<li>{$ustensile->getQteUstensile()} - {$ustensile->getNomUstensile()}<button type='button' name='removeUpdate' class='$i remove2'><i class='fas fa-minus'></i></button></li>";
                                                    $i++;
                                                }
                                            }
                                            ?>
                                            <!--Ici les li créé dynamiquement-->
                                        </ul>
                                        <!--Fin Tab Ustensiltes-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FIN PANEL INGREDIENT/USTENSILE-->
                        <!--DEBUT PANEL EXPLICATIONS-->
                        <div id="Explications" class="tab-pane">
                            <!--Box etapes-->
                            <div class="explain-box">
                                <p class="h3">Explication de la recette* </p>
                                <div id="etape-box">
                                    <?php
                                    foreach ($lesExplication as $etape) {
                                        echo "<label for='box-explain'>Etape " . intval($etape->getPosEtape() + 1) . " </label>";
                                        echo "<textarea class='box-explain' name='boxExplain_{$etape->getPosEtape()}' placeholder='Veuillez saisir les instruction pour cette étape.'>{$etape->getDescriptionEtape()}</textarea>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <!--FIN Box etapes-->
                        </div>
                        <!--FIN PANEL EXPLICATIONS-->
                        <!--DEBUT PANEL INFORMATIONS-->
                        <div id="Soumettre" class="tab-pane">
                            <p class="h3">Mettre à jour votree recette</p>
                            <p class="h5">Avez-vous bien remplis toutes les * ?</p>
                            <div class="buttonSub">
                                <input type="submit" name="update" value="Update" id="update">
                            </div>
                        </div>
                        <!--FIN PANEL INFORMATIONS-->
                    </div>
                </div>
            </form>
            <!--FIN PANEL-->
        </div>
    </div>
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="~/../js/recetteForm.js"></script>
</body>

</html>