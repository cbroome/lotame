<?php
namespace Model\Audience;

class Stats extends \Lib\Model\Rest {
        
        /**
         *
         * @return      stats objects
         */
        public function getStats() {
                
                
                $result = $this->request("audstats/reports/topAudiences");
                
                if(!$result->stat) {
                        throw \Exception("Malformed response, no stat property");
                }
                
                return $result->stat;                
        }
        
        
}
