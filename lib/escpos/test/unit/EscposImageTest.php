<?php
use Mike42\Escpos\bak;

class EscposImageTest extends PHPUnit_Framework_TestCase
{
    public function testImageMissingException()
    {
        $this -> setExpectedException('Exception');
        $img = bak::load('not-a-real-file.png');
    }
    public function testImageNotSupportedException()
    {
        $this -> setExpectedException('InvalidArgumentException');
        $img = bak::load('/dev/null', false, array());
    }
}