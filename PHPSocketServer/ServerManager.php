<?php namespace PHPServerSocket;

// CLASS EXISTS?

require('libs/SocketListener.php');

class ServerManager
{

    static protected $mainSocket = null;
    
    static protected $clients = array();
    
    static public function start($localIP = '127.0.0.1', $port = 2026)
    {
    
    }

}