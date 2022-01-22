<!DOCTYPE html>
<html>

<head>
    <title>Fomulaire recette</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link rel="stylesheet" href="~/../css/recetteForm.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>MarmIUTons - Soumission recette</title>
    <script>
        // On veux faire passer la variable numRecette à ajax
        booleanModal = <?php echo $showModalNbEtape ?>;
        ajax_numRecette = ""; // nécéssaire car updateRecette prends un numéro, pas createRecette 
    </script>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>
        <div class="container" id="mb">
            <!--Modal de selection de box-->
            <form action="routeur.php" method="POST">
                <input type="hidden" name="action" value="defineEtapeBox">
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Choix du nombre d'étapes</h5>
                            </div>
                            <div class="modal-body">
                                <label for="nb-e">Nombre d'étapes : </label>
                                <input type="number" min="1" max="9" id="nb-e" name="nbEtapeForm" value="1" placeholder="Entrez le nombre d'etapes ici ...">
                                <br><br>
                                <p class="hr5"> <small>Notez qu'il vous faudra entrer de nouveaux toutes les informations.</small> </p>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" name="choixEtape" value="Valider" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--Fin modal selection de box-->
            <!--DEBUT PANEL-->
            <form class="formMain" action="routeur.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="chosePathRecette">
                <div class="container w-60" id="container-tab-form">
                    <div class="row justify-content-center p-5 " id="header-pnl">
                        <p class="h1">Soumettre une recette</p>
                        <p class="h5">Veuillez remplir les informations dans chaque onglets.</p>
                        <?php
                        extract($_POST);

                        if (!empty($message)) {
                            echo "<ul>";
                            foreach ($message as $error)
                                echo "<li class='errorBox'>$error</li>";
                        }
                        echo "</ul>";
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
                            <a class="nav-link" href="#Photos" data-bs-toggle="tab"><i class="far fa-image"></i> <br> Photos</a>
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
                                    <input class="form-control" id="name-recette" type="text" value="<?php if (isset($_POST['nomRecette'])) echo $_POST['nomRecette'] ?>" name="nomRecette" placeholder="Entrez un nom pour votre recette">
                                </div>
                                <div class="mb-5">
                                    <div class="row" id="break-time-col">
                                        <div class="col">
                                            <div class="row">
                                                <label class="form-label details">Temps de préparation*</label>
                                                <div class="input-group mb-3 grp-time">
                                                    <input type="number" class="form-control temps" min="0" name="tempsPreparationH" value="<?php if (isset($_POST['tempsPreparationH'])) echo $tempsPreparationH;
                                                                                                                                            else echo "0"  ?>">
                                                    <span class="input-group-text">h</span>
                                                    <input type="number" class="form-control temps" min="0" max="59" name="tempsPreparationM" value="<?php if (isset($_POST['tempsPreparationM'])) echo $tempsPreparationM;
                                                                                                                                                        else echo "0"  ?>">
                                                    <span class=" input-group-text">mn</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <label class="form-label details">Temps de cuisson</label>
                                                <div class="input-group mb-3 grp-time">
                                                    <input class="form-control temps" type="number" min="0" name="tempsCuissonH" value="<?php if (isset($_POST['tempsCuissonH'])) echo $tempsCuissonH;
                                                                                                                                        else echo "0"  ?>">
                                                    <span class="input-group-text">h</span>
                                                    <input class="form-control temps" type="number" min="0" max="59" name="tempsCuissonM" value="<?php if (isset($_POST['tempsCuissonM'])) echo $tempsCuissonM;
                                                                                                                                                    else echo "0"  ?>">
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
                                        // On crée autant de div qu'il y a de tag dans la BD
                                        foreach ($lesCat as $categorie => $value) {
                                            if (isset($_POST['categoriePlatActive'])) { // Si l'utilisateur a deja essayer de soumettre on recupere la valeurs
                                                if ($_POST['categoriePlatActive'] == $value['nomCategorie']) {
                                                    echo "<input class='categorie-plat active' name='categoriePlatActive' value='{$value['nomCategorie']}' onclick='toggleCat()' readonly>";
                                                } else {
                                                    echo "<input class='categorie-plat' name='categoriePlat' value='{$value['nomCategorie']}' onclick='toggleCat()' readonly>";
                                                }
                                            } else { // Sinon on laisse les valleurs inactive
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
                                        // On crée autant de div qu'il y a de tag dans la BD
                                        foreach ($lesTag as $tag => $value) {
                                            echo "<input class='tagBtn' name='tag' value='{$value->getNomTag()}' > ";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="difficulte">
                                <p class="h3">Difficulte*</p>
                                <div class="categorie mb-5 mt-5">
                                    <!-- $difficulteSoumise = valeurs soumise par l'utilisateur OU par defaut 'facile'-->
                                    <!-- Ne veux pas passer la variable depuis le controller ... -->
                                    <?php
                                    $difficulteSoumise = "facile";
                                    if (isset($_POST['difficulte'])) {
                                        $difficulteSoumise = $_POST['difficulte'];
                                    }
                                    ?>
                                    <input class="hidden radio-label" type="radio" id="radio1" name="difficulte" value="facile" <?php if ($difficulteSoumise == 'facile') echo "checked"; ?>>
                                    <label class="radiolbl" for="radio1">
                                        <span>Facile</span>
                                    </label>

                                    <input class="hidden radio-label" type="radio" id="radio2" name="difficulte" value="moyen" <?php if ($difficulteSoumise == 'moyen') echo "checked"; ?>>
                                    <label class="radiolbl" for="radio2">
                                        <span>Moyen</span>
                                    </label>

                                    <input class="hidden radio-label" type="radio" id="radio3" name="difficulte" value="difficile" <?php if ($difficulteSoumise == 'difficile') echo "checked"; ?>>
                                    <label class="radiolbl" for="radio3">
                                        <span>Difficile</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--FIN PANEL CATEGORIE-->
                        <!--DEBUT PANEL PHOTOS-->
                        <div id="Photos" class="tab-pane">
                            <p class="h3">Photos de votre recette.</p>
                            <div class="pictures mb-5">
                                <p class="h5">Déposer ici vos images* </p>
                                <input type="file" name="image[]" id="img-up1" accept="image/*" onchange="previewFile(this)">
                                <label for="img-up1" class="lblPreview">
                                    <?php
                                    //On va recuperer l'image en base64 pour les réinserer à chaque refresh
                                    if (!empty($_FILES['image']['tmp_name'][0])) {
                                        $imageData = file_get_contents($_FILES['image']['tmp_name'][0]);
                                    }
                                    ?>
                                    <img <?php if (!empty($_FILES['image']['name'][0])) echo sprintf('src="data:image/png;base64,%s"', base64_encode($imageData));
                                            else echo "src='~/../images/pic.jpg'" ?> alt="background preview image" id="img-preview" style="cursor:pointer;">
                                </label>
                                <input type="file" name="image[]" id="img-up2" accept="image/*" onchange="previewFile(this)">
                                <label for="img-up2" class="lblPreview">
                                    <?php
                                    if (!empty($_FILES['image']['tmp_name'][1])) {
                                        $imageData = file_get_contents($_FILES['image']['tmp_name'][1]);
                                    }
                                    ?>
                                    <img <?php if (!empty($_FILES['image']['name'][1])) echo sprintf('src="data:image/png;base64,%s"', base64_encode($imageData));
                                            else echo "src='~/../images/pic.jpg'" ?> alt="background preview image" id="img-preview2" style="cursor:pointer;">
                                </label>
                                <input type="file" name="image[]" id="img-up3" accept="image/*" onchange="previewFile(this)">
                                <label for="img-up3" class="lblPreview">
                                    <?php
                                    if (!empty($_FILES['image']['tmp_name'][2])) {
                                        $imageData = file_get_contents($_FILES['image']['tmp_name'][2]);
                                    }
                                    ?>
                                    <img <?php if (!empty($_FILES['image']['name'][2])) echo sprintf('src="data:image/png;base64,%s"', base64_encode($imageData));
                                            else echo "src='~/../images/pic.jpg'" ?> alt="background preview image" id="img-preview3" style="cursor:pointer;">
                                </label>
                            </div>
                        </div>
                        <!--FIN PANEL PHOTOS-->
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
                                                            <option value="">Chercher un ingredient</option>
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
                                                if (!empty($_SESSION['ingredient_soumis'])) {
                                                    $i = 1;
                                                    foreach ($_SESSION['ingredient_soumis'] as $data) {
                                                        echo "<li>{$data['quantite']} {$data['typeMesure']} - {$data['nomIngredient']}<button type='button' name='removeUpdate' class='$i remove'><i class='fas fa-minus'></i></button></li>";
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
                                                        <option value="">Chercher un ustensile</option>
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
                                            if (!empty($_SESSION['ustensile_soumis'])) {
                                                $i = 1;
                                                foreach ($_SESSION['ustensile_soumis'] as $data) {
                                                    echo "<li>{$data['qteUstensile']} - {$data['nomUstensile']}<button type='button' name='removeUpdate' class='$i remove2'><i class='fas fa-minus'></i></button></li>";
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
                                <button type="button" class="btn btn-primary btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    Modifier nombre d'étapes
                                </button>
                                <div id="etape-box">
                                    <?php
                                    if (isset($_SESSION['nbEtape'])) {
                                        for ($nb = 1; $nb <= $_SESSION['nbEtape']; $nb++) {
                                    ?>
                                            <label for='box-explain'>Etape <?php echo $nb ?></label>
                                            <br>
                                            <textarea class='form-control box-explain' name="boxExplain_<?php echo $nb ?>" placeholder=' Veuillez saisir les instruction pour cette étape.'><?php if (isset($_POST["boxExplain_$nb"])) echo $_POST["boxExplain_$nb"] ?></textarea>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!--FIN Box etapes-->
                        </div>
                        <!--FIN PANEL EXPLICATIONS-->
                        <!--DEBUT PANEL INFORMATIONS-->
                        <div id="Soumettre" class="tab-pane">
                            <p class="h3">Envoyez votre recette</p>
                            <p class="h5">Avez-vous bien remplis toutes les * ?</p>
                            <div class="buttonSub">
                                <input type="submit" name="submit" value="Soumettre" id="submit">
                            </div>
                            <p><small>Si le formulaire n'est pas totalement renseigné, pensez à re-valider les <span class="customDanger">Tag recettes</span> et <span class="customDanger">Photos</span> </small></p>
                        </div>
                        <!--FIN PANEL INFORMATIONS-->
                    </div>
                </div>
            </form>
            <!--FIN PANEL-->
        </div>
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="~/../js/recetteForm.js"></script>
</body>

</html>