<?php

/*
 * odkaz na http://www.itnetwork.cz
 */

/**
 * Třída poskytuje metody pro praci s tabulkou `pojistenci`
 */
class SpravcePojistencu
{
	
	// funkce vlozi zaznam noveho pojistence do tabulky
	public function vlozNovehoPojistence (array $pojistenec) : bool 
	{
		return Db::vloz('pojistenci', $pojistenec);
	}

	// funkce zmeni zaznam stavajiciho pojistence podle jeho ID
	public function aktualizujPojistence (array $pojistenec, array $id) : bool
	{
		return Db::zmen('pojistenci', $pojistenec, 'WHERE pojistenci_id = ?', $id);
	}
	
	// funkce vymaze pojistence podle jeho ID 
	public function smazPojistence(array $id) : bool
	{
		return Db::dotaz('
			DELETE 
			FROM pojistenci
			WHERE pojistenci_id = ?', 
			$id);
	}

	// funkce vrati zaznam pojistence dle ID jako pole, nebo false
	public function vratJednohoPojistence (array $id) : array|bool
	{
		return Db::dotazJeden('
			SELECT *
			FROM pojistenci
			WHERE pojistenci_id = ?',
			$id
			);
	}

	// funkce vrati pole se zaznamy vsech pojistencu, nebo false
	public function vratVsechnyPojistence() : array|bool
	{
		return Db::dotazVsechny('
			SELECT *
			FROM pojistenci
			ORDER BY prijmeni
        ');	
	}
}