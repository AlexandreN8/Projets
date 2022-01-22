package ressources;

import personnage.Personnage;

//Cette interface va nous servir à programmer une pseudo IA à chacun de nos monstres
public interface ActionIA {

	public abstract void actionTourMonstre(Personnage monstre, Personnage joueur);
}
