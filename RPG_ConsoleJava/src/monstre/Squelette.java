package monstre;

import java.util.Random;

import equipement.Arme;
import equipement.Tresor;
import personnage.Personnage;

//Represente une IA du donjon
public class Squelette extends Monstre{
	//VARIABLE
	Random rand = new Random();
	
	private int nbPotion; //Quantité de potion disponible sur les squelettes
	
	//CONSTRUCTEUR
	public Squelette(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
		
		this.typeMonstre = "Undead";
		
		this.nbPotion = 1;
		this.arme = new Arme("Epee rouillée", 1, 3);
		
		//Loot monstre
		this.inventaireTresor.put(new Tresor("Os fracturé", 20), 1);
	}

	@Override
	public int attaquerPersonnage(Arme arme, Personnage cible) {
		int degatInflige = this.arme.degat();
		cible.setPvActuel(cible.getPvActuel() - degatInflige); 
		return degatInflige;
	}
	
	public int lancerOs(Personnage cible) {
		System.out.println("\n" + this.getNomPersonnage() + " Lance un os !");
		int degatInflige = rand.nextInt(4-2) + 2;
		cible.setPvActuel(cible.getPvActuel() - degatInflige); 
		return degatInflige;
	}

	@Override
	public int soignerPersonnage(Personnage cible) {
			this.pvActuel += 4;
			return 4;
	}

	//Methode programmant l'action de l'IA
	@Override
	public void actionTourMonstre(Personnage monstre, Personnage joueur) {
		int action;
		int temp;
		temp = (Math.random() <= 0.5) ? 1 : 2; //On détermine une attaque aléatoire avec probabilité 1/2
		if(this.getPvActuel() <= 3 && this.nbPotion == 1) {
			action = this.soignerPersonnage(this);
			this.nbPotion -= 1;
			System.out.println(this.getNomPersonnage() + " ramasse une potion et récupère 4 pv. Pv actuel : " + this.getPvActuel());
		}
		else if(temp == 1) {
			action = this.attaquerPersonnage(this.arme, joueur);
			System.out.println(action + " points de dégat infligé à " + joueur.getNomPersonnage() + ". " + joueur.getNomPersonnage() + ": " + joueur.getPvActuel() + " pv restant");
		}
		else {
			action = this.lancerOs(joueur);
			System.out.println(action + " points de dégat infligé à " + joueur.getNomPersonnage() + ". " + joueur.getNomPersonnage() + ": " + joueur.getPvActuel() + " pv restant");

		}
		
	}
}
