<?php

require(__DIR__ . '/Problem.php');

/**
 * Finish implementation of Class Problem3 by having the method it must implement return the
 * solution to the following problem:
 *
 * You have an array for which the ith element is the price of a given stock on day i.
 *
 * If you were only permitted to complete at most one transaction (ie, buy one and sell one share of the stock),
 * design an algorithm to find the maximum profit.
 *
 * For example:
 *
 * $params[0] = [7, 1, 5, 3, 6, 4]
 * return 5
 *
 * $params[0] = [7, 6, 4, 3, 1]
 * return 0
 *
 */
class Problem2 implements Problem
{

    /**
     * @param array ...$params
     *
     * @return int
     */
    public function run(...$params)
    {
        $prices = $params[0];
        $profit = 0;

        foreach ($prices as $i => $iValue) {
            $currentPrice           = $iValue;
            $slice                  = array_slice($prices, $i + 1);
            $maxInTheRestOfTheArray = $slice ? max($slice) : 0;

            if ($maxInTheRestOfTheArray > $currentPrice) {
                $profit = max($profit, $maxInTheRestOfTheArray - $currentPrice);
            }
        }

        return $profit;

    }
}