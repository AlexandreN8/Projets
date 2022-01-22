package test;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNull;
import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNotEquals;
import static org.junit.jupiter.api.Assertions.fail;

import java.util.stream.Stream.Builder;
import java.util.HashMap;
import java.util.Random;
import java.util.Map.Entry;
import java.util.stream.Stream;

import org.junit.Assert;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.params.ParameterizedTest;
import org.junit.jupiter.params.provider.Arguments;
import org.junit.jupiter.params.provider.MethodSource;

import equipement.Arme;

public class ArmeTest {
	Arme armeTest = new Arme("Test", 12, 15);
	Arme armeTest2 = new Arme("Test", 12, 15);
	Arme armeTestDifferente = new Arme("Different", 12, 56);
	
	@Test
	void testObjetNull() {
		Arme armeTestNull = null;

		assertNull(armeTestNull);
	}

	// ---------------------------------------------------------------------------------------------
	@Test
	void testAjouterHashCodeOverrideAjoutEquals() {
		HashMap<Arme, Integer> inventaireTest = new HashMap<>();

		inventaireTest.put(armeTest, 1);
		inventaireTest.put(armeTest2, 2);
		for (Entry<Arme, Integer> entry : inventaireTest.entrySet()) {
			System.out.println(entry.getKey() + " => " + entry.getValue());
		}
		assertEquals(1, inventaireTest.size()); //Verifier que les doublons ne soient pas stocké
		assertEquals(armeTest.hashCode(), armeTest2.hashCode());
		assertNotEquals(armeTest.hashCode(), armeTestDifferente.hashCode());
	}
	@Test
	void testAjouterHashCodeOverrideAjoutDifferents() {
		HashMap<Arme, Integer> inventaireTest = new HashMap<>();

		inventaireTest.put(armeTest, 1);
		inventaireTest.put(armeTestDifferente, 1);
		System.out.println(armeTest.hashCode() + "   " + armeTestDifferente.hashCode());
		for (Entry<Arme, Integer> entry : inventaireTest.entrySet()) {
			System.out.println(entry.getKey() + " => " + entry.getValue());
		}
		assertEquals(2, inventaireTest.size()); //Verifier que la map stock les hashcode differents
		assertEquals(armeTest.hashCode(), armeTest2.hashCode());
		assertNotEquals(armeTest.hashCode(), armeTestDifferente.hashCode());
	}

	// ---------------------------------------------------------------------------------------------

	@Test
	void testOverrideEquals() {	
		assertEquals(armeTest, armeTest2, ""); //test la methode equals override
		assertNotEquals(armeTestDifferente, armeTest, ""); // test valeur not equals
		armeTest.equals(armeTest2); 
	}

	// ---------------------------------------------------------------------------------------------
	@Test
	void testContenuDansTable() {
		HashMap<Integer, Arme> inventaireTest = new HashMap<>();
		Arme armeTest = new Arme("Test", 12, 15);
		inventaireTest.put(1, armeTest);
		if (!(inventaireTest.containsValue(armeTest))) {
			fail("Non contenu dans la table de hachage");
		}
	}

	// ---------------------------------------------------------------------------------------------
	@ParameterizedTest
	@MethodSource(value = "degatRand")
	void testDegatRandomSurRangeLarge(Integer rangeTest) {
		Integer min = 50;
		Integer max = 75;
		// A partir du stream aleatoire on va vérifier que les dégats soit toujours
		// compris entre le minimum et maximum de degat
		if (!(rangeTest >= min && rangeTest <= max)) {
			fail("Le nombre aléatoire n'est pas contenu dans la plage de dégat : " + rangeTest);
		}
	}

	// On va generer un stream d'Integer aleatoire
	static Stream<Arguments> degatRand() {
		Builder<Arguments> sb = Stream.builder();
		Random rand = new Random();
		Integer min = 50;
		Integer max = 75;

		for (int i = 1; i <= 10000; ++i)
			sb.add(Arguments.of(rand.nextInt(max - min + 1) + min)); // Genere des valeurs comprises entres degat min et max

		return sb.build();
	}

	// ---------------------------------------------------------------------------------------------
	@ParameterizedTest
	@MethodSource(value = "degatRandFixe")
	void testDegatRandomSurCloseRange(Integer rangeTest) {
		Integer min = 50;
		Integer max = 50;
		// Même teste qu'au dessus à ceci près qu'on admet cette fois que l'arme a des
		// dégats fixes
		if (!(rangeTest >= min && rangeTest <= max)) {
			fail("Le nombre aléatoire n'est pas contenu dans la plage de dégat : " + rangeTest);
		}
	}

	// On va generer un stream d'Integer aleatoire
	static Stream<Arguments> degatRandFixe() {
		Builder<Arguments> sb = Stream.builder();
		Random rand = new Random();
		Integer min = 50;
		Integer max = 50;

		for (int i = 1; i <= 100; ++i)
			sb.add(Arguments.of(rand.nextInt(max - min + 1) + min));

		return sb.build();
	}
	// ---------------------------------------------------------------------------------------------
	@Test
	void verifierToString() {
		Assert.assertEquals(armeTest.toString(), armeTest2.toString());
		Assert.assertNotEquals(armeTest.toString(), armeTestDifferente.toString());
	}
}
