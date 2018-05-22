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

  public function insert($table,$data){
    $this->connect();
    $campos="";
    $valores="";
    foreach ($data as $key => $campo) {
      $campos.="$key,";
      $valores.="'$campo',";
    }
    // return "INSERT INTO $table (".substr($campos,0,-1).") values (".substr($valores,0,-1).")";
    $rs=$this->sql->query("INSERT INTO $table (".substr($campos,0,-1).") values (".substr($valores,0,-1).")");
    $this->dissconect();
    return $rs;
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
  public function toFirstArray($query){
    if(isset($this->toArray($query)[0])) return $this->toArray($query)[0];
    else return [];
  }
}
?>
