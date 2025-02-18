<?php
include_once 'config/func.php';
include_once 'lib/escpos/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$printer_name = get_receipt_printer();

//$pdf = $_SERVER['DOCUMENT_ROOT'].'/print-server/print/media/pembelian.pdf';

$connector = new WindowsPrintConnector($printer_name->title);
$printer   = new Printer($connector);


try {
  $logo    = EscposImage::load("media/images/logo.png", false);
  $logo50  = EscposImage::load("media/images/logo50.png", false);

  $printer->setJustification(Printer::JUSTIFY_CENTER);
  $printer->text("Test #1 \n");
  $printer->bitImageForEpsonTMU220($logo);
  $printer->text("\nTest #2 \n");
  $printer -> bitImageForEpsonTMU220($logo50, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
  $printer->text("\nTest #3 \n");
  $printer -> bitImageForEpsonTMU220($logo, Printer::IMG_DOUBLE_WIDTH);
  $printer->cut();
}
catch (Exception $e) {
  echo $e->getMessage() . "\n";
} finally {
  $printer->close();
}

?>