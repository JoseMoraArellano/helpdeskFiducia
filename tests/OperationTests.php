<?php
declare (strict_types=1);

use PHPUnit\Framework\TestCase;
include './src/Operations.php';
class OperationTests extends TestCase
{
    public function testFactorialNumber() {
        $Number = 3;
        $expected = 6;
        $operation = new Operations();
        $factorial = $operation ->factorial($Number);
        $this->assertIsInt($factorial);
        $this ->assertEquals($expected, $factorial);
    }
}