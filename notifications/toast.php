<?php 
if(isset($alerts)){
  if (count($alerts) > 0){
    $respose = json_decode($alerts[0]);
    $title =$respose->title;
    $text = $respose->text;
    $icon = $respose->icon;
    $timer = $respose->timer;
    $redirect = $respose->redirect;
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        title:'$title',
        text:'$text',
        icon:'$icon',
        timer: $timer,
      }).then(() => {
        location.href  = '$redirect'; 
      })
    </script>";
    $alerts=array();
  }
}