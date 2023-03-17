<?php

/*
 * Complete the 'passwordCracker' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts following parameters:
 *  1. STRING_ARRAY passwords
 *  2. STRING loginAttempt
 */

function passwordCracker($passwords, $loginAttempt) {
    $result = [];

    foreach ($passwords as $password) {
        if (strpos($loginAttempt, $password) === 0) {
            $result[] = $password;
            if (strlen($loginAttempt) > strlen($password)) {
                $concatPasswords = passwordCracker($passwords, substr($loginAttempt, strlen($password)));
                if (in_array('WRONG PASSWORD', $concatPasswords)) {
                    array_pop($result);
                    continue;
                }
                if (!$concatPasswords) {
                    array_pop($result);
                    continue;
                }
                $result = array_merge($result, $concatPasswords);
            }
            if (implode('', $result) === $loginAttempt) {
                break;
            }
        }
    }

    if (!$result) {
        return ['WRONG PASSWORD'];
    }

    return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$t = intval(trim(fgets(STDIN)));

function sortByStrLen($a,$b){
    return strlen($a) - strlen($b);
}

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    $n = intval(trim(fgets(STDIN)));

    $passwords_temp = rtrim(fgets(STDIN));

    $passwords = preg_split('/ /', $passwords_temp, -1, PREG_SPLIT_NO_EMPTY);

    $loginAttempt = rtrim(fgets(STDIN), "\r\n");

    usort($passwords,'sortByStrLen');

    $redundantPasswords = [];
    for ($i = 0; $i <= count($passwords); $i++) {
        for ($j = $i + 1; $j <= count($passwords) - 1; $j++) {
            $a = passwordCracker([$passwords[$i]], $passwords[$j]);
            if ($a[0] !== 'WRONG PASSWORD') {
                $redundantPasswords[$passwords[$j]] = true;
            }
        }
    }

    foreach($redundantPasswords as $password => $true) {
        if (($key = array_search($password, $passwords)) !== false) {
            unset($passwords[$key]);
        }
    }

    $result = passwordCracker($passwords, $loginAttempt);

    fwrite($fptr, implode(' ', $result) . "\n");
}

fclose($fptr);
