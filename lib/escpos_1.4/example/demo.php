<?php
/**
 * This is a demo script for the functions of the PHP ESC/POS print driver,
 * Escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

//$connector = new FilePrintConnector("php://stdout");
$connector = new WindowsPrintConnector("pos58");
$printer = new Printer($connector);

/* Initialize */
$printer -> initialize();

/* Font modes */
$modes = array(
    Printer::MODE_FONT_B,
    Printer::MODE_EMPHASIZED,
    Printer::MODE_DOUBLE_HEIGHT,
    Printer::MODE_DOUBLE_WIDTH,
    Printer::MODE_UNDERLINE);
for ($i = 0; $i < pow(2, count($modes)); $i++) {
    $bits = str_pad(decbin($i), count($modes), "0", STR_PAD_LEFT);
    $mode = 0;
    for ($j = 0; $j < strlen($bits); $j++) {
        if (substr($bits, $j, 1) == "1") {
            $mode |= $modes[$j];
        }
    }
    $printer -> selectPrintMode($mode);
    $printer -> text($mode."ABCDEFGHIJabcdefghijk\n");
}
$printer -> selectPrintMode(); // Reset
$printer -> cut();

for ($i = 0; $i < 2; $i++) {
    $printer -> setEmphasis($i == 1);
    $printer -> text("Test words with Emphasis\n");
}
$printer -> setEmphasis(false); // Reset
$printer -> cut();

$fonts = array(
    Printer::FONT_A,
    Printer::FONT_B,
    Printer::FONT_C);
for ($i = 0; $i < count($fonts); $i++) {
    $printer -> setFont($fonts[$i]);
    $printer -> text("The quick brown fox jumps over the lazy dog\n");
}
$printer -> setFont(); // Reset
$printer -> cut();
$printer -> pulse();
$printer -> close();
