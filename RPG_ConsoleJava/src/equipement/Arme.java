package equipement;

import java.util.Random;

public class Arme {

	// VARIABLE
	// IMMUABLE
	private final String nomArme;
	private final int pdMin, pdMax;

	// CONSTRUCTEUR
	public Arme(String nom, int pdMin, int pdMax) {
		this.nomArme = nom;
		this.pdMin = pdMin;
		this.pdMax = pdMax;
	}

	// GETTERS
	public int getPdMin() {
		return pdMin;
	}

	public int getPdMax() {
		return pdMax;
	}

	public String getNomArme() {
		return nomArme;
	}

	// METHODE
	public int degat() {
		Random rand = new Random();

		return rand.nextInt(this.getPdMax() - this.getPdMin() + 1) + this.getPdMin(); // +1 car on veux inclure la
																						// valeur maximale
	}

	// Réécrire les méthodes equals et hashcode pour stocker nos armes dans la
	// linkedHashMap du magasin
	@Override
	public boolean equals(Object obj) {
		if (!(obj instanceof Arme || obj == null))
			return false;

		Arme armeCast = (Arme) obj;

		return this.nomArme == armeCast.nomArme && this.pdMin == armeCast.pdMin && this.pdMax == armeCast.pdMax;
	}

	@Override
	public int hashCode() {
		return this.nomArme.hashCode() + this.pdMax + this.pdMin;

	}

	@Override
	public String toString() {
		return "L'épée " + this.getNomArme() + ", fait entre " + this.getPdMin() + " et " + this.getPdMax()
				+ " points de dégat.";
	}

}
