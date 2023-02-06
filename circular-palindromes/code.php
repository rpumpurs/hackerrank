<?php

/*
 * Complete the 'circularPalindromes' function below.
 *
 * The function is expected to return an INTEGER_ARRAY.
 * The function accepts STRING s as parameter.
 */

function circularPalindromes($s) {
    $result = [];
    for ($i = 0; $i < strlen($s); $i++) {
        //var_dump($i);
        $rotatedString = substr($s, $i) . substr($s, 0, $i);
        //var_dump($rotatedString);
        $palindrome = new Palindrome($rotatedString);
        $result[] = $palindrome->getLongestPalindrome();
    }
    return $result;
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
