<?php
  session_start();

  // Flash message helper
  // EXAMPLE - flash('register_success', 'You are now registered');
  // DISPLAY IN VIEW - echo flash('register_success');
  function flash($name = '', $message = '', $class = 'alert alert-success'){
 
    if(!empty($name) && !empty($message) ){
        // si session name n'est pas vide alors on la supprime, pas super utile mais bon
        echo '<div class="'.$class.'" id="msg-flash">'.$message.'</div>';
    }
  }

  function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
      return true;
    } else {
      return false;
    }
  }
