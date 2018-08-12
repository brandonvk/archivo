<?php
include_once("includes/Sql.php");
include_once("File.php");
  class Documento extends Sql{

    public $response;

    public static function addDocumento($data){

        if($id=$this->insert("documento",$data)){
          return $id;
        }else return ["error"=>1,"msg"=>"Error al insertar el documento"];
    }

    public static function editDocumento($data){
      if(isset($data["id_documento"])){

        $idDocumento=$data["id_documento"];

        if($this->update("docuemnto",$data," id_documento='$idDocumento' ")){
          return $idDocumento;
        }else return ["error"=>"2upt","msg"=>"No se pudo actualizar el documento."];
      }else return ["error"=>"1upt","msg"=>"No se agrego el identificador del documento."];
    }
    public static function deleteDocumento($idDocumento){
        if($this->update("docuemnto",["isDelete"=>1]," id_documento=$idDocumento")){
          return $idDocumento;
        }else return ["error"=>"1del","msg"=>"No se agrego identificador de documento"];
    }

  }
  $Documento = (new Documento($_POST))->response;
?>
