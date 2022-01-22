package monstre;

import equipement.Arme;
import equipement.Tresor;
import personnage.Personnage;

public class Slime extends Monstre{
	
	//CONSTRUCTEUR
	public Slime(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
		
		this.typeMonstre = "Neutre";

		
		//Loot monstre
		this.inventaireTresor.put(new Tresor("Gelée", 10), 1);
	}

	
	@Override
	public int attaquerPersonnage(Arme arme, Personnage cible) {
		int degatInflige = 1;
		cible.setPvActuel(cible.getPvActuel() - degatInflige); 
		return degatInflige;
	}

	@Override
	public int soignerPersonnage(Personnage cible) {
		// Ne se soigne pas
		return 0;
	}
	
	public void fuiteMonstre() {
		int temp = (Math.random() <= 0.2) ? 1 : 2; //On détermine probabilité de fuite de 0.2

		if(temp == 1) {
			this.setFuite(true);
			System.out.println(this.getNomPersonnage() + " fuit le combat.");
		}
		else {
			System.out.println(this.getNomPersonnage() + " tente de fuire le combat... \nMais échoue.");
		}

	}

	@Override
	public void actionTourMonstre(Personnage monstre, Personnage joueur) {
		int action = 0;
		if(this.getPvActuel() == 1) {
			this.fuiteMonstre();
		}
		else
			action = this.attaquerPersonnage(this.arme, joueur);
			System.out.println(action + " points de dégat infligé à " + joueur.getNomPersonnage() + ". " + joueur.getNomPersonnage() + ": " + joueur.getPvActuel() + " pv restant");
	}
}
