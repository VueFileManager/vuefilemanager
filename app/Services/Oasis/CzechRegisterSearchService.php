<?php

/**
 * Parser pre vyhladavanie a vypis z obchodneho registra ČR
 * Lookup service for Czech commercial register (www.justice.cz)
 *
 * Version 1.0.0 (released 05.12.2019)
 * (c) 2019 lubosdz@gmail.com
 *
 * ------------------------------------------------------------------
 * Disclaimer / Prehlásenie:
 * Kód poskytnutý je bez záruky a môže kedykoľvek prestať fungovať.
 * Jeho funkčnosť je striktne naviazaná na generovanú štruktúru HTML elementov.
 * Autor nie je povinný udržiavať kód aktuálny a funkčný, ani neposkytuje ku nemu žiadnu podporu.
 * Autor nezodpovedá za nesprávne použitie kódu.
 * ------------------------------------------------------------------
 *
 * Usage example:
 * --------------
 * $connector = new ConnectorJusticeCz;
 * $out = $connector->findByNazev('auto');
 * $out = $connector->findByIco('44315945');
 * echo ''.print_r($out, 1).'';
 */
namespace App\Services\Oasis;

class CzechRegisterSearchService
{
    /**
     * @var string Target server URL base
     */
    const URL_SERVER = 'https://or.justice.cz/ias/ui/rejstrik-$firma';

    /**
     * @var int Max. dlzka nazvu pre autocomplete options
     */
    public $labelMaxChars = 30;

    /**
     * Vyhlada zoznam subjektov podla presneho ICO, ciastkove ICO nie je povolene
     * @param string $ico 8-miestne cislo, e.g. 29243831 or 64612023
     */
    public function findByIco($ico)
    {
        $response = [];
        $ico = preg_replace('/[^\d]/', '', $ico);

        if (preg_match('/^\d{8}$/', $ico)) {
            $url = self::URL_SERVER . '?ico=' . $ico;
            $response = file_get_contents($url);
            $response = self::extractSubjects($response);
        }

        return $response;
    }

    /**
     * Vyhlada zoznam subjektov podla ciastkoveho nazvu
     * @param string $nazev , e.g. "pojisteni"
     */
    public function findByNazev($nazev)
    {
        $response = [];

        if ($nazev) {
            $nazev = trim($nazev);
            $url = self::URL_SERVER . '?nazev=' . urlencode($nazev);
            $response = file_get_contents($url);
            $response = self::extractSubjects($response);
        }

        return $response;
    }

    /**
     * Vyhlada zoznam subjektov podla presneho ICO, ciastkove ICO nie je povolene
     * @param string $ico 8-miestne cislo, e.g. 12345678
     */
    public function getDetailByICO($ico)
    {
        $response = [];
        $ico = preg_replace('/[^\d]/', '', $ico);

        if (preg_match('/^\d{8}$/', $ico)) {
            $url = self::URL_SERVER . '?ico=' . $ico;
            $response = file_get_contents($url);

            if ($response) {
                $response = self::extractSubjects($response);

                if (! empty($response[0])) {
                    $response = $response[0];
                }
            }
        }

        return $response;
    }

    /**
     * Return matched formatted for autocomplete dropdown list
     * @param string $term Searched matching string
     * @param int how many items to return, 1 - 50 (justice vrati max. 50 zaznamov)
     */
    public function findForAutocomplete($term, $size = 15)
    {
        $out = [];
        $size = intval($size);

        if ($term && $size > 0 && mb_strlen($term, 'utf-8') >= 3) {
            // Justice vrati vysledok pre min. 3 znaky
            if (preg_match('/^\d{8}$/', $term)) {
                $subjects = $this->findByIco($term);
            } else {
                $subjects = $this->findByNazev($term);
            }
        }

        if (! empty($subjects) && is_array($subjects)) {
            $subjects = array_slice($subjects, 0, $size); // return first $size matches

            foreach ($subjects as &$subject) {
                $subject['shortname'] = $subject['name'];
                // cut off too long names
                if (mb_strlen($subject['shortname'], 'utf-8') > $this->labelMaxChars) {
                    $subject['shortname'] = mb_substr($subject['shortname'], 0, $this->labelMaxChars - 3, 'utf-8') . ' ..';
                }
            }

            foreach ($subjects as $subject) {
                if (! empty($subject['ico'])) {
                    $out[] = [
                        'value' => $subject['ico'],
                        'label' => "{$subject['shortname']} (IČO: {$subject['ico']})",
                    ];
                }
            }
        }

        return $out;
    }

