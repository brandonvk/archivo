<?php
class File{

public $path = "";

  public $root = "data/";

  public $list_black_extension = ["php","js","html","phtml","htaccess","ini","php3"];

  public function __construct(){

    $this->path = "{$_SERVER['DOCUMENT_ROOT']}/archivo/";

  }

  public function upload_file($files,$url=""){
    $path  = "{$this->path}{$this->root}{$url}";
    $res=[];
    while($file=array_pop($files)){
      // $res[]=gettype($file);
      if(isset($file["error"])&&!$file["error"]){
        if(!in_array($ext=$this->getExtension($file["name"]),$this->list_black_extension)){
          if(!is_dir($path)){
            $this->createPath($path);
          }
          // $res[]=[$file["tmp_name"],$file_name="{$path}".rand(1,9999).".$ext",move_uploaded_file($file["tmp_name"],$file_name="{$path}".rand(1,9999).".$ext")];
          if($file_success=$this->move_file($file["tmp_name"],$file_name="{$path}".rand(1,9999).".$ext")){
            $res["success"][]=["name"=>"El archivo se subio con éxito.","src"=>substr($file_name,strlen($this->path))];
        }else $res["error"][]=["name"=>"Ocurrio un erro al intentar subir el archivo F(300)."];
      }else $res["error"][]=["name"=>"El archivo que intento subir no esta permitido por el sistema {$file["name"]} F(200)","ext"=>$ext];
    }else if($file["name"]) $res["error"][]=["name"=>"Ocurrio un error al subir el archivo {$file["name"]} F(100)."];
    }
    return $res;
  }

  public function getExtension($name_file){
    $name_explode=explode(".",$name_file);
    return strtolower($name_explode[count($name_explode)-1]);
  }

  public function createPath($dir){

    $dir_str = "$this->path";

    $subdirs = substr($dir,strlen($dir_str));

    foreach (explode("/",$subdirs) as $dir) {

      if($dir&&!is_dir($dir_str="{$dir_str}{$dir}/")) mkdir($dir_str);

    }

    return $dir_str;
  }

  public function move_file($file,$dir){

    return move_uploaded_file($file,$dir);

  }

}
?>
