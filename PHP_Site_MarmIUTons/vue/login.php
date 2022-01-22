<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="~/../css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>MarmIUTons - Connexion</title>

</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>
        <form class="container" action="routeur.php" method="post">
            <input type="hidden" name="action" value="connectionUser">
            <div class="en-tete">
                <span class='titre'>Se connecter</span>
                <?php
                if (!empty($message)) {
                    echo "<p style='color:red; font-size:1.5em; border=1px solid red;'>$message</p>";
                }
                ?>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Nom d'utilisateur</span>
                    <input type="text" name="pseudo" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="input-box">
                    <span class="details">Mot de passe</span>
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="btnCo">
                    <input type="submit" name="envoyer" value="Se connecter">
                </div>
            </div>
            <a href="#">Mot de passe oublié</a>
            <br>
            <a href="routeur.php?action=inscription">Pas encore inscrit ?</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <?php include("~/../vue/footer.php"); ?>
</body>

</html>