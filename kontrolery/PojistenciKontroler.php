<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Kontroler pro zpracovani pojistencu
 */
class PojistenciKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Seznam pojištěnců',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);

		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		
		//nacteni vsech zaznamu pojistencu
		$pojistenci = $spravcePojistencu->vratVsechnyPojistence();

		// Obsluha tlacitek
		if ($_POST) {
			// tlacitko smazat (pojistence)
			if ($_POST['pojistenci_id_smaz']) {
				//kontrola, zda ma pojistenec prirazen pojistny produkt
				$existujePojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($_POST['pojistenci_id_smaz']));

				//ma-li prirazeny pojistny produkt, nelze smazat, generuje se varovani
				if ($existujePojisteni) {
					//nacteni hodnot pro varovani
					$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($_POST['pojistenci_id_smaz']));
					$varovani = '<div class="alert alert-danger text-center">Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' má přirazený nejméně jeden pojistný produkt. Smažte prosím nejprve toto pojištění!</div>';
				} else {
					//nacteni zaznamu pro zobrazeni ve zprave
					$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($_POST['pojistenci_id_smaz']));
					//nema-li prirazeny produkt, lze pojistence smazat
					$spravcePojistencu->smazPojistence(array($_POST['pojistenci_id_smaz']));
					//ulozeni zpravy do session
					$_SESSION['uspech'] = 'Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' byl smazán z databáze.';
					$this->presmeruj('pojistenci');
				}
			}
			elseif ($_POST['pojistenci_id_edituj']) {
				// obsluha tlacitka editovat
				$this->presmeruj("stavajici-pojistenec/{$_POST['pojistenci_id_edituj']}");
			}
		} else {
			$varovani = '';
		}

		// zpracovani zpravy vygenerovane obsluznou strankou 
		if ($_SESSION) {
			$zprava = '<div class="alert alert-success">' . $_SESSION['uspech'] . '</div>';
			unset($_SESSION['uspech']);
		} else {
			$zprava = '';

		}

		//predani promennych do pohledu
		$this->data['pojistenci'] = $pojistenci;
		$this->data['zprava'] = $zprava;
		$this->data['varovani'] = $varovani;
		$this->pohled = 'pojistenci';
	}
}