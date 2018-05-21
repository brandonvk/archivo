<?php
include_once("Credentials.php");
class Sql extends Credentials{
  private $sql;
  public function __construct(){
    print_r([$this->host,$this->user,$this->pswd,$this->bd]);
    $this->sql = new mysqli($this->host,$this->user,$this->pswd,$this->bd);
  }
  public function toArray($query){

    $rows=$this->sql->query($query);

    $result = [];
    while($row = $rows->fetch_assoc()){
      $result[]=$row;
    }
    return $result;
  }
}
?>
