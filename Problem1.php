<?php

require(__DIR__ . '/Problem.php');

/**
 * Finish implementation of Class Problem1 by having the method it must implement return the
 * solution to the following problem:
 *
 * Given an array of integers sorted in ascending order, find the starting and ending position of a given target value.
 *
 * Your algorithm's runtime complexity must be in the order of O(log n).
 *
 * If the target is not found in the array, return [-1, -1].
 *
 * For example, given
 * $params[0] = [5, 7, 7, 8, 8, 10]
 * and target value
 * $params[1] = 8
 *
 * return [3, 4]
 *
 */
class Problem1 implements Problem
{

    /**
     * its more like a 1000, when it starts to make a positive considerable difference in speed
     * tested against bruteforce foreach lookup, on php7.0.22 i3 4th gen
     */
    const MIN_SPLIT_DISTANCE = 5;

    /**
     * @param array ...$params
     *
     * @return array
     */
    public function run(...$params)
    {
        [$data, $target] = $params;
        $ranges        = [[0, count($data) - 1]];
        $defaultResult = [-1, -1];
        $result        = [];

        while ($ranges) {
            foreach ($ranges as $range) {

                // recycle used range
                array_shift($ranges);

                [$startPosition, $endPosition] = $range;

                $middlePosition = (int)ceil(($startPosition + $endPosition) / 2);
                $middleValue    = $data[$middlePosition];
                $startValue     = $data[$startPosition];
                $endValue       = $data[$endPosition];

                // it's faster to do a for loop at this point
                if ($endPosition - $startPosition < self::MIN_SPLIT_DISTANCE) {
                    for ($i = $startPosition; $i < $endPosition; $i++) {
                        if ($data[$i] === $target) {
                            $result[] = $i;
                        }
                    }
                    continue;
                }

                // find ranges where the target is
                if ($target >= $startValue && $target <= $middleValue) {
                    $ranges[] = [$startPosition, $middlePosition];
                }

                if ($target >= $middleValue && $target <= $endValue) {
                    $ranges[] = [$middlePosition, $endPosition];
                }
            }
        }

        return $result ?: $defaultResult;
    }
}
