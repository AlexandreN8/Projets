<!DOCTYPE html>
<html lang="fr">

<head>
    <title>header</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="~/../css/header.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>MamruIUTon</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid flex-column">
                <div class="row justify-content-space w-100  fs-4">
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target=".navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse navbarCollapse">
                        <div class="navbar-nav">
                            <a href="routeur.php" class="navbar-brand d-flex justify-content-start fs-1">Marm<span style="color:#00b4cc;">IUT</span>ons</a>

                        </div>
                        <div class="navbar-nav ms-auto d-flex justify-content-end">

                            <?php
                            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                            ?>
                                <a href=" routeur.php?action=afficherRecetteForm" style="background-color: #55acee;" class="btn btn-primary btn-lg" tabindex="-1" role="button" aria-disabled="true"><i class="fa fa-plus"></i> Recette</a>
                                <a href="routeur.php?action=profile" class="btn btn-primary btn-lg marginSecondary" tabindex="-1" role="button" aria-disabled="true"><i class="fas fa-user"></i> Profile</a>
                            <?php
                            } else {
                            ?>
                                <a href="routeur.php?action=inscription" class="btn btn-primary btn-lg" tabindex="-1" role="button" aria-disabled="true"><i class="fas fa-user-plus"></i> Inscription</a>
                                <a href="routeur.php?action=login" class="btn btn-secondary btn-lg marginSecondary" tabindex="-1" role="button" aria-disabled="true"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="justify-content-lg-center fs-4">
                    <div class="collapse navbar-collapse navbarCollapse">
                        <div class="navbar-nav d-flex align-items-center">
                            <a href="routeur.php?action=recherche_recettes&sTag=Catégorie&sIng=Ingrédient&terme=facile" class="nav-item nav-link">Idée facile</a>
                            <a href="routeur.php?action=recherche_recettes&sTag=Catégorie&sIng=chocolat&terme=" class="nav-item nav-link">Idée chocolat</a>
                            <a href="routeur.php" class="nav-item nav-link">
                                <span class="recette-link">Recettes</span>
                            </a>
                            <a href="routeur.php?action=recherche_recettes&sTag=Poisson&sIng=Ingrédient&terme=" class="nav-item nav-link">Idée poisson</a>
                            <a href="routeur.php?action=recherche_recettes&sTag=Viande&sIng=Ingrédient&terme=" class="nav-item nav-link">Idée viande</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="~/../js/header.js"></script>
</body>

</html>