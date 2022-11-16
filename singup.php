
<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>Acceso - Control de Pacientes de Medicina Est&eacute;tica</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

<link href="css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>

<body>
	
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand">
				<i class="icon-user-md"></i>&nbsp;Funcaci&oacute;n Sin Obesidad M&eacute;xico A.C.				
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					
					<li class="">						
						<a href="signup.html" class="" style="font-size:18px">
							Ingresar
						</a>
						
					</li>
					
					<!--<li class="">						
						<a href="index.html" class="">
							<i class="icon-chevron-left"></i>
							Regresar
						</a>
						
					</li>-->
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->



<div class="account-container">
	
	<div class="content clearfix">
		

		
			<h1>Acceso al sistema</h1>		
			
			<div class="login-fields">
				
				<p>Ingrese sus credenciales</p>
				
				<div class="field">
					<label for="username">Username</label>
					<input type="text" id="username" name="username" value="" placeholder="Nombre de usuario" class="login username-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<input type="password" id="password" name="password" value="" placeholder="Contrase&ntilde;a" class="login password-field"/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Recordarme</label>
				</span>
									
				<input type="button" class="button btn btn-success btn-large" value="Entrar" onClick="validar()" />
				
			</div> <!-- .actions -->
			
			
			

		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->



<div class="login-extra">
	<a href="#">Recuperar contrase&ntilde;a</a>
</div> <!-- /login-extra -->


<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/signin.js"></script>
<script type="text/javascript">
	
	function validar(){
		var username = $("#username").val();
		var password = $("#password").val();
		var params = "username="+username+"&password="+password;
		$.post("ajax/ajaxlogin.php?op=validaUsuario", params, function(resp){
			if(resp == 1)
				document.location = "index.php";
			if(resp == 0)
				alert("Usuario y/o contraseña incorrecta");	
			if(resp == 2)
				alert("Usuario inactivo. Consulte con el administrador del sistema");
		});
	}
	
</script>
</body>

</html>
