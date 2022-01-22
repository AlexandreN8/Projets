package ville;

import java.util.LinkedHashMap;
import java.util.Scanner;
import java.util.Map.Entry;

import equipement.Arme;
import equipement.Tresor;
import personnage.Personnage;

//Classe non demandé
//Une classe qui va nous servir de Menu afin de donner un sens aux mécaniques de trésor 
public class Ville {

	//VARIABLE
	private final String nomVille;
	// On opte pour une LinkedHashMap car il va nous falloir intégrer la notion de
	// position
	// afin de séléctionner une arme chez le marchand
	private LinkedHashMap<Arme, Integer> magasin;

	//CONSTRUCTEUR
	public Ville(String nomVille) {
		this.nomVille = nomVille;
		this.magasin = new LinkedHashMap<>();

		//Remplissage du magasin
		magasin.put(new Arme("Excalibur", 9000, 9999), 999);
		magasin.put(new Arme("Epée d'entrainement", 1, 12), 25);
		magasin.put(new Arme("Caliburn", 1, 35), 250);
		magasin.put(new Arme("Arc en acier", 15, 25), 150);
	}

	public LinkedHashMap<Arme, Integer> getMagasin() {
		return magasin;
	}

	public String getNomVille() {
		return nomVille;
	}

	public int ville(Personnage joueur, Scanner scan) {
		System.out.println("Bienvenue dans le quartier commercial !");
		System.out.println(
				"1 - Acheter objet\t " + " 2 - Vendre objet\t" + " \n3 - Auberge\t" + "	  4 - Retourner en ville\t");
		int choix = verifierHasInt(scan);

		while (choix != 1 && choix != 2 && choix != 3 && choix != 4) {
			choix = verifierHasInt(scan);
		}

		return choix;
	}

	public int acheterObjet(Personnage joueur, Scanner scan) {
		// "i" sert a représenté les numéros d'indice de la linkedHashMap
		int i = 0;
		System.out.println("\nBienvenue dans l'armurerie !\n");
		for (Entry<Arme, Integer> entry : this.getMagasin().entrySet()) {
			System.out.println(i + " - Prix : " + entry.getKey() + " => " + entry.getValue());
			i++;
		}

		int bourseActuel = joueur.getBourse();

		System.out.println("\nVous possédez " + bourseActuel + " pièces d'or");
		System.out.println("\nEntrer le numéro de l'arme désiré ou 999 pour partir: ");

		// On utilise la propiété des LinkedHashMap afin d'obtenir une Array indéxée
		Object[] keys = this.getMagasin().keySet().toArray();
		// On va demander a l'utilisateur de selectionné l'indice de l'array
		int achat = verifierHasInt(scan);

		// Tant que la linkedHashMap ne contient pas le choix de l'utilisateur on
		// redemande
		while (achat < 0 || achat >= keys.length) {
			if (achat == 999) { // Si l'utilisateur souhaite sortir du magasin
				System.out.println("\nA bientôt !\n");
				return 3;
			}
			System.out.println("Je n'ai pas cet article en stock. Désirer vous autre chose ?");
			achat = verifierHasInt(scan);
		}

		// Si le choix de l'utilisateur est contenu dans la map ET qu'il a la somme
		// nécéssaire
		if (achat != 999 && bourseActuel >= this.magasin.get(keys[achat])) {
			joueur.equiperArme((Arme) keys[achat]);
			joueur.setBourse(bourseActuel -= this.magasin.get(keys[achat]));
			this.magasin.remove(keys[achat]);
			System.out.println("Vous êtes équipés de : " + joueur.getArme() + " il vous reste " + joueur.getBourse()
					+ " pièces d'or.");
		}

		// Si le choix est contenu dans le magasin mais qu'il n'as pas la somme
		else if (bourseActuel < this.magasin.get(keys[achat])) {
			System.out.println("Revenez quand vous aurez réunis la somme.");
		}
		System.out.println("\nA bientôt !\n");
		return 3;
	}

	public int vendreMagasin(Personnage joueur, Scanner scan) {
		System.out.println("\nBienvenue dans la quicaillerie !\n");

		System.out.println("Inventaire joueur: ");
		for (Entry<Tresor, Integer> entry : joueur.getInventaireTresor().entrySet()) { // On affiche l'inventaire de
																						// trésors du joueur
			System.out.println(entry.getKey().getDescription() + " prix : " + entry.getKey().getNbOr() + " quantité : "
					+ entry.getValue());
		}
		int bourse = joueur.getBourse();

		int vente = -999;
		if (joueur.getInventaireTresor().isEmpty()) { // Si son inventaire est vide il est expulsé du shop
			System.out.println("\nVous n'avez pas de trésors revenez plus tards\n");
			return 2;
		}

		do {
			System.out.println("\nVendre vos ressources ? " + "\n1 - Vendre\t" + " 2 - Retour\t");
			vente = verifierHasInt(scan);

			if (vente == 1) { // S'il souhaite vendre on parcours la hashMap et pour chaque ressource on
								// multiplie la quantité d'or associé à la clé avec la valeur représentant la
								// quantité de trésors de ce type
				for (Entry<Tresor, Integer> entry : joueur.getInventaireTresor().entrySet()) {
					joueur.setBourse(bourse += entry.getKey().getNbOr() * entry.getValue());
				}
				joueur.getInventaireTresor().clear(); // On retire les trésors vendus de l'inventaire
				;
				System.out.println("\nTrésors vendus. Vous possédez désormais : " + joueur.getBourse() + " po.\n");
			} else
				return 2;
		} while (vente != 1 && vente != 2);

		return 3;
	}

	// Une fonction qui permet de ne pas se retrouver bas en pv pour le reste
	// du jeu
	public int auberge(Personnage joueur, Scanner scan) {
		System.out.println("\nBienvenue à l'auberge ! Voulez-vous une chambre pour 10 pièces d'or ?");
		System.out.println("1 - Oui, payer 10po\t" + " 2 - Retourner au Quartier commercial\t");

		int choix = verifierHasInt(scan);
		int bourseActuel = joueur.getBourse();

		switch (choix) {
		case 1:
			if (bourseActuel >= 10) { // Se reposer coute 10 po
				joueur.setPvActuel(joueur.getPvMax());
				joueur.setBourse(bourseActuel -= 10);
				System.out.println("ZzZzzZzzz.... Vous avez récupéré vos pv. \nBourse : " + joueur.getBourse() + "\n");
				break;
			} else {
				System.out.println("... Pas assez de po, au revoir !\n");
				break;
			}
		case 2:
			break;
		default:
			System.out.println("Commande inconnue.");
		}
		return 2;
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
