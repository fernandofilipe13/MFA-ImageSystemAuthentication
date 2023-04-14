<?php
if (isset($alerts)) {
  if (count($alerts) > 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    foreach ($alerts as $key) {
      # code...
      $respose = json_decode($key);
      // $title = '';
      // $html = '';
      // $icon = 'success';
      // $timer = 5000;
  
    $constructSwal = "Swal.fire({";
    if (isset($respose->title)){$constructSwal .= "title:'$respose->title',";}
    if (isset($respose->html)){$constructSwal .= "html:'$respose->html',";}
    if (isset($respose->icon)){$constructSwal .= "icon:'$respose->icon',";}
    if (isset($respose->timer)){$constructSwal .= "timer:$respose->timer,";}
    if (isset($respose->timerProgressBar)){$constructSwal .= "timerProgressBar:true,";}
    if (isset($respose->footer)){$constructSwal .= "footer:'$respose->footer',";}

    $constructSwal .= "})";
    if (isset($respose->redirect) || isset($respose->moreswall)) {
      $constructSwal .=".then(() => {";
        if (isset($respose->redirect)) {
          $constructSwal .= "location.href ='$respose->redirect'";
        }
        if (isset($respose->moreswall)) {
          $constructSwal .= "$respose->moreswall";
        }
      $constructSwal .="})";
    }
    echo "
    
    <script>
      $constructSwal
    </script>";
    $alerts = array();
    }
   
  }
}
