package ressources;

//Cette classe n'est pas demandée mais elle permet de gagner en clarté 
public enum Constante {
	
	//Constantes de personnage jouable
	PROPRIETE_DEGAT_CLASSE(1), MIN_DEGAT_CLERC(1), SOIN_CLERC(4);
	
	
	private int laConst;
	
	Constante(int d){
		laConst = d;
	}
	
	public int getLaConst() {
		return laConst;
	}

	
}
