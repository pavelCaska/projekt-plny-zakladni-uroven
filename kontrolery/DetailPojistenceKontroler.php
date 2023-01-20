<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Kontroler pro zpracovani pojistence a jemu prirazenych pojisteni
 */
class DetailPojistenceKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Detail pojištěnce',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();
		
		// nesmi chybet parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0])) {
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			// Pokud dany parametr neodpovida ID pojistence, přesměrujeme na ChybaKontroler
			if (!$pojistenec)
			$this->presmeruj('chyba');
		} else {
			$this->presmeruj('chyba');
		}
		
		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		// nacteni zaznamu daneho pojistneho produktu
		$pojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($parametry[0]));

		// Obsluha tlacitek
		if ($_POST) {
			// zpracovani tlacitka smazat pojistence, ktere se nachazi pod tabulkou
			if ($_POST['pojistenci_id_smaz']) {
				//kontrola, zda ma pojistenec prirazen pojistny produkt
				$existujePojisteni = $spravcePojisteni->vratVsechnaPojisteni(array($_POST['pojistenci_id_smaz']));
				
				//ma-li prirazeny pojistny produkt, nelze smazat, generuje se varovani
				if ($existujePojisteni) {
					$varovani = '<div class="alert alert-danger text-center">Pojištěnec má přirazený nejméně jeden pojistný produkt. Smažte prosím nejprve toto pojištění!</div>';
				} else {
					//nema-li prirazeny produkt, lze pojistence smazat
					$spravcePojistencu->smazPojistence(array($_POST['pojistenci_id_smaz']));
					//ulozeni zpravy do session
					$_SESSION['uspech'] = 'Pojištěnec ' . $pojistenec['jmeno'] . ' ' . $pojistenec['prijmeni'] . ' byl smazán z databáze.';
					$this->presmeruj('pojistenci');
				}
			}
			//zpracovani tlacitka edituj v seznamu pojisteni
			elseif ($_POST['pojisteni_id_edituj']) {
				
				$this->presmeruj("editovat-pojisteni/$parametry[0]/{$_POST['pojisteni_id_edituj']}");
			}
			//zpracovani tlacitka smazat v seznamu pojisteni
			elseif ($_POST['pojisteni_id_smaz']) {
				//nacteni zaznamu pro zobrazeni ve zprave
				$produkt = $spravcePojisteni->vratJedenProdukt(array($_POST['pojisteni_id_smaz']));
				//smazani vybraneho pojistneho produktu
				$spravcePojisteni->smazPojisteni(array($_POST['pojisteni_id_smaz']));
				//ulozeni zpravy do session
				$_SESSION['uspech'] = 'Produkt ' . $produkt['poj_produkt'] . ' byl smazán z databáze.';
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
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
		$this->data['pojistenec'] = $pojistenec;
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['pojisteni'] = $pojisteni;
		$this->data['zprava'] = $zprava;
		$this->data['varovani'] = $varovani;
		$this->pohled = 'detail-pojistence';
	}
}