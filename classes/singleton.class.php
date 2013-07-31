<?php
/**
 * @name Clase Singleton
 * @package classPHP
 * @author Federicorp
 * @copyright 2013 Federicorp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License  
 * 
 */
 
 class singleton{
     public static function getInstance()
     {
          if (  !self::$instancia instanceof self)
          {
             self::$instancia = new self;
          }
          return self::$instancia;
     }
     public function __clone()
       {
          trigger_error("Operación Invalida: No puedes clonar una instancia de ". get_class($this) ." class.", E_USER_ERROR );
       }
       public function __wakeup()
       {
          trigger_error("No puedes deserializar una instancia de ". get_class($this) ." class.");
       }
 }
 
 ?>
