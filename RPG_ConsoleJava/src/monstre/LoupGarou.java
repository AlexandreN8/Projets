package monstre;

import java.util.Random;

import equipement.Arme;
import equipement.Tresor;
import personnage.Personnage;

public class LoupGarou extends Monstre{
	
	//VARIABLE
	Random rand = new Random();
	private int compteurTour; //Nous permet de programmer une attaque après un certain nombre de tour

	//CONSTRUCTEUR
	public LoupGarou(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
		
		this.typeMonstre = "Bete";

		
		this.compteurTour = 0;
		//Loot monstre
		this.inventaireTresor.put(new Tresor("Griffe de loups-Garou", 75), 1);
		this.inventaireTresor.put(new Tresor("Fourrure de loups-Garou", 25), 1);
	}

	@Override
	public int attaquerPersonnage(Arme arme, Personnage cible) {
		int degatInflige = 4;
		cible.setPvActuel(cible.getPvActuel() - degatInflige); 
		return degatInflige;
	}

	@Override
	public int soignerPersonnage(Personnage cible) {
		int soin = 0;
		System.out.println(cible.getNomPersonnage() + " hurle vers la lune, ses blessures se refermes.");
		if(this.pvActuel + 12 <= this.getPvMax()) { // Si les soin ne dépassent pas les pvMax
			this.pvActuel += 12;
			soin = 12;
		}
		else {// Si les soins dépassent les pvMax
			soin = this.getPvMax() - this.getPvActuel(); // On stock la valeur soigné
			this.pvActuel = this.getPvMax();
		}
		return soin;
	}
	
	public int lacerationLoup(Personnage cible) {
		System.out.println("\n" + this.getNomPersonnage() + " vous lacère !");
		int degatInflige = rand.nextInt(8-5+1) + 5;
		cible.setPvActuel(cible.getPvActuel() - degatInflige); 
		return degatInflige;
	}

	@Override
	public void actionTourMonstre(Personnage monstre, Personnage joueur) {
			int action;
			int temp;
			temp = (Math.random() <= 0.5) ? 1 : 2; //On détermine une attaque aléatoire avec probabilité 1/2
			if(compteurTour == 4) {
				action = this.soignerPersonnage(this);
				this.compteurTour = 0;
				System.out.println(action + " pv soignés. Pv actuel : " + this.getPvActuel());
			}
			else if(temp == 1) { // Si le random == 1 le Loup_Garou attaque
				action = this.attaquerPersonnage(this.arme, joueur);
				System.out.println(action + " points de dégat infligé à " + joueur.getNomPersonnage() + ". " + joueur.getNomPersonnage() + ": " + joueur.getPvActuel() + " pv restant");
			}
			else {// Si le random == 2 le Loup_Garou lacère
				action = this.lacerationLoup(joueur);
				System.out.println(action + " points de dégat infligé à " + joueur.getNomPersonnage() + ". " + joueur.getNomPersonnage() + ": " + joueur.getPvActuel() + " pv restant");

			}
			this.compteurTour += 1;
			
		}


}
