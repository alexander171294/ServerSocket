<?php

// requerimos PHPSocketMaster
require('../../PHPSocketMaster/iSocketMaster.php');

// requerimos ServerManager
require('../../PHPSocketServer/ServerManager.php');

// creamos nuestro cliente y lo que va a realizar
class UnCliente extends SocketClient
{
    
    // cuando ocurra un error en nuestro cliente se ejecutara esta opcion
    public function onError()
    {
        // enviamos un mensaje a la consola para avisar que hubo un error en un cliente
        echo 'OOps error en un cliente';
    }
    
    // esto se ejecutara cuando se realice la conexion satisfactoria de un nuevo cliente
    public function onConnect()
    {
        echo 'Se conecto un cliente';
    }
    
    // esta funcion se ejecutara cuando un cliente nos env�e un mensaje
    public function onReceiveMessage($message)
    {
        echo 'El servidor recibio el siguiente mensaje: '.$message;
    }
    
    /* cuando nosotros queremos enviar un mensaje desde el servidor a este cliente,
     esta funcion se ejecutara
    y nos permitira cancelarlo cambiando el valor de $cancel a false
    si no cambiamos el valor de cancel el mensaje sera enviado 
    */
    public function onSendRequest(&$cancel, $message){}
    
    /* Esta funcion se ejecutara cuando nuestro servidor envie un mensaje a este 
    cliente satisfactoriamente
    */
    public function onSendComplete($message){}
    
}

// creamos mi administrador de sockets
class miAdministrador extends PHPServerSocket/ServerManager
{
    // creamos nuestra funcion que agrega clientes al servidor (para cuando nos llege una conexion)
   static public function AddNewClient()
   {
      // agregamos el cliente usando una instancia de nuestra clase de cliente que creamos arriba
      self::_AddNewClient(new UnCliente());
   }
}

// iniciamos el servidor indicando ip local o ip local de la red, y el puerto en el cual vamos a esperar conexiones
miAdministrador::start('127.0.0.1', '2246');