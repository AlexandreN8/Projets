package equipement;

public class Tresor {

	// VARIABLE 
	// IMMUABLE
	private final String description;
	private final int nbOr;

	// CONSTRUCTEUR
	public Tresor(String description, int nbOr) {
		this.description = description;
		this.nbOr = nbOr;
	}

	// GETTERS
	public String getDescription() {
		return description;
	}

	public int getNbOr() {
		return nbOr;
	}

	// On a besoin de réécrire equals et hashCode pour les valeurs de notre
	// inventaire de trésors (HashMap<Trésors, quantité>
	@Override
	public boolean equals(Object obj) {
		if (!(obj instanceof Tresor || obj == null))
			return false;

		Tresor tresorCast = (Tresor) obj;

		return this.description == tresorCast.description && this.nbOr == tresorCast.nbOr;
	}

	@Override
	public int hashCode() {
		return this.description.hashCode() + this.nbOr;

	}

	// METHODE
	@Override
	public String toString() {
		return "Descriptif : " + this.getDescription() + ". Prix : " + this.getNbOr();
	}

}
