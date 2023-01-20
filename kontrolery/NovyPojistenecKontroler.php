<?php

/* 
 * Více informací na http://www.itnetwork.cz/licence
 */

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistence
 */
class NovyPojistenecKontroler extends Kontroler
{
	
    public function zpracuj(array $parametry) : void
    {
		$this->hlavicka = array(
			'titulek' => 'Nový pojištěnec',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		// Příprava prázdného pojištěnce
		$pojistenec = array(
			'jmeno' => '',
			'prijmeni' => '',
			'email' => '',
			'telefon' => '',
			'ulice_cp' => '',
			'mesto' => '',
			'psc' => '',
		);
		// Je odeslán formulář
		if ($_POST) {
			// kontrola, zda jsou vyplneny vsechny inputy
			if ($_POST['jmeno'] == '' || $_POST['prijmeni'] == '' || $_POST['email'] == '' || $_POST['telefon'] == '' || $_POST['ulice_cp'] == '' || $_POST['mesto'] == '' || $_POST['psc'] == '') {
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím ) ÚPLNOST zadávaných údajů</div>';
			} elseif (
			// jednoducha validace inputu
				!preg_match('/^[\p{L}-]+$/u', $_POST['jmeno']) ||
				!preg_match('/^[\p{L}-]+$/u', $_POST['prijmeni']) ||
				!preg_match('/\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6}/', $_POST['email']) ||
				!preg_match('/^(\d{3}\s){2}\d{3}|\d{3}\s+$/', $_POST['telefon']) ||
				!preg_match('/^[\p{L}\s|\.\s]+[0-9\/0-9|\w]+$/u', $_POST['ulice_cp']) ||
				!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['mesto']) ||
				!preg_match('/^\d{3}\s\d{2}$/', $_POST['psc'])
			) {
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím SPRÁVNOST zadávaných údajů</div>';
			} else {
			// nastaveni relevantnich klicu
			$klice = array('jmeno', 'prijmeni', 'email', 'telefon', 'ulice_cp', 'mesto', 'psc');
			// z globalni promenne se vyberou relevantni hodnoty podle nastavenych klicu
			$pojistenec = array_intersect_key($_POST, array_flip($klice));
			// Uložení pojištěnce do DB
			$spravcePojistencu->vlozNovehoPojistence($_POST, $pojistenec);
			// ulozeni zpravy do session
			$_SESSION['uspech'] = 'Pojištěnec ' . $_POST['jmeno'] . ' ' . $_POST['prijmeni'] . ' byl uložen.';
			$this->presmeruj('pojistenci');
			}
		} else {
			$zprava = '';
		}
					
		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['zprava'] = $zprava;
		$this->pohled = 'novy-pojistenec';
    }
}