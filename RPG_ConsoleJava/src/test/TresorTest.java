package test;

import static org.junit.Assert.assertNull;
import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNotEquals;
import static org.junit.jupiter.api.Assertions.fail;

import java.util.HashMap;
import java.util.Map.Entry;

import org.junit.Assert;
import org.junit.jupiter.api.Test;

import equipement.Tresor;


public class TresorTest {
	@Test
	void testObjetNull() {
		Tresor tresorNull = null;

		assertNull(tresorNull);
	}

	// ---------------------------------------------------------------------------------------------
	@Test
	void testAjouterHashCodeOverrideAjoutEquals() {
		HashMap<Tresor, Integer> inventaireTest = new HashMap<>();
		Tresor tresor1 = new Tresor("Test d'un tresor", 15);
		Tresor tresorSame = new Tresor("Test d'un tresor", 15);
		Tresor tresorDiff = new Tresor("Je suis different", 15);

		inventaireTest.put(tresor1, 1);
		inventaireTest.put(tresorSame, 2);
		System.out.println(tresor1.hashCode() + "   " + tresorSame.hashCode());
		for (Entry<Tresor, Integer> entry : inventaireTest.entrySet()) {
			System.out.println(entry.getKey() + " => " + entry.getValue());
		}
		assertEquals(1, inventaireTest.size()); //Verifier que les doublons ne soient pas stocké
		assertEquals(tresor1.hashCode(), tresorSame.hashCode());
		assertNotEquals(tresor1.hashCode(), tresorDiff.hashCode());
	}
	@Test
	void testAjouterHashCodeOverrideAjoutDifferent() {
		HashMap<Tresor, Integer> inventaireTest = new HashMap<>();
		Tresor tresor1 = new Tresor("Test d'un tresor", 15);
		Tresor tresorSame = new Tresor("Test d'un tresor", 15);
		Tresor tresorDiff = new Tresor("Je suis different", 15);

		inventaireTest.put(tresor1, 1);
		inventaireTest.put(tresorDiff, 2);
		for (Entry<Tresor, Integer> entry : inventaireTest.entrySet()) {
			System.out.println(entry.getKey() + " => " + entry.getValue());
		}
		assertEquals(2, inventaireTest.size()); //Verifier que les doublons ne soient pas stocké
		assertEquals(tresor1.hashCode(), tresorSame.hashCode());
		assertNotEquals(tresor1.hashCode(), tresorDiff.hashCode());
	}

	// ---------------------------------------------------------------------------------------------
	@Test
	void testOverrideEquals() {
		Tresor tresor1 = new Tresor("Test", 15);
		Tresor tresorSame = new Tresor("Test", 15);
		Tresor tresorDiff = new Tresor("Je suis different", 15);

		assertEquals(tresor1, tresorSame, ""); //test la methode equals override
		assertNotEquals(tresor1, tresorDiff, ""); // test valeur not equals
		tresor1.equals(tresorSame); 

	}

	// ---------------------------------------------------------------------------------------------
	@Test
	void testContenuDansTable() {
		HashMap<Tresor, Integer> inventaireTest = new HashMap<>();
		Tresor tresor1 = new Tresor("Test", 1);
		inventaireTest.put(tresor1, 1);
		if (!(inventaireTest.containsKey(tresor1))) {
			fail("Non contenu dans la table de hachage");
		}
	}
	// ---------------------------------------------------------------------------------------------
	@Test
	void verifierToString() {
		Tresor tresor1 = new Tresor("Test d'un tresor", 15);
		Tresor tresorSame = new Tresor("Test d'un tresor", 15);
		Tresor tresorDifferent = new Tresor("Test d'un tresor different", 15);

		Assert.assertEquals(tresor1.toString(), tresorSame.toString());
		Assert.assertNotEquals(tresor1.toString(), tresorDifferent.toString());
	}
}
