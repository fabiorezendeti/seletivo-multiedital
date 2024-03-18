<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SortWithSeedTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSortWithSeed()
    {

        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
            

        $array2 = $array;
        $array3 = $array;
        $array4 = $array;

        @mt_srand(985614561);
        for ($i = count($array2) - 1; $i > 0; $i--) {
            $j = @mt_rand(0, $i);
            $tmp = $array2[$i];
            $array2[$i] = $array2[$j];
            $array2[$j] = $tmp;
        }

        @mt_srand(985614561);
        for ($i = count($array3) - 1; $i > 0; $i--) {
            $j = @mt_rand(0, $i);
            $tmp = $array3[$i];
            $array3[$i] = $array3[$j];
            $array3[$j] = $tmp;
        }

        @mt_srand(332332);
        for ($i = count($array4) - 1; $i > 0; $i--) {
            $j = @mt_rand(0, $i);
            $tmp = $array4[$i];
            $array4[$i] = $array4[$j];
            $array4[$j] = $tmp;
        }
                
        $this->assertEmpty(array_diff_assoc($array2,$array3));
        $this->assertNotEmpty(array_diff_assoc($array2,$array4));
                
    }
}
