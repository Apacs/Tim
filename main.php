<?php
/**
 *  1. Der Bayer arbeitet in der Halle aus dem Baujahr 1955.
 *  2. Der Thüringer geht gern Fischen.
 *  3. Der Berliner arbeitet bei Hochtief.
 *  4. Die 1946 erbaute Halle befindet sich links von der 1958 erbauten Halle.
 *  5. Die Person aus der 1946 erbauten Halle arbeitet bei Bauer.
 *  6. Der Bewehrerspielt gern Volleyball.
 *  7. Die Person aus der 1963 erbauten Halle ist Maurer.
 *  8. Die Person aus der Mittelhalle arbeitet bei Züblin.
 *  9. Der Hesse arbeitet in der ersten Halle.
 * 10. Der Kranfahrer arbeitet in der Halle neben der Person, die gern Fußball spielt.
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
 * Alle Optionen gemäß Vorgabe
 */

$land = array('Bayer', 'Thüringer', 'Berliner', 'Hesse', 'Bremer');
$job = array('Maurer', 'Polier', 'Maler', 'Bewehrer', 'Kranfahrer');
$company = array('Hochtief', 'Bauer', 'Züblin', 'Bilfinger', 'Strabag');
$sport = array('Fischen', 'Volleyball', 'Fußball', 'Golf', 'Tennis');
$date = array(1955, 1946, 1958, 1963, 1939);
$position = array(1, 2, 3, 4, 5);

/*
 * Alle möglichen Variationen anlegen.
 * Dabei prüfen wir (is_possible) in jeder Variante, ob diese sich überhaupt mit den einfachen Angaben vereinbaren lässt.
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
                         * Wir speichern nur die Varianten, die gemäß der Angabe möglich wären. Das spart später ein paar Schleifendurchläufe.
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

// In $variations sind nun alle möglichen Kombinationen, welche die Angabe nach den offensichtlichen und fixen Bedingungen theoretisch noch zulässt.
// Damit sind die Bedingungen 1, 2, 3, 5, 6, 7, 8, 9, 12 und 13 erfüllt, und die 4, 10, 11, 14, 15 und 16 teilweise erfüllt.

$variations = rest($variations);

$variations = filtered($variations);

echo 'Verbleibend: ' . count($variations) . '<br><br>';

foreach($variations as $array)
{
    print_r($array);
    echo '<br><br>';
}

/*
 * Ab hier keinen Bock mehr zu denken. Rest soll der Computer machen... ;-)
 * --- ##### BRUTFORCE ##### ---
 */

/*
$hui = 0;

foreach($variations as $case1)
{
    foreach($variations as $case2)
    {
        foreach($variations as $case3)
        {
            foreach($variations as $case4)
            {
                foreach($variations as $case5)
                {
                    $totest = array();
                    $totest[] = $case1;
                    $totest[] = $case2;
                    $totest[] = $case3;
                    $totest[] = $case4;
                    $totest[] = $case5;

                    $hui++;
                    echo $hui . '<br>';

                    if(is_match($totest)) { $final_result = $totest; break; }
                }
            }
        }
    }
}

echo 'HEUREKA!<br><br>';

if($final_result) { print_r($final_result); }
*/

// ---------- Programm ENDE ----------

// ---------- FUNKTIONEN: ----------

/*
 * Unser Test gegen die einfachen Bedingungen, gegen die wir schon während des Aufbaus der Variationen testen.
 */

