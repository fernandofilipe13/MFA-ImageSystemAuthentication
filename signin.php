<!DOCTYPE html>
<html>
<?php include("includes/a_config.php"); ?>

<head>
  <?php 
  include("includes/head-tag-contents.php");
  
  if (isset($_SESSION['username'])){
    header('location: multifactor.php');
  }
  ?>
</head>



<?php include("process_login.php"); ?>

<body class="align">
  <div style='text-align:center'>
    <h3>Sing In</h3>
    <p>It's quick and easy.</p>
  </div>
  <?php include('./notifications/alerts.php'); ?>

    <form method="POST" class="form login" style='max-width:250px;'>
      <div class="form__field">
        <label for="login__username"><svg class="icon">
            <use xlink:href="#icon-user"></use>
          </svg><span class="hidden">Username</span></label>
        <input autocomplete="username" id="login__username" type="text" name="username" class="form__input" placeholder="Username" required>
      </div>

      <div class="form__field">
        <label for="login__password"><svg class="icon">
            <use xlink:href="#icon-lock"></use>
          </svg><span class="hidden">Password</span></label>
        <input id="login__password" type="password" name="password" class="form__input" placeholder="Password" required>
        <label onclick="seePassword('login__password')" for="login__password"><svg class="icon">
            <use xlink:href="#icon-eye"></use>
          </svg>
        </label>
      </div>

      <div class="form__field">
        <input type="submit" class='submitButton' value="Login" name='signin'>
      </div>
      <div>Lost username or password? <a href='lostpassword.php'>Recover here!</a></div>
      <?php include('errors.php'); ?>

    </form>

    <p class="text--center">Not a member? <a href="signup.php">Sign up now</a> <svg class="icon">
        <use xlink:href="#icon-arrow-right"></use>
      </svg></p>


  <svg xmlns="http://www.w3.org/2000/svg" class="icons">
    <symbol id="icon-arrow-right" viewBox="0 0 1792 1792">
      <path d="M1600 960q0 54-37 91l-651 651q-39 37-91 37-51 0-90-37l-75-75q-38-38-38-91t38-91l293-293H245q-52 0-84.5-37.5T128 1024V896q0-53 32.5-90.5T245 768h704L656 474q-38-36-38-90t38-90l75-75q38-38 90-38 53 0 91 38l651 651q37 35 37 90z" />
    </symbol>
    <symbol id="icon-lock" viewBox="0 0 1792 1792">
      <path d="M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z" />
    </symbol>
    <symbol id="icon-user" viewBox="0 0 1792 1792">
      <path d="M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z" />
    </symbol>
    <symbol id="icon-eye" viewBox="0 0 512 512">
      <path d="M244.425,98.725c-93.4,0-178.1,51.1-240.6,134.1c-5.1,6.8-5.1,16.3,0,23.1c62.5,83.1,147.2,134.2,240.6,134.2 s178.1-51.1,240.6-134.1c5.1-6.8,5.1-16.3,0-23.1C422.525,149.825,337.825,98.725,244.425,98.725z M251.125,347.025 c-62,3.9-113.2-47.2-109.3-109.3c3.2-51.2,44.7-92.7,95.9-95.9c62-3.9,113.2,47.2,109.3,109.3 C343.725,302.225,302.225,343.725,251.125,347.025z M248.025,299.625c-33.4,2.1-61-25.4-58.8-58.8c1.7-27.6,24.1-49.9,51.7-51.7 c33.4-2.1,61,25.4,58.8,58.8C297.925,275.625,275.525,297.925,248.025,299.625z"></path>
    </symbol>
  </svg>

</body>
<?php include("includes/footer.php");?>
<script>
  function seePassword(input1, ) {
        var x = document.getElementById(input1);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
}
</script>
</html>