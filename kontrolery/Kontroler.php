<?php

/* 
 * Více informací na http://www.itnetwork.cz/licence
 */

/**
 * Výchozí kontroler pro ITnetworkMVC
 */
abstract class Kontroler
{

    /**
     * @var array Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
     */
    protected array $data = array();
    /**
     * @var string Název šablony bez přípony
     */
    protected string $pohled = "";
    /**
     * @var array|string[] Hlavička HTML stránky
     */
	protected array $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');

	/**
	 * Ošetří proměnnou pro výpis do HTML stránky
	 * @param mixed $x Proměnná k ošetření
	 * @return mixed Proměnná ošetřená proti XSS útoku
	 */
	private function osetri($x = null)
	{
		if (!isset($x))
			return null;
		elseif (is_string($x))
			return htmlspecialchars($x, ENT_QUOTES);
		elseif (is_array($x))
		{
			foreach($x as $k => $v)
			{
				$x[$k] = $this->osetri($v);
			}
			return $x;
		}
		else 
			return $x;
	}

    /**
     * Vyrenderuje pohled a osetri data
     * @return void
     */
    public function vypisPohled() : void
    {
        if ($this->pohled)
        {
            extract($this->osetri($this->data));
			extract($this->data, EXTR_PREFIX_ALL, "");
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }
	
	/**
     * Přesměruje na dané URL
     * @param string $url URL adresa, na kterou přesměrovat
     * @return never
     */
	public function presmeruj(string $url) : never
	{
		header("Location: /$url");
		header("Connection: close");
        exit;
	}

    /**
     * Hlavní metoda kontroleru
     * @param array $parametry Pole parametrů pro využití kontrolerem
     * @return void
     */
    abstract function zpracuj(array $parametry) : void;

}