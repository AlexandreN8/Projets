<?php
session_start(); // iNITIALISATION DE LA SESSION SUR L4ENSEMBLE DES PAGES

class Connexion
{

  // attributs de la classe Connexion paramètres de connexion à la base
  static private $hostname = '127.0.0.1';
  static private $database = 'anovais';
  static private $login = 'anovais';
  static private $password = 'avgbSMdT6_7V+KolL5ib';

  // attribut de la classe Connexion paramètres d'encodage 
  static private $tabUTF8 = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

  // attribut de la classe Connexion qui recevra l'instance PDO
  static private $pdo;

  // getter
  static public function pdo()
  {
    return self::$pdo;
  }

  // fonction de connexion
  static public function connect()
  {
    $h = self::$hostname;
    $d = self::$database;
    $l = self::$login;
    $p = self::$password;
    $t = self::$tabUTF8;
    self::$pdo = new PDO("mysql:host=$h;dbname=$d", $l, $p, $t);
    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
}
