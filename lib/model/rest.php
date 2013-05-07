<?php

namespace Lib\Model;
/**
 * Rest -- specialized model for rest based requests
 *
 * A lot of this code is just cut and paste from the examples.
 */
class Rest extends \Model {
        
        
        
        
        
        /**
         * @var  string    hostname
         */
        private $host = 'https://api.lotame.com/';
        
        
        /**
         *
         * @param    string  $endpoint
         * @return   object
         */
        public function request( $endpoint ) {
                
                
                      
                
                
        }
        
        
        /**
         *
         * @return   string  $token 
         */
        protected function getToken( ) {


                // urlencode the post args
                $postargs = 'email='.urlencode($username).'&password='.urlencode($password);
                
                // initialize the curl session
                $session = curl_init($restUrl);
                
                // set up our curl options for posting the args
                curl_setopt($session, CURLOPT_POST, true);
                curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                
                // run curl, get the token
                $token = curl_exec($session);
                curl_close($session);
                
                if(!$token) {
                        throw new \Lib\Exception\Rest(); 
                }
                
                return $token;
        }
        
        
}
