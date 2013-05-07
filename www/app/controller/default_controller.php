<?php
namespace Controller;
/**
 * Default controller, just shows the one table in this
 * example.
 *
 *
 */
class Default_Controller extends \Lib\Controller {
        
        /**
         * Shows the table...
         *
         */
        public function default_action() {
                
                # this is awful, but in the interest of time...
                include DIR_APP . "/views/default.php";
                
        }
        
}
