<?php
/**
 * Created by PhpStorm.
 * User: yudadp
 * Date: 19/06/2023
 * Time: 08:47
 */

include_once 'config/app.php';
include_once 'config/api.php';
include_once 'config/func.php';

include_once 'lib/Request.php';
include_once 'lib/escpos_1.4/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$response = METHOD == 'api' ? Request::get(PRINT_SALES) : file_get_contents('php://input');

$data = json_decode($response, true);
//print_r($data);

$status = $data['status'];
$errno  = $data['code'];

if (!$status && $errno != 404) {

  $printer_name = get_receipt_printer();
  $products     = $data['detail'];
  $payment_info = $data['detail_payment'];

  $trx_date  = $data['tgl_transaksi'];
  $inv_date  = $data['tgl_invoice'];
  $inv_no    = $data['no_transaksi'];
  $type      = $data['type'];
  $customer  = $data['pelanggan'];
  $addrs     = $data['alamat'];
  $cif       = $data['no_cif'];
  $tlp       = $data['no_tlp'];
  $dealer_nm = $data['dealer_name'];
  $payment   = $data['payment'];
  $servedby  = $data['served_by'];
  $totalp    = $data['total'];


  $lbl_servedBy = formatText('('.$servedby.')', 'center',15);

  $biayaKirim  = $data['biaya_pengiriman'];
  $notes       = $data['notes'];
  $logo        = $data['logo'];


  $line1 = '.................................';
  $line2 = '_________________________________';
  $line3 = '---------------------------------';

  /*if(empty($inv_no) || empty($customer) || empty($trx_date)) {

    echo json_encode(array('retval'=> '404', 'trxid' => 'No data available'));

  } else {*/

    $connector    = new WindowsPrintConnector($printer_name->title);
    $printer = new Printer($connector);

    $printer->setJustification(Printer::JUSTIFY_CENTER);
    //$printer -> bitImage($img);
    //$printer -> feed(2);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
    $printer->text(strtoupper("\n $type") . "\n");
    $printer -> selectPrintMode();
    $printer -> selectPrintMode(Printer::MODE_FONT_B);
    $printer->text("PT. Sari Arthagriha Valasindo Ekatama\n");
    $printer->text("Danau Sunter Utara F20 No. 24 Jakut\n");
    $printer->text("021-29615678 - 087774568833 \n");
    $printer->text("www.savemoneychanger.com \n");
    $printer->text("$line1\n");
    $printer->feed();

    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer -> selectPrintMode();
    $printer->text(sprintf('%-15.15s %1s %14s', "No. Transaksi", ":", formatText($inv_no,'left',14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "Tgl. Invoice", ":", formatText($inv_date,'left',14) ."\n"));
    //$printer->text(sprintf('%-15.15s %1s %14s', "Tgl. Transaksi", ":", formatText($inv_date,'left',14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "Pelanggan", ":", formatText($customer,'left',14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "No. Cif", ":", formatText($cif,'left',14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "No. Telepon", ":", formatText($tlp,'left',14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "Alamat", ":", formatText($addrs,'left', 14) ."\n"));
    $printer->text(sprintf('%-15.15s %1s %14s', "Catatan", ":", formatText($notes, 'left', 14) ."\n"));
    //$printer->text(sprintf('%-15.15s %2s %13s', "Alamat", ":", '' ."\n"));
    //$printer -> text("$addrs\n");
    //$printer->text(sprintf('%-15.15s %1s %14s', "Catatan", ":", '' ."\n"));
    //$printer -> text("$notes\n\n");

    $printer -> text("Detail Transaksi\n");
    $printer->text("$line3\n");
    //$printer->text(sprintf('%-2.2s %8s %6s %5s %5s', 'No', 'Currency', 'Amount', 'Rate', 'Total'));
    $printer->text(sprintf('%-8.8s %6s %5s %8s', 'Currency', 'Amount', 'Rate', '   Total'));
    $printer->text("\n");
    $printer->text("$line3");

    $no = 1;
    foreach ($products as $item) {
      $printer->text("\n");
      if(strlen(number_format($item['total'])) > 10) {
        $printer->text(sprintf('%-3.3s %7s %5s %13s', formatText($item['currency'],'center',3), number_format($item['amount']), number_format($item['rate']), formatText(number_format($item['total']),'right',13)));
      } else {
        $printer->text(sprintf('%-6.6s %7s %5s %10s', '  ' . $item['currency'], number_format($item['amount']), number_format($item['rate']), formatText(number_format($item['total']),'right',10)));
      }
      $printer->text("\n");
      $printer->text("$line3");
      $no++;
    }
    $printer->text("\n");
    $printer -> setEmphasis(true);
    $printer->text(sprintf('%-15.15s %2s %13s', formatText('Total Pembelian','center', 15), 'Rp', formatText(number_format($totalp),'center',13)));
    $printer -> setEmphasis(false);
    $printer->text("\n");
    $printer->text("$line3");
    //$printer->text("$line2\n\n");

    $printer->text("\n\n");
    $printer->text("Dealer Name : $dealer_nm\n");

    foreach ($payment_info as $p) {
      $printer->text("Payment : $p[payment]\n");
      $printer->text($p['bank'] ." $p[no_rek]\n");
      $printer->text(number_format($p['jumlah']) ."\n");
    }

    $printer->text("\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text("Saya menyatakan bahwa transaksi ini \n");
    $printer->text("ini BELUM MELEBIHI threshold dalam bulan ini & akan menyertakan underlying yang sebelumnya JIKA MELEBIHI.");
    $printer->text("\n\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text(sprintf('%-15.15s %15s', '    Served By:', 'Customer    '));
    $printer->text("\n\n\n\n\n");
    $printer->text(sprintf('%-15.15s %15s', $lbl_servedBy, '(.............)'));
    $printer->text("\n\n");
    $printer->text("Perhatian / Note \n");
    //$printer->text("Note\n");
    $printer -> selectPrintMode(Printer::MODE_FONT_B);
    $printer->text("Kekurangan penerimaan uang tidak ditanggung setelah meninggalkan loket/kasir.\n");
    $printer->text("Dokumen pendukung yang diberikan kepada Kami untuk kepentingan pembelian valas adalah asli dan benar adanya.\n");
    $printer -> setEmphasis(true);
    $printer->text("\nDeficiancies in cash receipts are not covered after leaving the counter/cashier.\n");
    $printer->text("Supporting documents provided to us for the purpose of purchasing foreign exchange are original and true.\n");
    $printer -> setEmphasis(false);
    $printer -> setEmphasis(false);
    $printer->feed();
    $printer->cut();
    $printer->pulse();
    $printer->close();

    if($printer) {
      echo json_encode(array('retval'=> '200', 'trxid' => $inv_no));
    }
  //}
} else {
  echo json_encode(array('retval'=> '400'));
}

if(METHOD == 'api') {
    echo '<script type="text/javascript">
    setTimeout(function(){
        location.reload();
    }, '. RELOAD_INTERVAL. ');
</script>';
}
?>