function is_possible($vland, $vjob, $vcompany, $vsport, $vdate, $vposition)
{
    // 1. Der Bayer arbeitet in der Halle aus dem Baujahr 1955.
    if(($vland == 'Bayer' && $vdate != 1955) || ($vdate == 1955 && $vland != 'Bayer')) { return FALSE; }

    // 2. Der Thüringer geht gern Fischen.
    elseif(($vland == 'Thüringer' && $vsport != 'Fischen') || ($vsport == 'Fischen' && $vland != 'Thüringer')) { return FALSE; }

    // 3. Der Berliner arbeitet bei Hochtief.
    elseif(($vland == 'Berliner' && $vcompany != 'Hochtief') || ($vcompany == 'Hochtief' && $vland != 'Berliner')) { return FALSE; }
	
	// 4. Die 1946 erbaute Halle befindet sich links von der 1958 erbauten Halle.
	// Teilweise: 1946 kann nicht Position 5, und 1958 kann nicht Postion 1 haben.
	elseif($vdate == 1946 && $vposition == 5) { return FALSE; }
	elseif($vdate == 1958 && $vposition == 1) { return FALSE; }

    // 5.Die Person aus der 1946 erbauten Halle arbeitet bei Bauer.
    elseif(($vdate == 1946 && $vcompany != 'Bauer') || ($vcompany == 'Bauer' && $vdate != 1946)) { return FALSE; }

    // 6. Der Bewehrer spielt gern Volleyball.
    elseif(($vjob == 'Bewehrer' && $vsport != 'Volleyball') || ($vsport == 'Volleyball' && $vjob != 'Bewehrer')) { return FALSE; }

    // 7. Die Person aus der 1963 erbauten Halle ist Maurer.
    elseif(($vdate == 1963 && $vjob != 'Maurer') || ($vjob == 'Maurer' && $vdate != 1963)) { return FALSE; }

    // 8.Die Person aus der Mittelhalle arbeitet bei Züblin.
    elseif(($vposition == 3 && $vcompany != 'Züblin') || ($vcompany == 'Züblin' && $vposition != 3))  { return FALSE; }

    // 9.Der Hesse arbeitet in der ersten Halle.
    elseif(($vland == 'Hesse' && $vposition != 1) || ($vposition == 1 && $vland != 'Hesse')) { return FALSE; }

    // 10. Der Kranfahrer arbeitet in der Halle neben der Person, die gern Fußball spielt.
	// Teilweise: Der Kranfahrer spielt nicht Fußball.
    elseif($vjob == 'Kranfahrer' && $vsport == 'Fußball') { return FALSE; }

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
    // Auskommentiert weil wohl absichtlich falsche Angabe
    // elseif($vjob == 'Maler' && $vland == 'Bayer')  { return FALSE; }
	
	// 9 und 14 kombiniert
	// 9. Der Hesse arbeitet in der ersten Halle.
	// 14. Der Hesse arbeitet neben der 1939 erbauten Halle.
	// Also muss Halle 1939 auf Position 2 stehen
	elseif(($vdate == 1939 && $vposition != 2) || ($vposition == 2 && $vdate != 1939))  { return FALSE; }

    // Ansonsten ist die Variante dieser Schleife  möglich
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

    // Nachbarschaftsverhältnisse

    // 10. Der Kranfahrer arbeitet in der Halle neben der Person, die gern Fußball spielt.
    $variations = unset_no_naighbors($variations, array('job' => 'Kranfahrer', 'sport' => 'Fußball'));

    // 11. Die Person die gern Tennis spielt, arbeitet neben dem Maurer.
    $variations = unset_no_naighbors($variations, array('sport' => 'Tennis', 'job' => 'Maurer'));

    // 14. Der Hesse arbeitet neben der 1939 erbauten Halle.
    $variations = unset_no_naighbors($variations, array('land' => 'Hesse', 'date' => 1939));

    // 15. Der Kranfahrer arbeitet neben der Person die bei Strabag arbeitet.
    $variations = unset_no_naighbors($variations, array('job' => 'Kranfahrer', 'company' => 'Strabag'));

	return $variations;
}

// Test

function filtered($rows)
{
    $filtered = array();
    foreach($rows as $index => $columns)
    {
        foreach($columns as $key => $value)
        {
            if($key == 'land' && $value == 'Bayer')
            {
                $filtered[] = $columns;
            }
        }
    }

    return $filtered;
}



function unset_no_naighbors($variations, $args)
{
    $search_keys = array_keys($args);

    $first_key = $search_keys[0];
    $first_value = $args[$search_keys[0]];

    $second_key = $search_keys[1];
    $second_value = $args[$search_keys[1]];

    $first_pos = array();
    $second_pos = array();

    foreach($variations as $key => $array)
    {
        if($array[$first_key] == $first_value)
        {
            $first_pos[$key] = $array['position'];
        }
        elseif($array[$second_key] == $second_value)
        {
            $second_pos[$key] = $array['position'];
        }
    }

    if(!empty($first_pos) && !empty($second_pos))
    {
        $to_delete_first = find_unpossible($first_pos, $second_pos);
        $to_delete_second = find_unpossible($second_pos, $first_pos);

        foreach($variations as $key => $array)
        {

            if(($array[$first_key] == $first_value) && in_array($array['position'], $to_delete_first))
            {
                unset($variations[$key]);
            }

            if(($array[$second_key] == $second_value) && in_array($array['position'], $to_delete_second))
            {
                unset($variations[$key]);
            }
        }
    }

    return $variations;
}

/*
 * Nimmt zwei Arrays von Positionen entgegen und returned die Positionen aus $first_pos welche sich nicht in einem Nabarschaftsverhältnis zu $second_pos befinden können
 */

function find_unpossible($first_pos, $second_pos)
{
    $pos_to_delete = array();

    if(in_array(1, $first_pos) && !in_array(2, $second_pos)) { $pos_to_delete[] = 1; }
    if(in_array(2, $first_pos) && !in_array(1, $second_pos) && !in_array(3, $second_pos)) { $pos_to_delete[] = 2; }
    if(in_array(3, $first_pos) && !in_array(2, $second_pos) && !in_array(4, $second_pos)) { $pos_to_delete[] = 3; }
    if(in_array(4, $first_pos) && !in_array(3, $second_pos) && !in_array(5, $second_pos)) { $pos_to_delete[] = 4; }
    if(in_array(5, $first_pos) && !in_array(4, $second_pos)) { $pos_to_delete[] = 5; }

    return $pos_to_delete;
}

/*
 * Prüft ob eine übergebene Matrix nur aus uniquen Werten besteht
 */

function is_match($totest)
{
    // Alle Werte der Matrix in einen Array schreiben
    $values = array();
    foreach($totest as $row)
    {
        $values = array_merge($values, array_values($row));
    }

    // Prüfen ob es bei den Werten Duplikate gibt
    if(count($values) !== count(array_unique($values))) { return FALSE; }
    else { return TRUE; }
}
