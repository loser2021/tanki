<center>
    
    <figure><img src="/1.png" class="saf1">
    <h1><b>Ваш баланс успешно пополнен</b></h1>
 	  <link href="../style/sweet-alert.css" rel="stylesheet" type="text/css"><script src="../js/sweet-alert.min.js"></script><script type="text/javascript">
		          setTimeout(function() {
		          swal({
			      type: "success",
			      title: "Поздравляем!",
			      text: "Ваш баланс успешно пополнен, сейчас вы будете перенаправлены в аккаунт!",
			      showConfirmButton: true
		          });
		          }, 2000);
		          </script>
                <?  header('Refresh: 3; URL=/profile');
			
						
						
					//	echo "<div class='alert alert-success'>Вы успешно зарегистрировались. </div>";
                       //           header('Refresh: 3; URL=/login');
						?>


<!--<BR /><a href="/profile">Перейти в аккаунт</a>-->
</center>