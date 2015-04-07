<?php

// requerimos PHPSocketMaster
require('../../PHPSocketMaster/iSocketMaster.php');

// requerimos ServerManager
require('../../PHPSocketServer/ServerManager.php');

// creamos nuestro cliente y lo que va a realizar
class UnCliente extends \PHPServerSocket\SocketClient
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
        /* enviaremos un mensaje al cliente que se acaba de conectar, para avisarle que se conect� satisfactoriamente
        usamos $this->id para especificar que enviemos un mensaje a este cliente
        con esta linea estamos usando el enviar mensaje del server manager
        no obstante, PHPSocketMaster de forma nativa soporta que el cliente env�e un mensaje usando
        la funci�n send de la siguiente forma:
        
        $this->getBridge()->send('Mensaje');
        
        La diferencia es que desde el administrador de clientes podemos enviar mensajes desde este cliente
        a otro cliente especificando un id diferente. Con la funcion send de phpsocketmaster no podemos
        enviar a un cliente diferente que no sea este mismo
        recuerde que toda esta clase es instanciada para cada cliente que se conecte.
        Si tuvieramos el id de otro cliente, podemos enviarle a otro cliente un mensaje cuando este cliente se conecte.
        
        puedes obtener todos los clientes conectados actualmente usando la funcion
        getClients() del administrador de sockets:
        
        miAdministrador::getClients();
        
        esto devolver� un array de los clientes conectados, el total de clientes conectados
        se pueden obtener con 
        
        count(miAdministrador::getClients());
        
        y cada id de cada cliente se encuentra en un indice diferente del array retornado por dicha funci�n.
        */
        miAdministrador::SendTo($this->id, 'Conectado al servidor');
    }
    
    // esta funcion se ejecutara cuando un cliente nos env�e un mensaje
    public function onReceiveMessage($message)
    {
        echo 'El servidor recibio el siguiente mensaje: '.$message;
        // ahora enviaremos el mensaje que recibimos a todos los clientes conectados al servidor
        // tenga en cuenta que con $this->id obtenemos el id del cliente actual
        miAdministrador::SendToAll('El cliente: '.$this->id.' envio el siguiente mensaje: '.$message);
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
    
    public function onRefresh() {}
    
}

// creamos mi administrador de sockets
class miAdministrador extends \PHPServerSocket\ServerManager
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