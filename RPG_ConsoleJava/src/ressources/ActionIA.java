package ressources;

import personnage.Personnage;

//Cette interface va nous servir � programmer une pseudo IA � chacun de nos monstres
public interface ActionIA {

	public abstract void actionTourMonstre(Personnage monstre, Personnage joueur);
}
