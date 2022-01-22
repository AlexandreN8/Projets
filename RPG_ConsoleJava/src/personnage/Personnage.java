package personnage;

import java.util.HashMap;
import java.util.Map.Entry;

import equipement.Arme;
import equipement.Tresor;

public abstract class Personnage{

	// VARIABLE
	protected final String nomPersonnage;
	protected int pvMax; // On ne le met pas final en admettant que le personnage puisse progresser
	protected int pvActuel;
	protected int bourse; // Cette variable nous permet de vendre des trésors et acheter des armes
	protected boolean mort, fuite;

	protected Arme arme;
	// J'ai choisis une hashMap et non un hashSet car on veux ajouter plusieurs
	// valeurs identiques pour
	// les revendres au magasin
	protected HashMap<Tresor, Integer> inventaireTresor;//<Tresor, quantites>

	// CONSTRUCTEUR
	protected Personnage(String nomPersonnage, int pvMax) {
		this.nomPersonnage = nomPersonnage;
		this.pvMax = pvMax;
		this.pvActuel = pvMax;
		this.bourse = 0; //On rajoute cet attribut pour le magasin

		// 2 boolean necessaire à la gestion des combats
		this.fuite = false; 
		this.mort = false;
		
		this.arme = null;
		this.inventaireTresor = new HashMap<>();
		
	}

	// GETTERS AND SETTERS
	public String getNomPersonnage() {
		return nomPersonnage;
	}

	public int getBourse() {
		return bourse;
	}

	public void setBourse(int bourse) {
		this.bourse = bourse;
	}

	public int getPvActuel() {
		return pvActuel;
	}

	public void setPvActuel(int pvActuel) {
		this.pvActuel = pvActuel;
	}

	// Permet de definir la mort d'un personnage
	public boolean isDead() {
		return this.pvActuel <= 0;
	}

	public int getPvMax() {
		return pvMax;
	}

	public boolean isFuite() {
		return fuite;
	}

	public void setFuite(boolean fuite) {
		this.fuite = fuite;
	}

	public Arme getArme() {
		return arme;
	}

	public void equiperArme(Arme arme) {
		this.arme = arme;
	}

	public HashMap<Tresor, Integer> getInventaireTresor() {
		return inventaireTresor;
	}

	public void prendreTresor(Tresor unTresor) {
		boolean trouve = false;
		for (Entry<Tresor, Integer> entry : this.inventaireTresor.entrySet()) {
			if (entry.getKey().equals(unTresor)) {// On vérifie que la HashMap ne posséde pas déjà ce trésor en tant que
													// clé, si il est déja présent on ajoute +1 a sa valeurs qui
													// représente sa
													// quantitée
				int valeurKey = entry.getValue();
				entry.setValue(valeurKey + 1);
				trouve = true;
			}
		}
		if (!trouve) {
			this.inventaireTresor.put(unTresor, 1);// sinon on ajoute le trésor à la hashmap
		}
	}

	// METHODE
	public abstract int attaquerPersonnage(Arme arme, Personnage cible);
	public abstract int soignerPersonnage(Personnage cible); 
	
	@Override
	public boolean equals(Object obj) {
		if (!(obj instanceof Personnage || obj == null))
			return false;
        if(this instanceof Guerrier && obj instanceof Clerc) {
            return false;
        }
        if(this instanceof Clerc && obj instanceof Guerrier) {
            return false;
        }
		Personnage persoCast = (Personnage) obj;


		return this.nomPersonnage == persoCast.getNomPersonnage() && this.pvMax == persoCast.getPvMax() 
				&& this.bourse == persoCast.getBourse() && this.arme == this.getArme()
				&& this.pvActuel == persoCast.getPvActuel() 
				&& this.getInventaireTresor().keySet().equals(persoCast.getInventaireTresor().keySet());
	}
	
	//L'objet n'est pas immuable mais vu qu'on a modifier le equals on va aussi modifier le hashcode
	@Override
	public int hashCode() {
		int armeCode;
		if(this.getArme() == null) {
			armeCode = 0;
		}else {
			armeCode = this.getArme().hashCode();
		}
		return this.nomPersonnage.hashCode() + this.pvMax + this.bourse + armeCode + this.pvActuel + this.getInventaireTresor().keySet().hashCode();
	}
}
