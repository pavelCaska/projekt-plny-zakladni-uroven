<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Třída poskytuje metody pro praci s tabulkou `pojisteni`
 */
class SpravcePojisteni
{
	
	// funkce vlozi zaznam noveho pojisteneho produktu do tabulky
	public function vlozNovePojisteni (array $pojisteni) : bool
	{
		return Db::vloz('pojisteni', $pojisteni);
	}

	// funkce zmeni zaznam daneho pojistneho produktu dle jeho ID
	public function aktualizujPojisteni (array $pojisteni, array $id) : bool
	{
		return Db::zmen('pojisteni', $pojisteni, 'WHERE pojisteni_id = ?', $id);
	}
	
	// funkce vymaze zaznam daneho pojistneho produktu dle jeho ID
	public function smazPojisteni(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM pojisteni
			WHERE pojisteni_id = ?', 
			$id);
	}
	
	// funkce smaze vsechny pojistne produkty prirazene danemu pojistenci dle jeho ID
	// Nakonec jsem tuto funkci nevyuzil, resp. jsem vytvoril podminku, podle niz nelze smazat pojistence, dokud ma prirazeny pojistny produkt
	public function smazVsechnaPojisteni( array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM pojisteni
			WHERE pojistenec_id = ?', 
			$id);
	}

	// funkce vrati pole zaznamu vsech pojistnych produktu prirazenych danemu pojistenci dle jeho ID, nebo false
	public function vratVsechnaPojisteni(array $id) : array|bool
	{
		return Db::dotazVsechny('
			SELECT *
			FROM pojisteni
			WHERE pojistenec_id = ?
			ORDER BY poj_produkt',
			$id);	
	}
	
	// funkce vrati pole se zaznamem pojistneho produktu dle jeho ID
	public function vratJedenProdukt(array $id) : array|bool
	{
		return Db::dotazJeden('
			SELECT *
			FROM pojisteni
			WHERE pojisteni_id = ?',
			$id);	
	}
}