package jeu;

import java.util.Random;
import java.util.Scanner;
import java.util.Map.Entry;

import equipement.Tresor;
import monstre.LoupGarou;
import monstre.Monstre;
import monstre.Slime;
import monstre.Squelette;
import personnage.Personnage;
import ville.Ville;

public class Duel {

	// On va creer un menu HUB qui va nous permettre de choisir le JvJ ou JvIA ou la
	// ville entre 2 aventures
	public int HUB(Personnage joueur, Ville ville, Scanner scan) {
		System.out.println("\n|----------------------------------------------------------");
		System.out.println("Choisissez votre prochaine déstination :");
		System.out.println("1 - Partir a l'aventure\t" + "	2 - Quartier commercial\t" + "	3 - Quitter le jeu\t");

		int actionVille = 0;
		// Ce try/catch va attraper l'error de format de saisie de l'utilisateur
		// (fonctionne avec les do...while)
		try {
			do {
				
				actionVille = scan.nextInt();
				
			} while (actionVille == 0 && actionVille > 3);
		} catch (Exception e) {
			System.out.println("Format de réponse inconnu.");
			scan.next();
		}
		
		return actionVille;
	}

	// Choisir le mode de jeu JoueurVsJoueur ou JoueurVsIA
	public int nbJoueur(Scanner scan) {
		int choix = 3;
		System.out.println(
				"Voulez vous jouer mesurer vos capacité contre un autre joueur ou entrer dans le donjon à la recherche de trésor ?\n1 pour un autre joueur. \n2 pour le donjon.");

		choix = verifierHasInt(scan); // appel de la fonnction verifiant le format de saisie (fonctionne pour tout
									  // type de boucle)

		while (choix != 1 && choix != 2) {
			System.out.println("\nCette entrée ne figure pas parmis les options.");
			System.out.println("\n1 - pour un autre joueur\t" + "	\n2 - pour le donjon.\t");
			choix = verifierHasInt(scan); //On appel de nouveau la verification
		}

		return choix;
	}

	// La boucle de combat JoueurVsJoueur
	public void combat2Joueur(Personnage j1, Personnage j2, Ville ville, Scanner scan) {
		System.out.println("\n|----------------------------------------------------------");
		// On va boucler sur les choix des joueurs jusque l'un deux renvois le bool mort
		// ou fuite
		do {
			// JOUEUR 1
			System.out.println("\nTOUR DE " + j1.getNomPersonnage().toUpperCase()); // On affiche qui joue
			choixAction(j1, j2, ville, scan);
			if (j2.isDead())// On vérifie si le joueur 2 est mort de l'attaque
				continue;  //On va à la fin de la boucle
			if (j1.isFuite())
				break; // Le choix d'action été la fuite on casse la boucle.

			// JOUEUR 2
			System.out.println("\nTOUR DE " + j2.getNomPersonnage().toUpperCase());
			choixAction(j2, j1, ville, scan);
			if (j1.isDead())
				continue;
			if (j2.isFuite())
				break;
			
		} while (j1.isDead() == false && j2.isDead() == false);

		// On determine le vainqueur en verifiant qu'il n'est pas fuis
		// puis on appel la fonction qui transfert les tresors
		if (j1.isDead() && !j1.isFuite() && !j2.isFuite()) {
			System.out.println("Victoire du joueur 2 " + j2.getNomPersonnage() + "\n");
			recupererInventairePerdant(j2, j1); // Le joueur 2 recupère les trésors
		} else if (j2.isDead() && !j1.isFuite() && !j2.isFuite()) {
			System.out.println("Victoire du joueur 1 " + j1.getNomPersonnage() + "\n");
			recupererInventairePerdant(j1, j2); // Le joueur 1 récupère les trésors
		}

		// si l'un des joueurs a fuis, on reinitialise le boolean de fuite
		if (j1.isFuite() || j2.isFuite()) {
			j1.setFuite(false);
			j2.setFuite(false);
		}
	}

	// Boucle du donjon aka joueur VS IA
	public void conqueteDonjon(Personnage j1, Monstre monstre, Ville ville, Scanner scan) {
		// ARRAY DE MONSTRE HERITE DE PERSONNAGE
		Monstre[] listeMonstre = { new Squelette("Squelette", 15), new LoupGarou("Loup-Garou", 40),
				new Slime("Slime", 5) };
		// On utilise le random pour generer un monstre aleatoire
		Random rand = new Random();
		int choixMonstre = rand.nextInt(listeMonstre.length); // On va generer un monstre aleatoire depuis notre liste
		monstre = listeMonstre[choixMonstre];
		
		System.out.println("\n|----------------------------------------------------------");
		System.out.println("ATTENTION Rencontre avec : " + monstre.getNomPersonnage());
		do {
			// JOUEUR
			choixAction(j1, monstre, ville, scan);
			if (monstre.isDead())
				continue;
			if (j1.isFuite())
				break; // Le choix d'action été la fuite on casse la boucle.

			// IA
			monstre.actionTourMonstre(monstre, j1); // On appel la methode de l'IA
			if (j1.isDead())
				break;
			if (monstre.isFuite()) // Le slime a la possibilité de fuir
				break;

		} while (j1.isDead() == false && monstre.isDead() == false);

		//On appel la fonction qui transfert les tresors
		if (monstre.isDead() && !j1.isFuite() && !monstre.isFuite()) {
			recupererInventairePerdant(j1, monstre); // Le joueur a gagné
		}

		if (j1.isFuite()) {
			j1.setFuite(false);
		}
		
	}

