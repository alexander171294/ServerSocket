<?php namespace PHPServerSocket;

// example of implementation of Class Socket Master for listen
class SocketListener extends PHPSocketMaster\SocketMaster
{

	// on error message event
	public function onError($errorMessage) /////////////////////////////////////// necesito crear un error manager
	{
		echo 'Oops SERVER error ocurred: '.$errorMessage;
		die(); // finish
	}

	public function onNewConnection(SocketBridge $socket)
	{
		ServerManager::AddNewClient();
		ServerManager::AddClient($socket);
	}
    
  public function onSendRequest(&$cancel, $message) {}   
  public function onSendComplete($message) {}
  // on Connect event
	public function onConnect() {}
	// on disconnect event
	public function onDisconnect() {}
	// on receive message event
	public function onReceiveMessage($message) {}
}