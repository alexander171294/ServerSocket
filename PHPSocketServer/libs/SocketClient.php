<?php namespace PHPServerSocket;

abstract class SocketClient extends PHPSocketMaster\SocketEventReceptor
{

    public $id;
    
    public function onDisconnect()
  	{
  		ServerManager::DeleteClient($this->id);
  	}
    
    public function onError();
    
    public function onConnect();
    
    public function onReceiveMessage($message);
    
    public function onSendRequest(&$cancel, $message);
    
    public function onSendComplete($message)

}