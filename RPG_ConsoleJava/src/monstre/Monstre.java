package monstre;

import equipement.Arme;
import personnage.Personnage;
import ressources.ActionIA;

//Correspond à notre adversaire IA
//Un monstre est un personnage mais il implémente une interface d'action permettant de programmer la pseudo IA
public abstract class Monstre extends Personnage implements ActionIA{

	//VARIABLE
	protected String typeMonstre;
	
	//CONSTRUCTEUR
	protected Monstre(String nomPersonnage, int pvMax) {
		super(nomPersonnage, pvMax);
	}

	@Override
	public abstract int attaquerPersonnage(Arme arme, Personnage cible);

	@Override
	public abstract int soignerPersonnage(Personnage cible);

}
