package personnage;

import equipement.Arme;
import ressources.Constante;

public class Guerrier extends Personnage {

	public Guerrier(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
	}

	@Override
	// On le met en public pour permettre à la factory de le voir
	public int attaquerPersonnage(Arme arme, Personnage cible) {
		if (this.arme == null) {
			cible.pvActuel -= (1 + Constante.PROPRIETE_DEGAT_CLASSE.getLaConst());//On ajoute le bonus de +1 degat à notre guerrier
			return 2;
		} else {
			int degatInflige = arme.degat() + Constante.PROPRIETE_DEGAT_CLASSE.getLaConst();//On ajoute le bonus de +1 degat à notre guerrier
			cible.pvActuel -= degatInflige;
			return degatInflige;
		}
	}

	@Override
	public int soignerPersonnage(Personnage cible) {
		System.out.println("\nVos pouvoir curatif sont faibles.");
		return 0; // Le guerrier ne soigne pas
	}

	@Override
	public String toString() {
		return "Guerrier " + this.nomPersonnage + ", pv max: " + this.pvMax + " et pv actuel: " + this.pvActuel;

	}

}
