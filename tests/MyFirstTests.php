<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;

class MyfirstTests extends TestCase{ 
public function testFirst() : void {
    $this->assertTrue(true);
    }
    public function testSecond() : void {
    $this->assertIsArray([],"el valor de salida debe ser un array");
    }

    public function testThird() : void {
       $this->assertStringStartsWith("a","andres","el valor debe comenzar con a Minuscula");
    }
    public function testFourth() : void {
        $array =[
            "data" => []];
        assertArrayHasKey("data",$array);
    }
}