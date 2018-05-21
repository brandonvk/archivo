<?php
include_once("Credentials.php");
class Sql extends Credentials{
  private $sql;

  public function connect(){
    $this->sql = new mysqli($this->host,$this->user,$this->pswd,$this->bd);
  }

  public function dissconect(){
    $this->sql->close();
  }

  public function toArray($query){
    $this->connect();
    $rows=$this->sql->query($query);

    $result = [];
    while($row = $rows->fetch_assoc()){
      $result[]=$row;
    }
    $this->dissconect();
    return $result;
  }
}
?>
