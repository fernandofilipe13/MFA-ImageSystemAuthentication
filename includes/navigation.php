
<div class="container">
	<ul class="nav nav-pills">
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Index") {?>active<?php }?>" href="index.php">Home</a>
	  </li>
	  <li class="nav-item">
	    <a id='resetMFA' class="nav-link">Reset MFA</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link <?php if ($CURRENT_PAGE == "Contact") {?>active<?php }?>" href="contact.php">Contact</a>
	  </li>
	  <?php if (isset($_SESSION['username'])) : ?>
		<li class="nav-item">
	    	<a class="nav-link btn-outline-danger <?php if ($CURRENT_PAGE == "Logout") {?>active<?php }?>" href="index.php?logout='1'">Logout</a>
	  	</li>
	  <?php endif ?>
	</ul>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
  $(document).on("click", '#resetMFA', async function() {
	const { value: code } = await Swal.fire({
		title: 'Insert you backup code here',
		input: 'text',
		inputLabel: 'To reset your account',
		inputPlaceholder: 'Enter your backup code'
	})

	if (code) {
		Swal.fire(`Entered backup code: ${code}`)
		$.ajax({
			type:'POST',
			url:'server.php',
			data:{
				"backup":code
			},
			success:function(data){
				console.log(data)
				var result = $.parseJSON(data);

				Swal.fire({
					title:result.title,
					icon:result.icon,
					timer: 10000,
					confirmButtonColor:result.color, 
					confirmButtonText: 'Ok!'
				})
				.then(() => {
					location.href = result.redirect; 
				})
			}
		})
	}
})
</script>