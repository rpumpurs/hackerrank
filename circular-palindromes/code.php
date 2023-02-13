<?php

/*
 * Complete the 'circularPalindromes' function below.
 *
 * The function is expected to return an INTEGER_ARRAY.
 * The function accepts STRING s as parameter.
 */

function circularPalindromes($s) {
    $result = [];
    $palindrome = new CircularPalindrome($s);
    for ($i = 0; $i < strlen($s); $i++) {
        //$rotatedString = substr($s, $i) . substr($s, 0, $i);
        //$palindrome = new Palindrome($rotatedString);
        //var_dump($i);
        //var_dump($rotatedString);
        $result[] = $palindrome->getLongestPalindrome($i);
    }
    return $result;
}

class CircularPalindrome
{
    protected $string;

    protected $stringLength;

    protected $doubleString;

    protected $doubleStringLength;

    protected $stringHalfLength;

    protected $maxOdds = [];
    protected $maxEvens = [];

    public function __construct($string)
    {
        $this->string = $string;
        $this->stringLength = strlen($string);
        $this->doubleString = $string . $string;
        $this->doubleStringLength = $this->stringLength * 2;
        $this->stringHalfLength = $this->stringLength / 2;
    }

    public function getLongestPalindrome($rotation)
    {
        if (!$this->maxOdds) {
            $this->getMaxOddsPalindromes();
        }
        if (!$this->maxEvens) {
            $this->getMaxEvensPalindromes();
        }
        return max($this->maxEvens[$rotation] * 2, $this->maxOdds[$rotation] * 2 - 1);
    }

    protected function getMaxOddsPalindromes() {
        $l = 0;
        $r = -1;
        $d1 = [];

        $mask = [];
        $maskVal = 0;
        for ($v = 0; $v < $this->stringLength; $v++) {
            if ($v < $this->stringHalfLength) {
                $maskVal++;
            } else if ($v > $this->stringHalfLength) {
                $maskVal--;
            }
            $mask[] = $maskVal;
        }

        for ($i = 0; $i < $this->stringLength; $i++) {
            $this->maxOdds[$i] = 0;
        }

        $rotation = 0;
        for ($i = 0; $i < $this->doubleStringLength; $i++) {
            $k = $i > $r ? 1 : min($d1[$l + $r - $i], $r - $i);

            while($rotation <= $i - $k && $i + $k < $this->doubleStringLength && $this->doubleString[$i - $k] == $this->doubleString[$i + $k]) {
                $k++;
            }

            $d1[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k + 1;
                $r = $i + $k - 1;
            }

            if ($i >= $this->stringLength) {
                $rotation = $i - $this->stringLength + 1;
            }

            for ($ii = $rotation; $ii <= $i - $rotation; $ii++) {
                if ($k < $mask[$i - $ii]) {
                    $kWithMask = $k;
                } else {
                    $kWithMask = $mask[$i - $ii];
                }
                if ($kWithMask > $this->maxOdds[$ii]) {
                    $this->maxOdds[$ii] = $kWithMask;
                }
            }
        }
    }

    protected function getMaxEvensPalindromes() {
        $l = 0;
        $r = -1;
        $d2 = [];

        $mask = [];
        $maskVal = -1;
        for ($v = 0; $v < $this->stringLength; $v++) {
            if ($v < $this->stringHalfLength) {
                $maskVal++;
            } else if ($v > $this->stringHalfLength + 1) {
                $maskVal--;
            }

            $mask[] = $maskVal;
        }

        for ($i = 0; $i < $this->stringLength; $i++) {
            $this->maxEvens[$i] = 0;
        }

        $rotation = 0;
        for ($i = 0; $i < $this->doubleStringLength; $i++) {
            $k = $i > $r ? 0 : min($d2[$l + $r - $i + 1], $r - $i + 1);

            while($i + $k < $this->doubleStringLength && $i - $k - 1 >= $rotation && $this->doubleString[$i + $k] == $this->doubleString[$i - $k - 1]){
                $k++;
            }

            $d2[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k;
                $r = $i + $k - 1;
            }

            if ($i >= $this->stringLength) {
                $rotation = $i - $this->stringLength + 1;
            }

            for ($ii = $rotation; $ii <= $i - $rotation; $ii++) {
                if ($k < $mask[$i - $ii]) {
                    $kWithMask = $k;
                } else {
                    $kWithMask = $mask[$i - $ii];
                }
                if ($kWithMask > $this->maxEvens[$ii]) {
                    $this->maxEvens[$ii] = $kWithMask;
                }
            }
        }
    }
}

class Palindrome
{
    /**
     * String
     *
     * @var array
     */
    protected $string;

    /**
     * String length
     *
     * @var integer
     */
    protected $stringLength;

    /**
     * Palindrome constructor.
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        $this->stringLength = strlen($string);
    }

    /**
     * Get longest palindrome
     *
     * @return integer
     */
    public function getLongestPalindrome()
    {
        return max($this->getMaxEvenPalindrome(), $this->getMaxOddPalindrome());
    }

    /**
     * Get max odd palindrome
     *
     * @return integer
     */
    protected function getMaxOddPalindrome() {
        $l = 0;
        $r = -1;
        $d1 = [];

        for ($i = 0; $i < $this->stringLength; $i++) {
            $k = $i > $r ? 1 : min($d1[$l + $r - $i], $r - $i);

            while(0 <= $i - $k && $i + $k < $this->stringLength && $this->string[$i - $k] == $this->string[$i + $k]) {
                $k++;
            }

            $d1[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k + 1;
                $r = $i + $k - 1;
            }
        }

        $max = max($d1);

        return $max * 2 - 1;
    }

    /**
     * Get max even palindrome
     *
     * @return integer
     */
    protected function getMaxEvenPalindrome() {
        $l = 0;
        $r = -1;
        $d2 = [];

        for ($i = 0; $i < $this->stringLength; $i++) {
            $k = $i > $r ? 0 : min($d2[$l + $r - $i + 1], $r - $i + 1);

            while($i + $k < $this->stringLength && $i - $k - 1 >= 0 && $this->string[$i + $k] == $this->string[$i - $k - 1]){
                $k++;
            }

            $d2[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k;
                $r = $i + $k - 1;
            }
        }

        $max = max($d2);

        return $max * 2;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$n = intval(trim(fgets(STDIN)));

$s = rtrim(fgets(STDIN), "\r\n");

$result = circularPalindromes($s);

fwrite($fptr, implode("\n", $result) . "\n");

fclose($fptr);
