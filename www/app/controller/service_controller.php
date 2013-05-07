<?php
namespace Controller;
/**
 * Default controller, just shows the one table in this
 * example.
 *
 *
 */
class Service_Controller extends \Lib\Controller {
        
        /**
         * Retrieve information about an audience...
         *
         */
        public function default_action( ) {
                
                $statsObj = new \Model\Audience\Stats;
                
                $stats = $statsObj->getStats(); 
                
        }
        
}
