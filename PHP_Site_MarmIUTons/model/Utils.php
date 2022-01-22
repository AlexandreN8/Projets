<?php
class Utils
{
    // Formatter les temps de recettes en entrée et sortie de BD - src : StackOverflow
    public static function hoursToMinutes($hours)
    {
        $minutes = 0;
        if (strpos($hours, ':') !== false) {
            // Split hours and minutes. 
            list($hours, $minutes) = explode(':', $hours);
        }
        return $hours * 60 + $minutes;
    }

    public static function minutesToHours($minutes)
    {
        $hours = (int)($minutes / 60);
        $minutes -= $hours * 60;
        return sprintf("%d:%02.0f", $hours, $minutes);
    }
    //---- Fonction permettant de vider $_POST['ustensile_soumis'] et ['ingredient_soumis']
    // on l'utilise avant l'accès à certaines pages, les 2 controller en ont besoin
    public static function clearArrayTemp()
    {
        if (isset($_SESSION['ustensile_soumis'])) {
            $_SESSION['ustensile_soumis'] = array();
        }
        if (isset($_SESSION['ingredient_soumis'])) {
            $_SESSION['ingredient_soumis'] = array();
        }
    }
    // Si la première action est l'update recette il faut créer les indices
    public static function setArrayTemp()
    {
        if (!isset($_SESSION['ingredient_soumis'])) {
            $_SESSION['ingredient_soumis'] = array();
        }
        if (!isset($_SESSION['ustensile_soumis'])) {
            $_SESSION['ustensile_soumis'] = array();
        }
    }
}
