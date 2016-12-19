<?php
/**
 *  1. Der Bayer arbeitet in der Halle aus dem Baujahr 1955.
 *  2. Der Th�ringer geht gern Fischen.
 *  3. Der Berliner arbeitet bei Hochtief.
 *  4. Die 1946 erbaute Halle befindet sich links von der 1958 erbauten Halle.
 *  5. Die Person aus der 1946 erbauten Halle arbeitet bei Bauer.
 *  6. Der Bewehrerspielt gern Volleyball.
 *  7. Die Person aus der 1963 erbauten Halle ist Maurer.
 *  8. Die Person aus der Mittelhalle arbeitet bei Z�blin.
 *  9. Der Hesse arbeitet in der ersten Halle.
 * 10. Der Kranfahrer arbeitet in der Halle neben der Person, die gern Fu�ball spielt.
 * 11. Die Person die gern Tennis spielt, arbeitet neben dem Maurer.
 * 12. Der Maler arbeitet bei Bilfinger.
 * 13. Der Bremer arbeitet als Polier.
 * 14. Der Hesse arbeitet neben der 1939 erbauten Halle.
 * 15. Der Kranfahrer arbeitet neben der Person die bei Strabagarbeitet.
 * 16. Der Maler arbeitet neben dem Bayer.
 *
 * Frage: Wer geht gern Golfen ?
 */

/*
 * Alle Optionen gem�� Vorgabe
 */

$land = array('Bayer', 'Th�ringer', 'Berliner', 'Hesse', 'Bremer');
$job = array('Maurer', 'Polier', 'Maler', 'Bewehrer', 'Kranfahrer');
$company = array('Hochtief', 'Bauer', 'Z�blin', 'Bilfinger', 'Strabag');
$sport = array('Fischen', 'Volleyball', 'Fu�ball', 'Golf', 'Tennis');
$date = array(1955, 1946, 1958, 1963, 1939);
$position = array(1, 2, 3, 4, 5);

/*
 * Alle m�glichen Variationen anlegen.
 * Dabei pr�fen wir (is_possible) in jeder Variante, ob diese sich �berhaupt mit den einfachen Angaben vereinbaren l�sst.
 */

$variations = array();

foreach($land as $vland)
{
    foreach($job as $vjob)
    {
        foreach($company as $vcompany)
        {
            foreach($sport as $vsport)
            {
                foreach($date as $vdate)
                {
                    foreach($position as $vposition)
                    {
                        /*
                         * Wir speichern nur die Varianten, die gem�� der Angabe m�glich w�ren. Das spart sp�ter ein paar Schleifendurchl�ufe.
                         */

                        if(is_possible($vland, $vjob, $vcompany, $vsport, $vdate, $vposition))
                        {
                            $variations[] = array(
                                'land'     => $vland,
                                'job'      => $vjob,
                                'company'  => $vcompany,
                                'sport'    => $vsport,
                                'date'     => $vdate,
                                'position' => $vposition
                            );
                        }
                    }
                }
            }
        }
    }
}

// In $variations sind nun alle m�glichen Kombinationen, welche die Angabe nach den offensichtlichen und fixen Bedingungen theoretisch noch zul�sst.
// Damit sind die Bedingungen 1, 2, 3, 5, 6, 7, 8, 9, 12 und 13 erf�llt, und die 4, 10, 11, 14, 15 und 16 teilweise erf�llt.

$variations = rest($variations);

echo 'Verbleibend: ' . count($variations) . '<br><br>';

foreach($variations as $array)
{
    print_r($array);
    echo '<br><br>';
}

// ---------- Programm ENDE ----------

// ---------- FUNKTIONEN: ----------

/*
 * Unser Test gegen die einfachen Bedingungen, gegen die wir schon w�hrend des Aufbaus der Variationen testen.
 */

