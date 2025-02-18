<?php

function read_databsae() {
  $path = __DIR__ . DIRECTORY_SEPARATOR  . 'data.json';
  $file = file_get_contents($path);
  $data = $file ? json_decode($file) : null;
  return empty($data->printers) ? json_decode(['printers' => [], 'order_printers' => [], 'receipt_printer' => ""]) : $data;
}

function get_printers() {
  $data = read_databsae();
  return $data->printers;
}

function get_receipt_printer_id() {
  $data = read_databsae();
  return !empty($data->receipt_printer) ? $data->receipt_printer : '';
}

function get_receipt_printer() {
  $printers = get_printers();
  $receipt_printer = get_receipt_printer_id();

  foreach ($printers as $printer) {
    if ($printer->id == $receipt_printer) {
      return $printer;
    }
  }

  return false;
}

function formatText($text, $textligment='left', $max_lenght=45) {

  $char = strlen($text);
  $selisih = $max_lenght - $char;

  if($char < $max_lenght) {
    if($textligment == 'right') {
      $space = str_repeat(' ', $selisih);
      $text = $space.$text;
    }

    if($textligment == 'left') {
      $space = str_repeat(' ', $selisih);
      $text = $text.$space;
    }

    if($textligment == 'center') {
      $pembagi = (int)($selisih / 2);
      $space = str_repeat(' ', $pembagi);
      $text = $space.$text.$space;
    }
  }

  return $text;
}

function savelogs($txt, $tag="APP_LOGS") {

  //if(ENABLE_LOGS) {
    $date = date('dmY h:i:s');

    if($tag == 'APP_LOGS') {
      $tag = 'APP_LOGS_';
      $fp   = fopen('../logs/'. $tag . date("dmY") .'.log', 'a');
    } else {
      $fp   = fopen('../logs/'. $tag .'.log', 'a');
    }


    $rr = fwrite($fp, $date . ' : ' . $txt . "\r\n");
    fclose($fp);

    return $rr;
  //}
}

?>