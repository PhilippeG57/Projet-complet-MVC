<?php
    class Users extends Controller{
        public function __construct(){
            $this->userModel =  $this->model('User');
        }

        public function register(){
            // check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //Sanitize POST date
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Init data
                $data=[
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                //EMAIL
                if(empty($data['email'])){
                    $data['email_err'] = 'Vous devez entrer votre email';
                } else {
                    //check email
                    if($this->userModel->findUserByEmail($data['email'])){
                        $data['email_err'] = 'Cet email existe déjà';
                    }
                }

                //NAME
                if(empty($data['name'])){
                    $data['name_err'] = 'Vous devez entrer votre nom';
                }

                //PASSWORD
                if(empty($data['password'])){
                    $data['password_err'] = 'Vous devez entrer un mot de passe';
                }elseif(strlen($data['password'])<6){
                    $data['password_err'] = 'Vous devez entrer au moins 6 caractères';
                }

                //CONFIRM PASSWORD
                if(empty($data['confirm_password'])){
                    $data['password_err'] = 'Veuillez confirmer le mot de passe';
                }elseif($data['password'] != $data['confirm_password']){
                    $data['password_err'] = 'La confirmation de correspond pas au mot de passe';
                }

                //Make sure errors are empty
                if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                    // hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // register user
                    if($this->userModel->register($data)){
                        flash('register_success', 'Vous êtes bien inscrit, vous pouvez vous connecter.');
                        redirect('users/login');
                    }else{
                        die('Something went wrong');
                    }
                }else{
                    // Load view with errors
                    $this->view('users/register', $data);
                }
            }else{
                //Init data
                $data=[
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                //Load view
                $this->view('users/register', $data);
            }
        }

        public function login(){
            // check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //Sanitize POST date
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                //Init data
                $data=[
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => ''
                ];

                //validate EMAIL
                if(empty($data['email'])){
                    $data['email_err'] = 'Vous devez entrer votre email';
                }

                //validate PASSWORD
                if(empty($data['password'])){
                    $data['password_err'] = 'Vous devez entrer votre mot de passe';
                }

                //check for email
                if($this->userModel->findUserByEmail($data['email'])){
                    // User found
                } else {
                    // User not found
                    $data['email_err'] = "Pas d'utilisateur correspondant à cet email";
                }

                if(empty($data['email_err']) && empty($data['password_err'])){
                    // Validated
                    // Check and set logged in user
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                    if($loggedInUser){
                        //Create Session
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['password_err'] = "Mot de passe incorrect";
                        $this->view('users/login', $data);
                    }
                }else{
                    // Load view with errors
                    $this->view('users/login', $data);
                }
            }else{
                //Init data
                $data=[
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => ''
                ];

                // Load view
                $this->view('users/login', $data);
            }
    }
}