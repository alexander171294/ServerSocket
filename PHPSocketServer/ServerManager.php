<?php namespace PHPServerSocket;

define('R_LISTEN', 200);
define('R_NCLIENT', 201);
define('R_DCLIENT', 202);

// CLASS EXISTS?

require('libs/SocketListener.php');
require('libs/SocketClient.php');

abstract class ServerManager
{

    static protected $mainSocket = null;
    
    static protected $clients = array();
    
    static protected $newClient = null;
    
    static protected $initial = true;
    
    final static public function start($localIP = '127.0.0.1', $port = '2026')
    {
        // create a new socket
    		self::$mainSocket = new SocketListener($localIP, $port);
    
    		self::$mainSocket->listen(); 
  
        static::SocketReporter(R_LISTEN);
    
    		static::AddNewClient();
    
    		while(true)
    		{
    			self::$mainSocket->refreshListen(self::$newClient); // detect new clients
    			// refresh clients
          $valores = count(self::$clients);
    			for($i=0; $i<$valores; $i++)
    			{
    				if(isset(self::$clients[$i]))
            {
              self::$clients[$i]->refresh();
            } else $valores++; // como hay uno que no existe, tenemos que seguir uno m�s con el for
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
    
    final static public function _AddNewClient(SocketClient $obj)
    {
        if(self::$initial == false) static::SocketReporter(R_NCLIENT);
        if(self::$initial == true) self::$initial = false;
        self::$newClient = $obj;
    }
    
    // agregar el nuevo cliente
    final static public function AddClient($sock)
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
      $sock->SocketEventReceptor->onReady();
  	}
    
    final static public function DeleteClient($id) // eliminamos el cliente
  	{
      if(isset(self::$clients[$id]))
      {
          static::SocketReporter(R_DCLIENT);
  	      unset(self::$clients[$id]);    
      }
  	}
    
    // enviar mensajes a todos los clientes
    final static public function SendToAll($message) // enviamos un mensaje a todos los clientes
  	{
  		for($i=0; $i<count(self::$clients); $i++)
  		{
        if(isset(self::$clients[$i]))
        {
  			   self::$clients[$i]->send($message);
        }
  		}	
  	}
    
    // enviar mensaje a un cliente usando su id
    final static public function SendTo($id, $message) // enviamos un mensaje a un cliente en particular
    {
      if(isset(self::$clients[$id]))
      {
  			self::$clients[$id]->send($message);
      }
    }
    
    final static public function getClients()
    {
      $map = array();
      for($i=0; $i<count(self::$clients); $i++)
  		{
        if(!isset(self::$clients[$i]))
        {
  			   $map[] = $i;
        }
  		}
      return $map;
    }
    
    // esta funcion es ejecutada al reportar errores
    static protected function SocketReporter($report){ }

}