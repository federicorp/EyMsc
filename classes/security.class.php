<?php
/**
 * @name Clase de seguridad
 * @package classPHP
 * @author Federicorp
 * @copyright 2013 Federicorp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License  
 * 
 */

class security{
    
    
    public function clean_globals($exceptions=array()){
        if ( !ini_get( 'register_globals' ) )
        return;

        if ( isset( $_REQUEST['GLOBALS'] ) )
            die( 'GLOBALS overwrite attempt detected' );
    
        // Variables that shouldn't be unset
        $no_unset = array( 'GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', 'table_prefix' );
    
        $input = array_merge( $_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset( $_SESSION ) && is_array( $_SESSION ) ? $_SESSION : array() );
        foreach ( $input as $k => $v ) {
            if ( !in_array( $k, $no_unset ) && isset( $GLOBALS[$k] ) ) {
                $GLOBALS[$k] = null;
                unset( $GLOBALS[$k] );
            }
        }
    }
    
    /** 
      * Funcion para preparar el encriptado de la contraseña.
      * Posibles valores para el $mode: 'strrev','normal','no-salt','md5-md5'
      * Predeterminado 'strrev'.
      * 
      *  @param string, mode
      *  @return crypt() del string
      */
     
     public function prep_encr($string,$mode="strrev"){
         if($mode=="strrev")
             return crypt($string, strrev($string));
         if($mode=="normal")
            return crypt($string, $string);
         if($mode=="no-salt")
            return crypt($string);
         if($mode=="md5-md5")
             return md5(md5($string));
         if($mode=="md5-strrev")
            return md5(crypt($string, strrev($string)));
         if($mode=="strrev-md5"){
             $md5 = md5($string);
             return crypt($md5, strrev($md5));
         }
     }
    
    
}


?>