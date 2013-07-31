<?php
/**   Archivo de conexión a la base de datos
  *   @author Federicorp
  *   @package classPHP
  *   @copyright 2013 Federicorp
  *   @license http://opensource.org/licenses/gpl-license.php GNU Public License
  *   Antes de tocar este archivo, asegurece de saber los conceptos de Base de Datos y su conexión.
  *   
  */

class funciones_generales{
    
    /* -------------------- Security.class ----------------------------------*/
    protected $security = NULL;
    
    
    public function __construct(){
        require_once dirname((__FILE__)).'/security.class.php';
        $this->security = new security();
    }
    
    /*---------------------  Subida de Imágenes -----------------------------*/
    public $carpeta_imagenes = NULL;
    private $max_size_imagenes = 200000;
    private $error_imagenes = "";
    private $nombre_archivo_subido = "";
    public function cargar_imagen($imagen,$nombre=""){
        if($this->carpeta_imagenes!=NULL){
            $filename = trim($imagen['name']);
            $extension = explode(".", $filename);
            $filesize = $imagen['size'];
            if(!file_exists($this->carpeta_imagenes)){
                mkdir($this->carpeta_imagenes);
            }
            if($filesize > $this->max_size_imagenes){
                $this->error_imagenes="Imagen muy grande.";
                return false;
            }
            if($filesize<1){
                $this->error_imagenes="Imagen muy pequeña.";
                return false;
            }
            $pattern = array("/jpg/","/png/","/jpeg/");
            $b=0;
            foreach($pattern as $val){
                if(preg_match($val, strtolower($extension[count($extension)-1]))){
                    $b=1;
                }
            }
            if($b==0){
                $this->error_imagenes="Extensión de imagen no válida.";
                return false;
            }
            $nuevo_nombre_imagen = "";
            if($nombre==""){
                $nuevo_nombre_imagen = $this->security->prep_encr(date("d-j-Y"),"md5-md5");
            }else{
                $nuevo_nombre_imagen = $this->security->prep_encr($nombre,"md5-md5");
            }
            if(move_uploaded_file($imagen['tmp_name'], $this->carpeta_imagenes.$nuevo_nombre_imagen.".".strtolower($extension[count($extension)-1]))){
                $this->nombre_archivo_subido = $nuevo_nombre_imagen.".".strtolower($extension[count($extension)-1]); 
                return true;
            }else{
                $this->error_imagenes="No se ha podido mover el archivo temporal.";
                return false;
            }
        }else{
            echo "No se ha especificado la carpeta donde subir la imagen.";
        }
        
    }
    
    public function set_max_size_image($size){
        $this->max_size_imagenes = $size;
    }
    
    public function get_error_upload(){
        return $this->error_imagenes;
    }
    
    public function get_nombre_uploaded(){
        return $this->nombre_archivo_subido;
    }
    

/* -----------------------------------------------------------------------------*/
    
    public function seted_and_post(){
        $args = func_get_args();
        $c=0;//Contador para saber si se envió el array
        foreach ($args as $k) {
            $c++;//Incrementa contador
            if(!isset($_POST[$k])){
                return false;//Si hay una variable que no esta seteada, retorna directamente false
            }
        }
        if($c==0){
            return false;
        }else{
            return true;   
        }
    }
    
    /**
     * Muestra un error formateado en HTML
     */
    
    public static function show_error_html($error,$fatal=false){
        if($fatal==true){
            if(!headers_sent())
                die('<html lang="es"><head><meta charset="utf-8" /></head><body><div style="margin: 0 auto;max-width: 500px;background: #D8A4A4;text-align: center;padding: 10px;-webkit-border-radius: 10px;border-radius: 10px;-webkit-box-shadow: 1px 1px 4px 1px rgba(0, 0, 0, 0.8);box-shadow: 1px 1px 3px 0px rgba(0, 0, 0, 0.8);border: solid thin #5F5F5F;">'.$error.'</div></body></html>');
            die('<div style="margin: 0 auto;max-width: 500px;background: #D8A4A4;text-align: center;padding: 10px;-webkit-border-radius: 10px;border-radius: 10px;-webkit-box-shadow: 1px 1px 4px 1px rgba(0, 0, 0, 0.8);box-shadow: 1px 1px 3px 0px rgba(0, 0, 0, 0.8);border: solid thin #5F5F5F;">'.$error.'</div>');
        }else{
            echo "<div style='margin: 0 auto;max-width: 500px;background: #C1E0FF;text-align: center;padding: 10px;-webkit-border-radius: 10px;border-radius: 10px;-webkit-box-shadow: 1px 1px 4px 1px rgba(0, 0, 0, 0.8);box-shadow: 1px 1px 3px 0px rgba(0, 0, 0, 0.8);border: solid thin #5F5F5F;'>".$error."</div>";
        }
    }
    
    public function post($id){
        return strip_tags($_POST[$id]);
    }
    
    public function get($id){
        return strip_tags($_GET[$id]);
    }
    
    public function redirect($dir){
        if(!headers_sent()){
            header("Location: ".$dir);
            exit();
        }else{
            echo "<script type='text/javascript'> location.href='".$dir."'</script>";
            exit();
        }
    }
    
};
