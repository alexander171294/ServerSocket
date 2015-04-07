<?php namespace PHPServerSocket;

// CLASS EXISTS?

require('libs/SocketListener.php');
require('libs/SocketClient.php');

class ServerManager
{

    static protected $mainSocket = null;
    
    static protected $clients = array();
    
    static protected $newClient = null;
    
    static public function start($localIP = '127.0.0.1', $port = '2026')
    {
        // create a new socket
    		self::$mainSock = new SocketListener($localIP, $port);
    
    		self::$mainSock->listen(); 
  
        ///////////////////////////////////////////////////////////////////////// avisar que estamos a la escucha aqu�
    
    		self::AddNewClient();
    
    		while(true)
    		{
    			self::$mainSock->refreshListen(self::$NewClient); // detect new clients
    			// refresh clients
    			for($i=0; $i<count(self::$clients); $i++)
    			{
    				if(isset($clients[$i])) self::$clients[$i]->refresh();
    			}
    		}
    }
    
    // esta funcion debe ser reescrita por la persona que use la clase
    abstract static public function AddNewClient();
    /*
    ej:
  	{
        // agregamos nuevo cliente para que en el bucle se use uno nuevo
        // tiene que estar basado en la clase SocketClient
  	    self::_AddNewClient(new SocketClient());
  	}*/
    
    static public function _AddNewClient(SocketClient $obj)
    {
        self::$newClient = $obj;
    }
    
    // agregar el nuevo cliente
    static public function AddClient($sock)
  	{
      $vacio = false;
      // revisamos que no haya un casillero vac�o
      for($i = 0; $i<count(self::$clients); $i++)
      {
          if(!isset(self::$clients[$i]))
          { // usamos el espacio vac�o
              $vacio = true;
              self::$clients[$i] = $sock; 
              $sock->SocketEventReceptor->id = $i;
          }
      }
      // si no usamos un espacio vac�o agregamos uno
  		if(!$vacio)
      {
          $sock->SocketEventReceptor->id = count(self::$clients); // add te id
		      self::$clients[count(self::$clients)] = $sock;
      }
  	}
    
    static public function DeleteClient($id) // eliminamos el cliente
  	{
  		unset(self::$clients[$id]);
  	}
    
    // enviar mensajes a todos los clientes
    static public function SendToAll($message) // enviamos un mensaje a todos los clientes
  	{
  		for($i=0; $i<count(self::$clients); $i++)
  		{
        if(!isset(self::$clients[$i]))
        {
  			   self::$clients[$i]->send($message);
        }
  		}	
  	}
    
    // enviar mensaje a un cliente usando su id
    static public function SendTo($id, $message) // enviamos un mensaje a un cliente en particular
    {
      if(!isset(self::$clients[$id]))
      {
  			self::$clients[$id]->send($message);
      }
    }

}