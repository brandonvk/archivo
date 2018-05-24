<?php
include_once("includes/Sql.php");
include_once("File.php");
  class Turno extends Sql{

    public $response;

    public $limit_by_page=10;

    public function __construct($params){
      $this->response = ["success"=>0,"error"=>"ocurrio un error inesperado"];
      // return $this->response = ["success"=>0,"error"=>"ocurrio un error inneserado","params"=>$params];
      if(!isset($params["action"])) $this->response = ["error"=>"no se encontro accion",
        //'params'=>[$_GET,$_POST,$_REQUEST,$_FILES],
        'success'=>false];
      else{
        switch ($params["action"]) {
          case 'getRows':
            if(!isset($params['page'])) $this->response = ["error"=>"no se encontro página",'success'=>false];
            $this->get($params['page'],isset($params['search'])?$params['search']:"");
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
      }else if(isset($documento["error"])) $this->response = ["success"=>0,"err"=>(isset($documento["error"])?$documento["error"]:$this->response)];
      $params["fecha_entrega"]="{$params["aaaa_entrega"]}-{$params["mm_entrega"]}-{$params["dd_entrega"]}";
      unset($params["aaaa_entrega"]);
      unset($params["mm_entrega"]);
      unset($params["dd_entrega"]);

      $params["fecha_documento"]="{$params["aaaa_documento"]}-{$params["mm_documento"]}-{$params["dd_documento"]}";
      unset($params["aaaa_documento"]);
      unset($params["mm_documento"]);
      unset($params["dd_documento"]);
        // $this->response = $params;
        if($this->insert("turno",$params)){
          $this->response = ["success"=>1,"msg"=>"Se inserto correctamente el registro."];
        }else $this->response = ["success"=>0,"err"=>"Ocurrio un error al insertar el registro ."];


    }

    public function editTurno($params){
      if(isset($params["id_turno"])){

        if($_FILES &&($documento = $this->upload_file($_FILES,"turno/"))&&!isset($documento["error"])){
          $params["documento"]=array_pop($documento["success"])["src"];
        }

        $params["fecha_entrega"]="{$params["aaaa_entrega"]}-{$params["mm_entrega"]}-{$params["dd_entrega"]}";
        unset($params["aaaa_entrega"]);
        unset($params["mm_entrega"]);
        unset($params["dd_entrega"]);

        $params["fecha_documento"]="{$params["aaaa_documento"]}-{$params["mm_documento"]}-{$params["dd_documento"]}";
        unset($params["aaaa_documento"]);
        unset($params["mm_documento"]);
        unset($params["dd_documento"]);
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
    public function get($page,$search){

      $limit_rows=($this->limit_by_page*$page)-$this->limit_by_page;
      $filter="";
      if($search=preg_replace('/"\'\&\|/i','',$search)){
        $filter=" && (entrega like '%$search%' || tipo_documento like '%$search%' || turnado like '%$search%')";
      }
      $maxlength=$this->toFirstArray("SELECT count(*) as total FROM turno WHERE isDelete=0 $filter;")["total"];

      $rows=$this->toArray("SELECT id_turno,fecha_entrega,entrega,tipo_documento,descripcion,turnado,documento,fecha_documento
        FROM turno WHERE isDelete=0 $filter LIMIT $limit_rows,{$this->limit_by_page};");
      $this->response = [
        "success"=>1,
        "rows"=>$rows,
        "maxlength"=>(int)$maxlength,"filter"=>$filter];
    }
    public function upload_file($files,$dir){
      return (new File())->upload_file($files,$dir);
    }
  }
  $Turno = (new Turno($_POST))->response;
  echo json_encode($Turno);
?>
