<?php
require __DIR__ . '/../autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$connector = new WindowsPrintConnector("pos58");
$printer = new Printer($connector);

$tux = EscposImage::load("resources/logo.png", false);
$printer -> bitImage($tux);
$printer -> feed();

/* Name of shop */
$printer -> setJustification(Printer::JUSTIFY_CENTER);
//$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
$printer -> setFont(Printer::FONT_B);
$printer -> setTextSize(1, 2);
$printer -> text("NEW BALANCE GRAND INDONESIA\n");
//$printer -> selectPrintMode();
$printer -> text("GRAND INDONESIA EM2 39A\n");
$printer -> text("JL MH THAMRIN N0 1\n\n");
$printer -> setFont();
$printer -> feed();

/* Title of receipt */
//$printer -> setEmphasis(true);
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setFont(Printer::FONT_B);
$printer -> setTextSize(1, 2);
$printer -> text("TID:673736               MID: 000001052376\n");
$printer -> text("CARD TYPE: VISACARD\n");
$printer -> setFont();
$printer -> setFont(Printer::FONT_A);
$printer -> setTextSize(1, 1);
$printer -> text("************");
$printer -> setFont();
$printer -> setFont(Printer::FONT_B);
$printer -> setTextSize(1, 2);
$printer -> text("8200-DIP\n");
$printer -> text("SUHERMAN\n");
$printer -> setFont();
$printer -> selectPrintMode(49);
$printer -> setTextSize(3, 2);
$printer -> text("SALE\n");
$printer -> selectPrintMode();
$printer -> setFont(Printer::FONT_B);
$printer -> setTextSize(1, 2);
$printer -> text("BATCH: 00256               TRACE NO: 02319\n");
$printer -> text("DATE: 21 NOV 2017           TIME: 13:32:15\n");
$printer -> text("REF NO: 727813            APPR CODE: 00235\n\n");
$printer -> text("TC : 2B5F506354A6S77\n\n");
$printer -> setFont();

//$printer -> setTextSize(2, 1);
$printer -> selectPrintMode(49);
$printer -> setTextSize(2, 2);
$printer -> text("TOTAL   Rp 30,000,000\n");
$printer -> selectPrintMode();

/* Footer */
$printer -> feed();
$printer -> setFont(Printer::FONT_B);
$printer -> setTextSize(1, 2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("NO SIGNATURE REQUIRED\n");
$printer -> setTextSize(1,1);
$printer -> text("*** ");
$printer -> setTextSize(1,2);
$printer -> text("PIN VERIFICATION SUCCESS");
$printer -> setTextSize(1,1);
$printer ->text(" ***\n");
$printer -> setTextSize(1,2);
$printer ->text("I AGREE TO PAY ABOVE TOTAL AMOUNT\n ACCORDING TO CARD ISSUER AGREEMENT\n\n");
$printer -> setFont(); // Reset
$printer -> feed(2);

/* Cut the receipt and open the cash drawer */
$printer -> cut();
$printer -> pulse();
$printer -> close();
?>
