<?php
include_once("includes/Sql.php");
include_once("File.php");
  class Contenedor extends Sql{

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
            $this->addContenedor($params);
          break;
          case 'edit':
            unset($params["action"]);
            $this->editContenedor($params);
          break;
          case 'delete':
            unset($params["action"]);
            $this->deleteContenedor($params);
          break;
          default:
            $this->response = ["error"=>"acción no seteada",'success'=>false];
            break;
        }
      }
    }
    public function addContenedor($params){
      // return $this->response = [$params,$_FILES];
      // $documento = $this->upload_file($_FILES,"turno/");
      // if(!isset($documento["error"])){
      //   $params["documento"]=array_pop($documento["success"])["src"];
      // }else if(isset($documento["error"])) return $this->response = ["success"=>0,"err"=>(isset($documento["error"])?$documento["error"]:$this->response)];

        // $this->response = $params;
        if($this->insert("contenedor",$params)){
          $this->response = ["success"=>1,"msg"=>"Se inserto correctamente el registro."];
        }else $this->response = ["success"=>0,"err"=>"Ocurrio un error al insertar el registro .","data"=>$params];


    }

    public function editContenedor($params){
      if(isset($params["id_contenedor"])){

        $idContenedor=$params["id_contenedor"];
        unset($params["id_contenedor"]);

        if($this->update("contenedor",$params," id_contenedor=$idContenedor ")){
          $this->response=["success"=>1,"msg"=>"Se actualizo correctamente el registro."];
        }else $this->response=["success"=>0,"err"=>"Ocurrio un error al actualizar el registro.","data"=>$params];
      }else $this->response=["success"=>0,"err"=>"No se agrego identificador de la contenedor."];
    }
    public function deleteContenedor($params){
      if(isset($params["id"])){
        $idContenedor=$params["id"];
        unset($params["id"]);

        if($this->update("contenedor",["isDelete"=>1]," id_contenedor=$idContenedor ")){
          $this->response=["success"=>1,"msg"=>"Se elimino correctamente el registro."];
        }else $this->response=["success"=>0,"err"=>"Ocurrio un error al eliminar el registro."];
      }else $this->response=["success"=>0,"err"=>"No se agrego identificador de la dirección."];
    }
    public function get($page,$search){

      $limit_rows=($this->limit_by_page*$page)-$this->limit_by_page;
      $filter="";
      if($search=trim(preg_replace('/"|\'|\&|\||(AND)|(OR)/i','',$search))){
        $filter=" && (identificador like '%$search%' || tipo_contenedor like '%$search%')";
      }
      $maxlength=$this->toFirstArray("SELECT count(*) as total FROM contenedor WHERE isDelete=0 $filter;")["total"];

      $rows=$this->toArray("SELECT id_contenedor,identificador,tipo_contenedor
        FROM contenedor WHERE isDelete=0 $filter LIMIT $limit_rows,{$this->limit_by_page};");
      $this->response = [
        "success"=>1,
        "rows"=>$rows,
        "maxlength"=>(int)$maxlength];
    }

  }
  $Contenedor = (new Contenedor($_POST))->response;
  echo json_encode($Contenedor);
?>
