<?php

require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';
class LiveChat extends PluginAbstract{

    public function getDescription() {
        global $global;
        $desc = "A live chat for multiple propouses<br>Initiate it on terminal with the command <code>nohup php {$global['systemRootPath']}plugin/LiveChat/chat-server.php &</code>";
        $desc .= $this->isReadyLabel(array('Live'));
        return $desc;
    }
    
    public function getName() {
        return "LiveChat";
    }

    public function getUUID() {
        return "52222da2-3f14-49db-958e-15ccb1a07f0e";
    }

    public function getPluginVersion() {
        return "1.0";   
    }
    
    
    public static function getChatPanelFile(){
        global $global;
        $cmd = "nohup php {$global['systemRootPath']}plugin/LiveChat/chat-server.php &";
        exec($cmd . " < /dev/null  2>&1");
        return $global['systemRootPath'].'plugin/LiveChat/view/panel.php';
    }
    
    public static function includeChatPanel($chatId = ""){
        global $global;
        if(Plugin::isEnabledByUUID(self::getUUID())){
            require static::getChatPanelFile();            
        }
    }
    
    public function getEmptyDataObject() {
        global $global;
        $server = parse_url($global['webSiteRootURL']);
        
        $obj = new stdClass();
        $obj->port = "8888";
        
        $scheme = "ws";
        $port = ":{$obj->port}";
        if(strtolower($server["scheme"])=="https"){
            $scheme = "wss";
            $port = "/wss/";
        }        
        
        $obj->websocket = "{$scheme}://{$server['host']}{$port}";
        $obj->onlyForLoggedUsers = false;
        $obj->loadLastMessages = 10;
        return $obj;
    }
    
    
    public function getWebSocket() {
        $o = $this->getDataObject();
        return $o->websocket;
    }

    
    public function getTags() {
        return array('free', 'live', 'streaming', 'live stream', 'chat');
    }
    
    public function canSendMessage($isLogged=false){
        $obj = $this->getDataObject();
        if(empty($obj->onlyForLoggedUsers) || (User::isLogged() || $isLogged)){
            return true;
        }
        return false;
    }

}
