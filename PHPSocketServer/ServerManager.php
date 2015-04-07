<?php namespace PHPServerSocket;

// CLASS EXISTS?

require('libs/SocketListener.php');

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
  
        ///////////////////////////////////////////////////////////////////////// avisar que estamos a la escucha aquí
    
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
    
    // esta funcion debe ser reescrita por la persona que use la clase  //////////////////////////////////////
    static public function AddNewClient()
  	{
      // agregamos nuevo cliente para que en el bucle se use uno nuevo
  		self::$newClient = new newClient();
  	}
    
    // agregar el nuevo cliente
    static public function AddClient($sock)
  	{
      $vacio = false;
      // revisamos que no haya un casillero vacío
      for($i = 0; $i<count(self::$clients); $i++)
      {
          if(!isset(self::$clients[$i]))
          { // usamos el espacio vacío
              $vacio = true;
              self::$clients[$i] = $sock; 
              $sock->SocketEventReceptor->id = $i;
          }
      }
      // si no usamos un espacio vacío agregamos uno
  		if(!$vacio)
      {
          $sock->SocketEventReceptor->id = count(self::$clients); // add te id
		      self::$clients[count(self::$clients)] = $sock;
      }
  	}
    
    static public function DeleteClient($id) // eliminamos el cliente
  	{
  		unset(self::$clients[$id]);
  	} ////////////////////////////////////////////////////////////////////////// hacer interface new client que implemente eliminación de cliente
    
    //////////////////////////////////////////////////////////////////////////// hacer funciones publicas de transisión como sendToAll o sendToId

}