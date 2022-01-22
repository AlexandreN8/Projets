package jeu;

import java.util.Scanner;

import personnage.Personnage;
import ville.Ville;

//CLASSE MAIN
public class Partie {
	public static void main(String[] args) {

		// On va envoyer ce scanner à toutes les fonctions qui en ont besoin pour le
		// fermer quand on quitte le jeu.
		Scanner scan = new Scanner(System.in);
		// Ce scanner permet d'éviter les conflits entre nextLine et nextInt
		Scanner scanTexte = new Scanner(System.in);

		Personnage joueur1;
		Personnage joueur2;
		Ville laVille = new Ville("Paris"); // Permet de faire appel à toutes les fonctions de la ville
											// (magasin/auberge)
		Duel unDuel = new Duel(); // Permet de faire appel à toutes les fonctions relatives aux duels

		System.out.println("Bienvenue Aventurier");
		System.out.println("Veuillez créer votre personnage");
		joueur1 = CreerPerso.creerPersoFactory(scan, scanTexte);

		// Plutot que de multiplier les switch dans les fonctions pour appeller d'autres fonction on fais un switch dans le main : 
		// Boucle 1 : HUB permet de voyager vers 2 diréctions distinctes
		// Boucle imbriqué = 1 : Vers aventure Joueurs contre joueur ou donjon (IA)
		// Boucle imbriqué = 2 : Vers la ville et ses fonctions
		int choixAventure;
		do {
			choixAventure = unDuel.HUB(joueur1, laVille, scan); // Boucle principale, avant de quitter le jeu on passe
																// par le HUB
			int choix = 0;

			if (choixAventure == 1) {
				choix = unDuel.nbJoueur(scan);
				while (choixAventure == 1) { // Si le joueur a choisis l'aventure on entre dans une boucle sur ce
											// scenario tant qu'il ne reviens pas au HUB
					switch (choix) {
					case 1: // JoueurVsJoueur
						System.out.println("Création du joueur 2");
						joueur2 = CreerPerso.creerPersoFactory(scan, scanTexte); // On crée un 2eme joueur

						unDuel.combat2Joueur(joueur1, joueur2, laVille, scan); // Boucle duel 2 joueurs
						if (joueur1.isDead()) {
							choixAventure = unDuel.defaite(joueur1);
							break;
						}
						choixAventure = unDuel.finCombat(joueur1, laVille, scan); // Choix combattre de nouveau ou ville
						break;

					case 2:// JoueurVsIA
						unDuel.conqueteDonjon(joueur1, null, laVille, scan);
						if (joueur1.isDead()) { // Defaite du joueur
							choixAventure = unDuel.defaite(joueur1);
							break;
						}
						choixAventure = unDuel.finCombat(joueur1, laVille, scan);
						break;
					}
				}
			} else if (choixAventure == 2) {// Si le joueur choisis d'aller en ville on boucle sur ce scenario tant qu'il ne
											// reviens pas au HUB
				while (choix != 4) { 
					choix = laVille.ville(joueur1, scan);
					switch (choix) {
					case 1:
						choix = laVille.acheterObjet(joueur1, scan);
						break;
					case 2:
						choix = laVille.vendreMagasin(joueur1, scan);
						break;
					case 3:
						choix = laVille.auberge(joueur1, scan);
						break;
					case 4:
						break; // retour au HUB
					}
				}
			}
		} while (choixAventure != 3); // Quitter le jeu si 3
		System.out.println("A bientot pour de nouvelles aventures !");
		scan.close();
		scanTexte.close();
	}
}
