<?php namespace PHPServerSocket;

abstract class SocketClient extends \PHPSocketMaster\SocketEventReceptor
{

    public $id;
    
    public function onDisconnect()
  	{
  		ServerManager::DeleteClient($this->id);
  	}

}