    /**
     * Vrati zoznam najdenych subjektov s udajmi
     * @param string $html HTML response zo servera justice.cz
     * @return [name, ico, city, addr_streetnr, addr_city, addr_zip, ..]
     */
    protected static function extractSubjects($html)
    {
        // ensure valid XHTML markup
        if (! extension_loaded('tidy')) {
            throw new \Exception('Missing extension [tidy].');
        }

        $tidy = new \tidy();
        $html = $tidy->repairString($html, [
            'output-xhtml'   => true,
            'show-body-only' => true,
        ], 'utf8');

        // purify whitespaces - vkladaju \n alebo
        $html = strtr($html, [
            ' ' => ' ',
        ]);
        $html = preg_replace('/\s+/', ' ', $html);

        // load XHTML into DOM document
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->loadXML($html);
        $xpath = new \DOMXPath($xml);
        $rows = $xpath->query('//table[@class="result-details"]/tbody');

        $out = [];

        if ($rows->length) {
            foreach ($rows as $row) {
                // Nazev
                $nodeList = $xpath->query('./tr[1]/td[1]', $row);

                if (! $nodeList->length) {
                    continue; // nazev je povinny
                }
                $name = $nodeList->item(0)->nodeValue;
                $name = preg_replace('/\s+/', ' ', $name); // viacnasobne inside spaces

                // ICO
                $nodeList = $xpath->query('./tr[1]/td[2]', $row);
                $ico = $nodeList->length ? $nodeList->item(0)->nodeValue : '';

                // adresa - neda sa spolahnut na poradie prvkov :-(
                $city = '';
                $nodeList = $xpath->query('./tr[3]/td[1]', $row);

                if ($nodeList->length) {
                    $addr = trim($nodeList->item(0)->nodeValue);

                    if (preg_match('/,\s*(\d{3} ?\d{2})\s+(.+)$/', $addr, $match)) {
                        // Příborská 597, Místek, 738 01 Frýdek-Místek - nazov obce za PSC, prva je ulice a cislo
                        $city = $addr_city = $match[2];
                        list($addr_streetnr) = explode(',', $addr);
                        $addr_zip = $match[1];
                    } elseif (preg_match('/,\s*PSČ\s+(\d{3} ?\d{2})$/', $addr, $match)) {
                        // Řevnice, ČSLA 118, okres Praha-západ, PSČ 25230 - PSC na konci, obec je prva, ulice a cislo druha
                        list($city, $addr_streetnr) = explode(',', $addr);
                        $addr_city = $city;
                        $addr_zip = $match[1];
                    } elseif (! preg_match('/\d{3} ?\d{2}/', $addr, $match)) {
                        // Ústí nad Labem, Masarykova 74 - bez PSC - obec, ulice a cislo
                        $addr_streetnr = $addr_zip = '';

                        if (false !== strpos($addr, ',')) {
                            list($city, $addr_streetnr) = explode(',', $addr);
                        } else {
                            list($city) = explode(',', $addr);
                        }
                        $addr_city = $city;
                    }

                    // "Praha 10 - Dolní Měcholupy" -> Praha 10, pozn: Frydek-Mistek nema medzeru okolo pomlcky
                    // whoops, avsak ani Ostrana-Hontice a dalsie .. :-( Pre city potrebujeme kratky nazov do 10-15 pismen
                    list($city) = explode('-', $city);
                    // Praha 5 -> Praha
                    $city = preg_replace('/\d/', '', $city);
                    // viacnasobne spaces
                    $city = preg_replace('/\s+/', ' ', $city);
                }

                $out[] = [
                    'name' => self::trimQuotes($name),
                    'ico'  => preg_replace('/[^\d]/', '', $ico),
                    'city' => self::trimQuotes($city),
                    // pre polia s adresou konzistentne so smartform naseptavacem
                    'addr_city'     => self::trimQuotes($addr_city),
                    'addr_zip'      => preg_replace('/[^\d]/', '', $addr_zip),
                    'addr_streetnr' => self::trimQuotes($addr_streetnr),
                    // len pre kontrolu - plna povodna adresa
                    'addr_full' => self::trimQuotes($addr),
                ];
            }
        }

        return $out;
    }

    /**
     * Vyhodi quotes z textu, aby neposkodilo HTML atributy
     * @param string $s
     */
    protected static function trimQuotes($s)
    {
        return trim(strtr($s, ['"' => '', "'" => '']));
    }
}
