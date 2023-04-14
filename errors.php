<?php 
if(isset($errors)){
  if (count($errors) > 0){

    echo '<div class="error">';
      foreach ($errors as $error){

        echo "<p>$error</p>";
      }
      echo "</div>";

  }
}