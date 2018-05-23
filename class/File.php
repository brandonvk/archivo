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
      if(isset($file["error"])&&!$file["error"]){
        if(!in_array($this->getExtension($file["name"]),$this->list_black_extension)){
          if(!is_dir($path)){
            $res[] = $this->createPath($path);
          }
        }else $res["error"][]=["name"=>"El archivo que intento subir no esta permitido por el sistema {$file["name"]}."];
      }else $res["error"][]=["name"=>"Ocurrio un error al subir el archivo {$file["name"]}."];
    }
    return $res;
  }

  public function getExtension($name_file){
    $name_explode=explode($name_file,".");
    return strtolower($name_explode[count($name_explode)-1]);
  }

  public function createPath($dir){

    $dir_hard = "$this->path";

    $subdirs = substr($dir,strlen($this->path));

    foreach (explode("/",$subdirs) as $dir) {
      if(!is_dir($dir_hard="{$dir_hard}{$dir}")) mkdir($dir_hard);
    }
    return $dir_hard;
  }
}
?>
