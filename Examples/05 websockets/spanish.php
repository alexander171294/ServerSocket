<?php

// nombre de la clase que gestionará los sockets 
define('SRV_MGR', 'miAdministrador');
// activamos websocket  (es lo unico que hay que hacer para que se soporte websockets)
define('SRV_WSK', true);

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
        //echo 'OOps error en un cliente';
    }
    
    // esto se ejecutara cuando se realice la conexion satisfactoria de un nuevo cliente
    // esto reemplaza a onConnect de PHPSocketMaster
    // ESTO SOLO FUNCIONA CON LA LIBRERÍA ServerSocket
    // si usted solo usa PHPSocketMaster por favor utilice onConnect()
    public function onReady()
    {
        echo 'Se conecto un cliente';
        /* enviaremos un mensaje al cliente que se acaba de conectar, para avisarle que se conectó satisfactoriamente
        usamos $this->id para especificar que enviemos un mensaje a este cliente
        con esta linea estamos usando el enviar mensaje del server manager
        no obstante, PHPSocketMaster de forma nativa soporta que el cliente envíe un mensaje usando
        la función send de la siguiente forma:
        
        $this->getBridge()->send('Mensaje');
        
        La diferencia es que desde el administrador de clientes podemos enviar mensajes desde este cliente
        a otro cliente especificando un id diferente. Con la funcion send de phpsocketmaster no podemos
        enviar a un cliente diferente que no sea este mismo
        recuerde que toda esta clase es instanciada para cada cliente que se conecte.
        Si tuvieramos el id de otro cliente, podemos enviarle a otro cliente un mensaje cuando este cliente se conecte.
        
        puedes obtener todos los clientes conectados actualmente usando la funcion
        getClients() del administrador de sockets:
        
        miAdministrador::getClients();
        
        esto devolverá un array de los clientes conectados, el total de clientes conectados
        se pueden obtener con 
        
        count(miAdministrador::getClients());
        
        y cada id de cada cliente se encuentra en un indice diferente del array retornado por dicha función.
        */
        miAdministrador::SendTo($this->id, 'Conectado al servidor');
    }
    
    // esta funcion se ejecutara cuando un cliente nos envíe un mensaje
    public function onReceiveMessage($message)
    {
        echo 'El servidor recibio el siguiente mensaje: '.$message[0];
        // ahora enviaremos el mensaje que recibimos a todos los clientes conectados al servidor
        // tenga en cuenta que con $this->id obtenemos el id del cliente actual
        miAdministrador::SendToAll('El cliente: '.$this->id.' envio el siguiente mensaje: '.$message[0]);
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
    
    /* dado que el serverManager necesita utilizar el evento onDisconnect para 
        gestionar los socket activos, se creó ésta función para que tu puedas
        utilizarla en una desconexión */ 
    public function _onDisconnect()
    {
        echo 'Me desconecté :( '.$this->id;
    }
    
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
   
   // reescribimos la funcion reporter
   // esta función se ejecutará cuando ocurran eventos en el administrador
   static public function socketReporter($report)
   {
   
      // si el reporte es que el server ejecutó la función listen
      if($report == R_LISTEN)
      {
          // mostramos el reporte en la consola
          echo 'El servidor se encuentra operativo y a la escucha de conexiones entrantes';
      }
      if($report == R_NCLIENT)
      {
          // mostramos el reporte en la consola
          echo 'nuevo cliente';
      }
      if($report == R_DCLIENT)
      {
          // mostramos el reporte en la consola
          echo 'cliente eliminado';
      }
      /**
       * El reporte puede ser una de las siguientes constantes:
       * R_LISTEN cuando el servidor se pone a la escucha
       * R_NCLIENT cuando se crea un nuevo cliente
       * R_DCLIENT cuando se elimina un cliente (por un error, por desconexión o lo que sea)
       */
   }
}

// iniciamos el servidor indicando ip local o ip local de la red, y el puerto en el cual vamos a esperar conexiones
miAdministrador::start('127.0.0.1', '2026');