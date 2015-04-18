<?php namespace PHPServerSocket;

abstract class SocketClient extends \PHPSocketMaster\SocketEventReceptor
{

    public $id;
    
    final public function onDisconnect()
  	{
      call_user_func(SRV_MGR.'::DeleteClient', $this->id);
      $this->_onDisconnect();
  	}
    
    public function onConnect()
    {
        if($this->firstExecution) $this->firstExecution = false;
        else {
            if(defined('SRV_WSK') || defined('SRV_DUAL'))
            {
                $this->onReady();
            }
        }
    }
    
    abstract public function onReady();
    abstract public function _onDisconnect();
    
}