package ressources;

//Cette classe n'est pas demand�e mais elle permet de gagner en clart� 
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
