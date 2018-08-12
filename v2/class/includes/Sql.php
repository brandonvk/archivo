<?php
include_once("Credentials.php");
class Sql extends Credentials{
  private $sql;

  protected $getSql='';

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
    $rs=$this->sql->query($this->getSql="INSERT INTO $table (".substr($campos,0,-1).") values (".substr($valores,0,-1).")");
    $id=$this->sql->insert_id;
    $this->dissconect();
    return $id;
  }

  public function update($table,$data,$filter_id){
    $this->connect();
    $campos=[];
    $valores="";
    foreach ($data as $key => $campo) {
      $campos[]="$key='$campo'";
    }
    // return "UPDATE $table SET ".implode($campos,",")." WHERE $filter_id;";
    $rs=$this->sql->query($this->getSql="UPDATE $table SET ".implode($campos,",")." WHERE $filter_id;");
    $this->dissconect();
    return $rs;
  }
  public function toArray($query){
    $this->connect();
    $rows=$this->sql->query($this->getSql=$query);

    $result = [];
    if($rows){
      while($row = $rows->fetch_assoc()){
        $result[]=$row;
      }
      $this->dissconect();
    }
    return $result;
  }
  public function toFirstArray($query){
    if(isset($this->toArray($query)[0])) return $this->toArray($query)[0];
    else return [];
  }
}
?>
