<?php
namespace Model\Audience;

class Stats extends \Lib\Model\Rest {
        
        
        public function getStats() {
                
                
                $this->request("cc/audiencestatsservice");
                
        }
        
        
}
