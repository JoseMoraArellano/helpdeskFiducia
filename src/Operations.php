<?php
declare(strict_types=1);
class Operations{
    public function factorial(int $Number):int {
        $result = 1;
        for ($i =$Number;$i>1;$i--){
            $result *= $i;
        }
        return $result;
    }
}