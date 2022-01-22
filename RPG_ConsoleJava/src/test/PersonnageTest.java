package test;

import static org.junit.Assert.assertEquals;
import static org.junit.jupiter.api.Assertions.fail;

import java.util.stream.Stream;
import java.util.stream.Stream.Builder;

import org.junit.Assert;
import org.junit.Test;
import org.junit.jupiter.params.ParameterizedTest;
import org.junit.jupiter.params.provider.MethodSource;

import equipement.Arme;
import equipement.Tresor;
import personnage.Clerc;
import personnage.Guerrier;
import personnage.Personnage;

public class PersonnageTest {
	// VARIABLE DE TEST
	static Personnage guerrierTest = new Guerrier("Test", 60);
	static Personnage guerrierTestSame = new Guerrier("Test", 60);
	static Personnage guerrierTestDiff = new Guerrier("Test diff", 60);
	static Personnage clercTest = new Clerc("Test", 50);

// ---------------------------------------------------------------------------------------------
	// Test du soin du guerrier
	@Test
	public void soinAZeroGuerrierSurSoi() {
		guerrierTest.setPvActuel(48);

		guerrierTest.soignerPersonnage(guerrierTest);

		assertEquals(48, guerrierTest.getPvActuel()); // rien ne change
	}

	@Test
	public void soinAZeroGuerrierSurAdversaire() {
		clercTest.setPvActuel(30);

		guerrierTest.soignerPersonnage(clercTest);

		assertEquals(30, clercTest.getPvActuel()); // rien ne change
	}

// ---------------------------------------------------------------------------------------------
	// Test de soin pour le clerc avec les diférentes valeurs possible - Sur lui
	// même
	@Test
	public void soinClercLuiMeme() {
		clercTest.setPvActuel(20);

		clercTest.soignerPersonnage(clercTest);
		assertEquals(24, clercTest.getPvActuel()); // Si les pvActuel + soin son inferieur à pvMax
	}

	@Test
	public void soinClercLuiMemeQuandPvPresqueMax() {
		clercTest.setPvActuel(48);

		clercTest.soignerPersonnage(clercTest);
		assertEquals(50, clercTest.getPvActuel()); // Si les pvActuel + soins sont > à pvMax
	}

	@Test
	public void soinClercLuiMemeQuandPvMax() {
		clercTest.setPvActuel(50);

		clercTest.soignerPersonnage(clercTest);
		assertEquals(50, clercTest.getPvActuel()); // Si les pvActuel = pvMax
	}

// ---------------------------------------------------------------------------------------------
	// Test de soin pour le clerc avec les diférentes valeurs possible - Sur
	// l'adversaire
	@Test
	public void soinClercCible() {
		guerrierTest.setPvActuel(20);

		clercTest.soignerPersonnage(guerrierTest);
		assertEquals(24, guerrierTest.getPvActuel()); // Si les pvActuel + soin son inferieur à pvMax
	}

	@Test
	public void soinClercCibleQuandPvPresqueMax() {
		guerrierTest.setPvActuel(58);

		clercTest.soignerPersonnage(guerrierTest);
		assertEquals(60, guerrierTest.getPvActuel()); // Si les pvActuel + soins sont > à pvMax
	}

	@Test
	public void soinClercCibleQuandPvMax() {
		guerrierTest.setPvActuel(guerrierTest.getPvMax());

		clercTest.soignerPersonnage(guerrierTest);
		assertEquals(60, guerrierTest.getPvActuel()); // Si les pvActuel = pvMax
	}

// ---------------------------------------------------------------------------------------------
	// On test la plage de dégat du guerrier, on veux donc que le dégat soit le
	// minimum +1 et le maximum +1
	@ParameterizedTest
	@MethodSource(value = "degatRandGuerrier")
	public void testDegatMaxEtMinGuerrierAvecArme(int valAttaque) {

		if (!(valAttaque >= 26) && !(valAttaque < 52)) { // degats arme + 1 du guerrier
			fail("La valeur d'attaque n'est pas correct.");
		}
	}

	// On va generer un stream d'Integer aleatoire
	static Stream<Integer> degatRandGuerrier() {
		Builder<Integer> sb = Stream.builder();
		guerrierTest.equiperArme(new Arme("Arme test", 25, 50));

		for (int i = 1; i <= 1000; ++i)
			sb.add(guerrierTest.attaquerPersonnage(guerrierTest.getArme(), new Guerrier("TestPrendsDegats", 60))); // On
																													// utilise
																													// la
																													// methode
																													// attaquer
																													// du
																													// Guerrier

		return sb.build();
	}

// ---------------------------------------------------------------------------------------------
	// Même chose que pour le clerc avec ses -1 de dégat
	@ParameterizedTest
	@MethodSource(value = "degatRandClerc")
	public void testDegatMaxEtMinClercAvecArme(int valAttaque) {

		if (!(valAttaque >= 24) && !(valAttaque < 50)) { // degat arme -1 du clerc
			fail("La valeur d'attaque n'est pas correct.");
		}
	}

	// On va generer un stream d'Integer aleatoire
	static Stream<Integer> degatRandClerc() {
		Builder<Integer> sb = Stream.builder();
		clercTest.equiperArme(new Arme("Arme test", 25, 50));

		for (int i = 1; i <= 1000; ++i)
			sb.add(clercTest.attaquerPersonnage(clercTest.getArme(), new Guerrier("TestPrendsDegats", 60))); // On
																												// utilise
																												// la
																												// methode
																												// attaquer
																												// du
																												// Clerc

		return sb.build();
	}
	
// ---------------------------------------------------------------------------------------------
	@Test
	public void verifierToString() {

		Assert.assertEquals(guerrierTest.toString(), guerrierTest.toString());
		Assert.assertNotEquals(guerrierTest.toString(), clercTest.toString());
	}

// ---------------------------------------------------------------------------------------------
		@Test
		public void verifierEquals() {

			guerrierTest.equiperArme(new Arme("Excalibur", 500, 600));
			guerrierTestSame.equiperArme(new Arme("Excalibur", 500, 600));
			guerrierTest.setPvActuel(guerrierTest.getPvMax());
			
			assertEquals(guerrierTest, guerrierTestSame); //Identique
			guerrierTestSame.setPvActuel(20);
			Assert.assertNotEquals(guerrierTest, guerrierTestSame); // Pv actuel differents
			
			guerrierTestSame.prendreTresor(new Tresor("Test", 25));
			guerrierTestSame.setPvActuel(guerrierTest.getPvActuel());	
			Assert.assertNotEquals(guerrierTest, guerrierTestSame); //Inventaire different

			Assert.assertNotEquals(guerrierTest, clercTest); // Guerrier different de clerc
			Assert.assertNotEquals(guerrierTest, guerrierTestDiff);

		}
		
// ---------------------------------------------------------------------------------------------
				@Test
				public void verifierHashCode() {
					
					guerrierTestSame.getInventaireTresor().clear();
					
					assertEquals(guerrierTest.hashCode(), guerrierTestSame.hashCode());
					
					guerrierTestSame.equiperArme(null);
					Assert.assertNotEquals(guerrierTest.hashCode(), guerrierTestSame.hashCode());

					Assert.assertNotEquals(guerrierTest.hashCode(), guerrierTestDiff.hashCode());


				}
}
