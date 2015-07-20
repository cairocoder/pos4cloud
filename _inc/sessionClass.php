<?php

    class sessionClass {
    
        private $logged_in = false;
        public  $user_id;
        public  $username;
        public  $email;
        public  $loc_id;
        public  $job_id;
        public  $user_type;
    
        function __construct() {
            session_start();
            $this->check_login();
            if ($this->logged_in) {
                
            } else {
                
            }
        }
        
        public function is_logged_in() {
            return $this->logged_in;
        }
        
        public function login($user) {
            if($user){
                $this->user_id    = $_SESSION['user_id']    = $user->user_id;
                $this->username   = $_SESSION['username']   = $user->username;
                $this->email      = $_SESSION['email']      = $user->email;
                $this->loc_id     = $_SESSION['loc_id']     = $user->loc_id;
                $this->job_id     = $_SESSION['job_id']     = $user->job_id;
                $this->user_type  = $_SESSION['user_type']  = $user->user_type;
                $this->logged_in  = true;
            }
        }
        
        public function logout() {
            unset($_SESSION['user_id'], $_SESSION['date']);
            unset($this->user_id);
            $this->logged_in = false;
        }
        
        private function check_login() {
            if (isset($_SESSION['user_id'])) {
                $this->user_id   = $_SESSION['user_id'];
                $this->logged_in = true;
            } else {
                unset($this->user_id);
                $this->logged_in = false;
            }
        }
    }
    
    $session = new sessionClass();
    
?>
