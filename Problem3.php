<?php

require(__DIR__ . '/Problem.php');

/**
 * Finish implementation of Class Problem4 by having the method it must implement return the
 * solution to the following problem:
 *
 * Find all valid IP address combinations within a given string containing only digits.
 *
 * For example:
 *
 * $params[0] = '25525511135'
 * return ['255.255.11.135', '255.255.111.35']
 *
 */
class Problem3 implements Problem
{

    const OCTETS_IN_ADDRESS = 4;
    const MAX_OCTET = 255;
    const MAX_OCTET_LEN = 3;

    /**
     * @var array
     */
    private $addresses = [];

    public function run(...$params)
    {
        $str = $params[0];

        if (strlen($str) === 12) {
            return [implode('.', str_split($str, 3))];
        }

        $this->generateAddresses(str_split($str));

        return $this->addresses;
    }

    /**
     * For IPv4 full notation only
     * we want to divide the sting by number of octets in an address
     * it shifts 1, 2, 3 chars from the remainder, and recursively does the same with the resulting remainder
     * ex:
     * 1 27001
     *   1 2 7001
     *   1 27 001
     *   1 27 0 01 x nope
     * 127 001
     *   127 0 01
     *   127 0 0 1 v that will work
     *
     * although it does the job, this is quite an inefficient alg.,
     * because it finds the addresses by brute force and wastes cycles
     * maybe a conversion to binary would be better, would love to see a better way
     *
     * @param array $remainder
     * @param int   $nestingLevel
     * @param array $accumulator
     */
    private function generateAddresses(
        array $remainder,
        int $nestingLevel = 0,
        array $accumulator = []
    )
    {
        if (!$remainder && count($accumulator) === self::OCTETS_IN_ADDRESS) {
            $this->addresses[] = implode('.', $accumulator);
        }

        if ($nestingLevel < self::OCTETS_IN_ADDRESS) {
            for ($j = 1; $j <= self::MAX_OCTET_LEN; $j++) {
                $newRemainder = $remainder;
                $shifted      = [];

                for ($i = 0; $i < $j; $i++) {
                    $shifted[] = array_shift($newRemainder);
                }

                $octet            = implode('', $shifted);
                $validNumber      = (string)(int)$octet === $octet; // exclude numbers like 01
                $isInvalidOctet   = $octet > self::MAX_OCTET;
                $hasLeftoverNulls = count($shifted) !== count( // exclude nulls, they produce dupes
                        array_filter(
                            $shifted,
                            static function ($item) {
                                return $item !== null;
                            }
                        )
                    );

                if (!$validNumber || $hasLeftoverNulls || $isInvalidOctet) {
                    continue;
                }

                $this->generateAddresses(
                    $newRemainder,
                    $nestingLevel + 1,
                    array_merge($accumulator, [$octet])
                );
            }
        }
    }
}


//print_r((new Problem4())->run('127001'));
//print_r((new Problem4())->run('25525511135'));
//print_r((new Problem4())->run('19216801'));
//print_r((new Problem4())->run('10101010'));
//print_r((new Problem4())->run('1111'));

