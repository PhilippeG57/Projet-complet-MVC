<?php
    class Pages extends Controller {
        public function __construct(){

        }

        public function index(){
            if(isLoggedIn()){
                redirect('posts');
            }

            $data=[
                'title' => 'Projet MVC',
                'description' => 'Framework MVC classe AFPA 2023'
            ];

            $this->view('pages/index', $data);
        }

        public function about(){
            $data=[
                'title' => 'Informations générales',
                'description' => 'Informations générales de mon site'
            ];

            $this->view('pages/about', $data);
        }
    }