	// La fonction qui permet de modéliser les tours d'un joueur avec une liste de
	// choix
	public void choixAction(Personnage jActuel, Personnage j2, Ville ville, Scanner scan) {
		System.out.println("\nChoisissez votre prochaine action :");
		System.out.println("1 - Attaquer \t" + "2 - Se soigner\t");
		System.out.println("3 - fuir \t");

		int actionTour;

		// On va faire appel aux fonctions choisis par l'utilisateurs
		do {
			actionTour = verifierHasInt(scan);
			
			switch (actionTour) {
			case 1: // Attaquer
				int degatInflige = jActuel.attaquerPersonnage(jActuel.getArme(), j2);
				System.out.println(degatInflige + " points de dégat infligé à " + j2.getNomPersonnage() + ". "
						+ j2.getNomPersonnage() + ": " + j2.getPvActuel() + " pv restant");
				break;
				
			case 2: // Soigner
				this.soignerCible(jActuel, j2, scan);
				break;
				
			case 3: // Fuir
				System.out.println("Vous fuyez le combat.");
				jActuel.setFuite(true);// permet de break la boucle de combat
				break;
				
			default: // Scan inconnu
				System.out.println("Commande invalide.");
			}
		} while (actionTour != 1 && actionTour != 2 && actionTour != 3);
	}

	//Fonction gérant le ciblage du soin
	public void soignerCible(Personnage jActuel, Personnage j2, Scanner scan) {
		//Comme demandé dans l'énoncé on offre la possibilité de soigner le joueur ou un autre personnage
		System.out.println("Cible soin :" + "\n1 - Joueur" + "		2 - " + j2.getNomPersonnage() + "\t");
		
		int cibleHeal = verifierHasInt(scan);
		while (cibleHeal != 1 && cibleHeal != 2) {
			System.out.println("Entrer non comprise.");
			cibleHeal = verifierHasInt(scan);
		}
		
		int soin;
		if(cibleHeal == 1) { //On soigne le joueur
			soin = jActuel.soignerPersonnage(jActuel);
			System.out.println(jActuel.getNomPersonnage() + " soigné de " + soin + " points de vie");
		}else { //On soigne la cible
			soin = jActuel.soignerPersonnage(j2);
			System.out.println(j2.getNomPersonnage() + " soigné de " + soin + " points de vie");
		}
	}
	
	// Offre le choix entre continuer les combats ou retourner en ville
	public int finCombat(Personnage j1, Ville ville, Scanner scan) {
		int suite = 0;
		System.out.println("\n|----------------------------------------------------------");
		System.out.println("1 - Vous aventurer plus loin\t" + " 2 - Retourner en ville\t");

		suite = verifierHasInt(scan);

		while (suite != 1 && suite != 2) {
			System.out.println("Entrer non comprise.");
			suite = verifierHasInt(scan);
		}

		return suite;
	}

	// Si le joueurs est vaincu
	public int defaite(Personnage joueur) {
		System.out.println("Aie... Défaite, vous êtes rapatrié en ville et perdez vos trésors.");
		joueur.getInventaireTresor().clear();
		joueur.setPvActuel(10);
		System.out.println("Vos pv sont désormais de : " + joueur.getPvActuel());
		return 2; // On retourne la valeur nécéssaire pour revenir à la boucle du HUB

	}

	// Fonction pour recuperer l'inventaire d'un adversaire en cas de
	// victoire
	public void recupererInventairePerdant(Personnage joueur, Personnage adversaire) {
		if (!joueur.isFuite() && !adversaire.isFuite()) { //On vérifie qu'il y ai un gagnant et pas une fuite
			if (!adversaire.getInventaireTresor().isEmpty()) { // Si l'inventaire n'est pas vide on appel prendreTresor()
															   // pour chaque trésors que le monstre possède
				
				for (Entry<Tresor, Integer> entry : adversaire.getInventaireTresor().entrySet()) {
					joueur.prendreTresor(entry.getKey());
					System.out.println("Vous ramasser " + entry.getKey().getDescription() + " *" + entry.getValue()
							+ " sur " + adversaire.getNomPersonnage());
				}
				adversaire.getInventaireTresor().clear(); // On retire les trésors de l'inventaire du perdant
			} else {
				System.out.println("L'adversaire ne possedais pas de trésors.");
			}
		}
	}

	// On crée une fonction vérifiant le format de la saisie de l'utilisateur
	public int verifierHasInt(Scanner scan) {
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
