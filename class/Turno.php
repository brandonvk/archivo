<?php
include_once("includes/Sql.php");
  class Turno extends Sql{

    public $response;

    public function __construct($params){
      $this->response = ["success"=>0,"error"=>"ocurrio un error inneserado"];
      if(!isset($params["action"])) $this->response = ["error"=>"no se encontro accion",'success'=>false];
      switch ($params["action"]) {
        case 'getRows':
        if(!isset($params['page'])) $this->response = ["error"=>"no se encontro página",'success'=>false];
          $this->get($params['page']);
          break;

        default:
          $this->response = ["error"=>"acción no seteada",'success'=>false];
          break;
      }
    }
    public function addTurno(){

    }
    public function get($page){
      $maxlength=$this->toArray("SELECT count(*) FROM turno;");
      $rows=$this->toArray("SELECT * FROM turno;");
      $this->response = [
        "success"=>1,
        "rows"=>$rows,
        "maxlength"=>$maxlength];
    }
  }
  $Turno = (new Turno($_POST))->response;
  echo json_encode([$_POST,$Turno]);
?>
