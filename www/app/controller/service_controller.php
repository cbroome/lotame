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
         * This method will output Json directly...
         *
         */
        public function default_action( ) {
                
                $statsObj = new \Model\Audience\Stats;
                
                $stats = $statsObj->getStats(); 
                
                print json_encode($stats);
        }
 
	/**
	 *
	 *
	 */
	public function csv_action() {
		// should mask this...
		$csv_string = $_POST['csv'];
		header('Content-type: text/csv');
		header('Content-disposition: attachment;filename=audiences.csv');
		die($csv_string);
	}

       
}