function is_possible($vland, $vjob, $vcompany, $vsport, $vdate, $vposition)
{
    // 1. Der Bayer arbeitet in der Halle aus dem Baujahr 1955.
    if(($vland == 'Bayer' && $vdate != 1955) || ($vdate == 1955 && $vland != 'Bayer')) { return FALSE; }

    // 2. Der Th�ringer geht gern Fischen.
    elseif(($vland == 'Th�ringer' && $vsport != 'Fischen') || ($vsport == 'Fischen' && $vland != 'Th�ringer')) { return FALSE; }

    // 3. Der Berliner arbeitet bei Hochtief.
    elseif(($vland == 'Berliner' && $vcompany != 'Hochtief') || ($vcompany == 'Hochtief' && $vland != 'Berliner')) { return FALSE; }
	
	// 4. Die 1946 erbaute Halle befindet sich links von der 1958 erbauten Halle.
	// Teilweise: 1946 kann nicht Position 5, und 1958 kann nicht Postion 1 haben.
	elseif($vdate == 1946 && $vposition == 5) { return FALSE; }
	elseif($vdate == 1958 && $vposition == 1) { return FALSE; }

    // 5.Die Person aus der 1946 erbauten Halle arbeitet bei Bauer.
    elseif(($vdate == 1946 && $vcompany != 'Bauer') || ($vcompany == 'Bauer' && $vdate != 1946)) { return FALSE; }

    // 6. Der Bewehrerspielt gern Volleyball.
    elseif(($vjob == 'Bewehrer' && $vsport != 'Volleyball') || ($vsport == 'Volleyball' && $vjob != 'Bewehrer')) { return FALSE; }

    // 7. Die Person aus der 1963 erbauten Halle ist Maurer.
    elseif(($vdate == 1963 && $vjob != 'Maurer') || ($vjob == 'Maurer' && $vdate != 1963)) { return FALSE; }

    // 8.Die Person aus der Mittelhalle arbeitet bei Z�blin.
    elseif(($vposition == 3 && $vcompany != 'Z�blin') || ($vcompany == 'Z�blin' && $vposition != 3))  { return FALSE; }

    // 9.Der Hesse arbeitet in der ersten Halle.
    elseif(($vland == 'Hesse' && $vposition != 1) || ($vposition == 1 && $vland != 'Hesse')) { return FALSE; }

    // 10. Der Kranfahrer arbeitet in der Halle neben der Person, die gern Fu�ball spielt.
	// Teilweise: Der Kranfahrer spielt nicht Fu�ball.
    elseif($vjob == 'Kranfahrer' && $vsport == 'Fu�ball') { return FALSE; }

    // 11. Die Person die gern Tennis spielt, arbeitet neben dem Maurer.
	// Teilweise: Der Tennisspieler ist nicht Maurer.
    elseif($vsport == 'Tennis' && $vjob == 'Maurer') { return FALSE; }

    // 12. Der Maler arbeitet bei Bilfinger.
    elseif(($vjob == 'Maler' && $vcompany != 'Bilfinger') || ($vcompany == 'Bilfinger' && $vjob != 'Maler'))  { return FALSE; }

    // 13. Der Bremer arbeitet als Polier.
    elseif(($vland == 'Bremer' && $vjob != 'Polier') || ($vjob == 'Polier' && $vland != 'Bremer'))  { return FALSE; }

    // 14. Der Hesse arbeitet neben der 1939 erbauten Halle.
	// Teilweise: Der Hesse arbeitet nicht in 1939.
    elseif($vland == 'Hesse' && $vdate == 1939)  { return FALSE; }

    // 15. Der Kranfahrer arbeitet neben der Person die bei Strabag arbeitet.
	// Teilweise: Der Kranfahrer arbeitet nicht bei Strabag.
    elseif($vjob == 'Kranfahrer' && $vcompany == 'Strabag')  { return FALSE; }

    // 16. Der Maler arbeitet neben dem Bayer.
	// Teilweise: Der Maler ist kein Bayer.
    elseif($vjob == 'Maler' && $vland == 'Bayer')  { return FALSE; }
	
	// 9 und 14 kombiniert
	// 9. Der Hesse arbeitet in der ersten Halle.
	// 14. Der Hesse arbeitet neben der 1939 erbauten Halle.
	// Also muss Halle 1939 auf Position 2 stehen
	elseif(($vdate == 1939 && $vposition != 2) || ($vposition == 2 && $vdate != 1939))  { return FALSE; }

    // Ansonsten ist die Variante dieser Schleife  m�glich
    else { return TRUE; }
}

/*
 * Die restlichen Bedingungen gegen jede verbliebene Variation testen
 * Positionen der Hallen...
 */

function rest($variations)
{	
	foreach($variations as $key1 => $array1)
    {
        // 4. Die 1946 erbaute Halle befindet sich links von der 1958 erbauten Halle.
        if($array1['date'] == 1946)
        {
			foreach($variations as $key2 => $array2)
            {
                if($array2['date'] == 1958)
                {
                    if($array1['position'] >= $array2['position'])
                    {
                        unset($variations[$key2]);
                    }
                }
            }
        }
		
		if($array1['date'] == 1958)
        {
			foreach($variations as $key2 => $array2)
            {
                if($array2['date'] == 1946)
                {
                    if($array1['position'] <= $array2['position'])
                    {
                        unset($variations[$key2]);
                    }
                }
            }
        }
    }
	
	return $variations;
}