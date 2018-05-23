<?php
include_once("includes/Sql.php");
include_once("File.php");
  class Turno extends Sql{

    public $response;

    public function __construct($params){
      $this->response = ["success"=>0,"error"=>"ocurrio un error inneserado"];
      if(!isset($params["action"])) $this->response = ["error"=>"no se encontro accion",
        //'params'=>[$_GET,$_POST,$_REQUEST,$_FILES],
        'success'=>false];
      else{
        switch ($params["action"]) {
          case 'getRows':
            if(!isset($params['page'])) $this->response = ["error"=>"no se encontro página",'success'=>false];
            $this->get($params['page']);
          break;
          case 'add':
            unset($params["action"]);
            $this->addTurno($params);
          break;
          case 'edit':
            unset($params["action"]);
            $this->editTurno($params);
          break;
          case 'delete':
            unset($params["action"]);
            $this->deleteTurno($params);
          break;
          default:
            $this->response = ["error"=>"acción no seteada",'success'=>false];
            break;
        }
      }
    }
    public function addTurno($params){
      // return $this->response = [$params,$_FILES];
      $documento = $this->upload_file($_FILES,"turno/");
      if(!isset($documento["error"])){
        $params["documento"]=array_pop($documento["success"])["src"];
        $params["fecha_entrega"]="{$params["aaaa"]}-{$params["mm"]}-{$params["dd"]}";
        unset($params["aaaa"]);
        unset($params["mm"]);
        unset($params["dd"]);
        // $this->response = $params;
        if($this->insert("turno",$params)){
          $this->response = ["success"=>1,"msg"=>"Se inserto correctamente el registro."];
        }else $this->response = ["success"=>0,"err"=>"Ocurrio un error al insertar el registro ."];
      }else $this->response = ["success"=>0,"err"=>(isset($documento["error"])?$documento["error"]:$this->response)];

    }

    public function editTurno($params){
      if(isset($params["id_turno"])){
        $params["fecha_entrega"]="{$params["aaaa"]}-{$params["mm"]}-{$params["dd"]}";
        unset($params["aaaa"]);
        unset($params["mm"]);
        unset($params["dd"]);
        $idTurno=$params["id_turno"];
        unset($params["id_turno"]);

        if($this->update("turno",$params," id_turno=$idTurno ")){
          $this->response=["success"=>1,"msg"=>"Se actualizo correctamente el registro."];
        }else $this->response=["success"=>0,"err"=>"Ocurrio un error al actualizar el registro."];
      }else $this->response=["success"=>0,"err"=>"No se agrego identificador de turno."];
    }
    public function deleteTurno($params){
      if(isset($params["id"])){
        $idTurno=$params["id"];
        unset($params["id"]);

        if($this->update("turno",["isDelete"=>1]," id_turno=$idTurno ")){
          $this->response=["success"=>1,"msg"=>"Se elimino correctamente el registro."];
        }else $this->response=["success"=>0,"err"=>"Ocurrio un error al eliminar el registro."];
      }else $this->response=["success"=>0,"err"=>"No se agrego identificador de turno."];
    }
    public function get($page){
      $maxlength=$this->toFirstArray("SELECT count(*) as total FROM turno;")["total"];
      // $rows=$this->toArray("SELECT * FROM turno;");
      // $rows=$this->toArray("SELECT entrega as 'Persona que entrego',
      //   tipo_documento as 'Tipo de documento',
      //   fecha_entrega as 'Fecha de entrega',
      //   descripcion as 'Descripción'
      //   FROM turno WHERE isDelete=0;");
      $rows=$this->toArray("SELECT id_turno,fecha_entrega,entrega,tipo_documento,descripcion,turnado
        FROM turno WHERE isDelete=0;");
      $this->response = [
        "success"=>1,
        "rows"=>$rows,
        "maxlength"=>(int)$maxlength];
    }
    public function upload_file($files,$dir){
      return (new File())->upload_file($files,$dir);
    }
  }
  $Turno = (new Turno($_POST))->response;
  echo json_encode($Turno);
?>
