Est-il possible de stocker des personnages dans une table de hachage ?

Techniquement oui, on peut y mettre un objet de type personnage. Cependant, l'objet personnage n'est pas un objet immuable,
Et un HashSet/HashMap ne doit contenir que des objet de immuable pour �viter que le hashcode ne change car les table ne hachage ne recalcule pas
les hashcode.

Donc la r�ponse est non, Personnage n'est pas immuable il peu dont � tout moment "cass�" la structure de donn�e.