package jeu;

import java.util.Scanner;

import personnage.Clerc;
import personnage.Guerrier;
import personnage.Personnage;

//Factory de personnages joueurs
public class CreerPerso {
	public static Personnage creerPersoFactory(Scanner scan, Scanner scanTexte) {
		Personnage persoCreer = null;
		System.out.println("Comment s'appelle votre perso...?");
		String nom = scanTexte.nextLine();
		// On répète le choix de classe tant que la classe choisie n'existe pas
		boolean classeIncorrecte = true;

		do {
			System.out.println("Quelle classe voulez-vous? \n1 - Guerrier\t" + "2 - clerc\t");
			int classeChoisi = verifierHasInt(scan);
			switch (classeChoisi) { // création du personnage
			case 1:
				persoCreer = new Guerrier(nom, 60); // creer guerrier
				classeIncorrecte = false;
				break;
			case 2:
				persoCreer = new Clerc(nom, 40); // creer clerc
				classeIncorrecte = false;
				break;
			default:
				System.out.println("Commande invalide.");
			}
		} while (classeIncorrecte);

		System.out.println(persoCreer + " a vu le jour");
		return persoCreer; // on retourne le combattant créer pour pouvoir l'utiliser ensuite
	}
	
	// On crée une fonction vérifiant le format de la saisie de l'utilisateur
	public static int verifierHasInt(Scanner scan) {
		int choix = 0;
		boolean errorSaisie = true;

		while (errorSaisie) {
			if (scan.hasNextInt()) {
				choix = scan.nextInt();
			} else {
				System.out.println("Format de saisie incompatible. Veuillez faire une nouvelle saisie : ");
				scan.next();
				continue;
			}
			errorSaisie = false;
		}
		return choix;
	}
}