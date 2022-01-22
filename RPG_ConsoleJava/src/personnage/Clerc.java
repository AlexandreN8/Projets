package personnage;

import equipement.Arme;
import ressources.Constante;

public class Clerc extends Personnage {

	public Clerc(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
	}

	@Override
	// On le met en public pour permettre à la factory de le voir
	public int attaquerPersonnage(Arme arme, Personnage cible) {
		if (this.arme == null) {
			cible.pvActuel -= Constante.MIN_DEGAT_CLERC.getLaConst(); //On s'assure que les degat min soient toujours de 1
			return Constante.MIN_DEGAT_CLERC.getLaConst();
		} else {
			int degatInflige = arme.degat() - Constante.PROPRIETE_DEGAT_CLASSE.getLaConst(); // Le clerc applique 1 de degat en moins
			if (degatInflige == 0) { //Si les degat - les 1 du clerc valent 0 on reajuste à 1
				degatInflige = Constante.MIN_DEGAT_CLERC.getLaConst();
			}
			cible.pvActuel -= degatInflige;
			return degatInflige;
		}
	}

	@Override
	public int soignerPersonnage(Personnage cible) {
		if ((cible.pvActuel + Constante.SOIN_CLERC.getLaConst()) <= cible.pvMax) {
			cible.pvActuel += Constante.SOIN_CLERC.getLaConst();
			return Constante.SOIN_CLERC.getLaConst();
		} else {
			// On pourrais donné directement les pv max mais on veux retourner la valeur des
			// pv soignés pour l'afficher
			int pvSoigne = cible.getPvMax() - cible.getPvActuel();
			cible.pvActuel += pvSoigne;
			return pvSoigne;
		}
	}

	public String toString() {
		return "Clerc " + this.nomPersonnage + ", pv max: " + this.pvMax + " et pv actuel: " + this.pvActuel;
	}
}
