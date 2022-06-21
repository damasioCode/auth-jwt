<?php

namespace App\Database;

use PDO;

class Connection
{
  private static $conn;

  public static function getConn()
  {
    if(!self::$conn){
      self::$conn = new PDO('mysql:host=localhost;dbname=jwt-php', 'root', 'root');
    }
    return self::$conn;
  }
}