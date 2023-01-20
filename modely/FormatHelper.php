<?php
class FormatHelper
{
	public $vstupniDatum;
	
	// prevede pravopisne spravny i nespravny cesky format datumu na databazovy
	public static function datumDoDB($vstupniDatum)
	{
		$datum_pole = explode('.', $vstupniDatum);
		$den = trim($datum_pole[0]);
		$mesic = trim($datum_pole[1]);
		$rok = trim($datum_pole[2]);
	
		return $rok . '-' . $mesic . '-' . $den;
	}
	
	// prevede databazovy format datumu na cesky format dle zadani, ackoliv napr. 1.1.2020 neni z hlediska ceskeho pravopisu spravne
	public static function datumDoFormulare($vstupniDatum)
	{
		$datum_pole = explode('-', $vstupniDatum);
		$den = ltrim($datum_pole[2], "0");
		$mesic = ltrim($datum_pole[1], "0");
		$rok = $datum_pole[0];
	
		return $den . '.' . $mesic . '.' . $rok;
	}
}
