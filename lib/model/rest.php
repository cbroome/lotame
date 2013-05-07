<?php

namespace Lib\Model;
/**
 * Rest -- specialized model for rest based requests
 *
 * A lot of this code is just cut and paste from the examples.
 */
class Rest extends \Lib\Model {
        
        
        /**
         * @static $token save token value so there's only one token per resource request
         */
        static private $token;
        
        /**
         * @var  string    hostname
         */
        private $host = 'https://api.lotame.com/';
        
        
        /**
         *
         *
         */
        public function __construct() {
                
                // These should be set elsewhere, but for brevity sake...
                $this->username = 'chris.a.broome@gmail.com';
                $this->password = 'TShmeiths';
                
        }
        
        
        
        /**
         *
         * @param    string  $endpoint
         * @return   object
         */
        public function request( $endpoint ) {
                $rv = null;
                
                $token = $this->getToken();
                
                $session = curl_init($restUrl);
                curl_setopt($session, CURLOPT_HTTPHEADER, array("Authorization: $token","Accept: application/json"));
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                
                //Make our request
                $jsonResponse = curl_exec($session);
                
                curl_close($session);
                $rv = json_decode($jsonResponse);
                
                
                return $rv; 
        }
        
        
        /**
         * Get the access token
         *
         *
         * @throws \Lib\Exception\Rest
         * @return   string  $token 
         */
        protected function getToken( ) {

                if( !self::$token ) {
                        // urlencode the post args
                        $postargs = 'email='.urlencode($this->username).'&password='.urlencode($this->password);
                                                
                        // initialize the curl session
                        $session = curl_init($this->host);
                        
                        // set up our curl options for posting the args
                        // curl_setopt($session, CURLOPT_CAINFO, DIR_LIB."/cacert.pem");
                        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 2); 
                        curl_setopt($session, CURLOPT_POST, true);
                        curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
                        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
                        
                        // run curl, get the token
                        $token = curl_exec($session);
                        
                        if(!$token) {
                                throw new \Lib\Exception\Rest("Could not retrieve token! " . curl_error($session)); 
                        }
                        self::$token = $token;
                        curl_close($session);
                }                
                return self::$token;
        }
        
        
}
