<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Kontroler pro vytvoreni zaznamu noveho pojistneho produktu
 */
class PridatPojisteniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Přidat pojištění',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();

		// nesmi chybet parametr URL (ID pojistence, kteremu se pojisteni prirazuje), resp. musi to byt cislo
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

		// Je odeslán formulář
		if ($_POST) {
			// kontrola, zda jsou vyplneny vsechny inputy
			if ($_POST['poj_produkt'] == '' || $_POST['poj_castka'] == '' || $_POST['predmet_poj'] == '' || $_POST['platnost_od'] == '' || $_POST['platnost_do'] == '') {
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím ÚPLNOST zadávaných údajů</div>';
			} elseif (
			// jednoducha validace inputu
				!filter_var($_POST['poj_castka'], FILTER_VALIDATE_INT) || $_POST['poj_castka'] < 0 ||
				!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['predmet_poj']) ||
			// oba datumy zohlednuji pravopisne spravny (1. 1. 2020) i nespravny format datumu (pouzity v zadani) 
				!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_od']) ||
				!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_do'])
				) 
			{
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím SPRÁVNOST zadávaných údajů</div>';

			} else {
				//nastaveni pole pro intersect
				$pojisteni = array (
					'poj_produkt' => $_POST['poj_produkt'],
					'poj_castka' => $_POST['poj_castka'],
					'predmet_poj' => $_POST['predmet_poj'],
					'platnost_od' => FormatHelper::datumDoDB($_POST['platnost_od']),
					'platnost_do' => FormatHelper::datumDoDB($_POST['platnost_do']),
					'pojistenec_id' => $parametry[0],
				);
				// vlozeni noveho zaznamu do databaze
				$spravcePojisteni->vlozNovePojisteni($pojisteni);
				// ulozeni zpravy do session
				$_SESSION['uspech'] = 'Nový pojistný produkt ' . $_POST['poj_produkt'] . ' byl uložen.';
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
			}
		} else {
			$zprava = '';
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['zprava'] = $zprava;
		$this->pohled = 'pridat-pojisteni';
	}
}