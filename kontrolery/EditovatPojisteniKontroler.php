<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Kontroler pro editaci stavajiciho pojisteneho produktu - pouze parametry, nikoli druh pojisteni
 */
class EditovatPojisteniKontroler extends Kontroler
{
	public function zpracuj(array $parametry): void
	{
		$this->hlavicka = array(
			'titulek' => 'Editovat pojištěni',
			'klicova_slova' => 'závěrečný projekt, PHP, programátor, webové aplikace',
			'popis' => 'Evidence pojištění - Plná verze - minimální požadavky ke splnění'
		);
		
		// Vytvoření instance modelu
		$spravcePojistencu = new SpravcePojistencu();
		$spravcePojisteni = new SpravcePojisteni();

		// nesmi chybet prvni ani druhy parametr URL, resp. musi to byt cislo
		if (!empty($parametry[0]) && is_numeric($parametry[0]) || !empty($parametry[1]) && is_numeric($parametry[1])) {
			$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
			$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));
			// kontrola, zda jsou ID pojistence a ID pojisteni validni, jinak přesměrujeme na ChybaKontroler
			if (!$pojistenec || !$produkt)
			$this->presmeruj('chyba');
		} else {
			$this->presmeruj('chyba');
		}
		
		// nacteni zaznamu daneho pojistence
		$pojistenec = $spravcePojistencu->vratJednohoPojistence(array($parametry[0]));
		// nacteni zaznamu daneho pojistneho produktu
		$produkt = $spravcePojisteni->vratJedenProdukt(array($parametry[1]));

		// Je odeslán formulář
		if ($_POST) {
			// kontrola, zda jsou vyplneny vsechny inputy
			if ($_POST['poj_castka'] == '' || $_POST['predmet_poj'] == '' || $_POST['platnost_od'] == '' || $_POST['platnost_do'] == '') {
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím ÚPLNOST zadávaných údajů</div>';
			} elseif (
				!filter_var($_POST['poj_castka'], FILTER_VALIDATE_INT) || $_POST['poj_castka'] < 0 ||
				!preg_match('/^[\p{L}\s|\.\s]+[0-9]|[\p{L}]+$/u', $_POST['predmet_poj']) ||
			// oba datumy zohlednuji pravopisne spravny (1. 1. 2020) i nespravny format datumu (pouzity v zadani)
				!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_od']) ||
				!preg_match('/(^\d{1,2}\.\d{1,2}\.\d{4}$)|(^\d{1,2}\.\s\d{1,2}\.\s\d{4}$)/', $_POST['platnost_do'])
			) {
				$zprava = '<div class="alert alert-danger text-center">Zkontrolujte prosím SPRÁVNOST zadávaných údajů</div>';
			} else {
				// nastaveni pole pro intersect
				$pojisteni = array(
					'poj_castka' => $_POST['poj_castka'],
					'predmet_poj' => $_POST['predmet_poj'],
					'platnost_od' => FormatHelper::datumDoDB($_POST['platnost_od']),
					'platnost_do' => FormatHelper::datumDoDB($_POST['platnost_do']),
				);
				// ulozeni produktu do databaze
				$spravcePojisteni->aktualizujPojisteni($pojisteni, array($parametry[1]));
				//ulozeni zpravy do session
				$_SESSION['uspech'] = 'Produkt ' . $produkt['poj_produkt'] . ' byl aktualizován.';
				$this->presmeruj("detail-pojistence/{$parametry[0]}");
			}
		} else {
			$zprava = '';
		}

		//predani promennych do pohledu
		$this->data['pojistenec'] = $pojistenec;
		$this->data['id_pojistence'] = $parametry[0];
		$this->data['id_pojisteni'] = $parametry[1];
		$this->data['produkt'] = $produkt;
		$this->data['zprava'] = $zprava;
		$this->pohled = 'editovat-pojisteni';
	}
}