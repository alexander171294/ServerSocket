<?php namespace PHPServerSocket;

abstract class SocketClient extends \PHPSocketMaster\SocketEventReceptor
{

    public $id;
    
    public function onDisconnect()
  	{
      call_user_func(SRV_MGR.'::DeleteClient', $this->id);
  	}
    
    public function onConnect()
    {
    
    }
    
    abstract public function onReady();
    
}