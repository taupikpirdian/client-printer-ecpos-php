<?php
session_start();
define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DATABASE", "print_server");
define("PORT", 3306);

$mysqli = new mysqli (HOST, USER, PASSWORD, DATABASE, PORT);

if ($mysqli->connect_error) {
  trigger_error('Error connection: ' . $mysqli->connect_error, E_USER_ERROR);
}


class my_db_connect {

  private $host;
  private $user;
  private $password;
  private $db;
  private $conn;

  public function __construct($params = []) {
    $this->host     = HOST;
    $this->user     = USER;
    $this->password = PASSWORD;
    $this->db       = DATABASE;

    $this->conn = $mysqli = new mysqli($this->host, $this->user, $this->password, $this->db, 3306);

    if ($mysqli->connect_error) {
      trigger_error('Error connection: ' . $mysqli->connect_error, E_USER_ERROR);
    }
  }

  public function connect() {
    return $this->conn;
  }

  public function dissconnect() {
    return $this->dissconnect();
  }
}
?>