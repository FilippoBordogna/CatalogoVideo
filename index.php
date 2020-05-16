
<?php

	session_start();
	if(!isset($_SESSION["login"]) || $_SESSION["login"]!=1)
		session_unset();
	if(isset($_POST["stato"])&&$_POST["stato"]=="logout")
	{
		session_unset();
		session_destroy();
		session_start();
	}
	
	function dbConn(){
		$host = ""; /* Host Server MySQL */
		$user = "root"; /* User Server MySQL */
		$pwd = ""; /* Password Server MySQL */
		$dbname = "catalogo"; /* Nome DB MySQL */
		$conn = new mysqli ( $host , $user , $pwd , $dbname ); /* Inizializzazione Connesione DB */
		if ($conn->connect_errno) { /* Controllo della corretta connesione */
			printf("Errore nella connessione al DB:</br>", $mysqli->connect_error);
			exit();
		}
		return $conn;
	}
?>


<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Catalogo Film, Documentari e Serie TV</title>


	<!-- Bootstrap core CSS -->
	<link href="assets/dist/css/bootstrap.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

	  .col-md-4 :hover {
		cursor: pointer;
		background-color: black;
		color: white;	   
	   }

	   p.card-text-description{
		   display: none;
	   }

	   p.card-text-description :hover{
		   display: block;
	   }

    </style>
    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
	<script>
		function logout(){
			f.stato.value="logout";
			f.submit();
		}
		
		function passa_a(id,valore){
			f.id.value=id;
			f.stato.value=valore;
			f.submit();
		}
	</script>
  </head>
  <body>
	
		<header>
		  <div class="navbar navbar-dark bg-dark shadow-sm">
			<div class="container d-flex justify-content-between">
			  <a href="#" class="navbar-brand d-flex align-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
				<strong>Album</strong>
			  </a>
			  
			  	<?php
					if(!isset($_SESSION["login"]))
						$_SESSION["login"]=0;
					if(isset($_POST["user"]) && isset($_POST["pass"])){
						$login=1;
						$utente=trim($_POST["user"]);
						$password=trim($_POST["pass"]);
						$conn=dbConn();
						$query="SELECT * FROM utenti WHERE email='".$utente."' AND password='".md5($password)."';"; /* Preparazione Query */
						$result=$conn->query($query); /* Risultati della query */
						if (!$result->num_rows!=1){
							$riga=$result->fetch_assoc();
							$admin=$riga["admin"];
							$result->free();
							$_SESSION["user"]=$riga["username"];
							$_SESSION["admin"]=$riga["admin"];
							$_SESSION["login"]=1;
						}
						else
							$login=0;
						//$result->free();
						$conn->close();
					}
					
					/*$_SESSION["login"]=1;
					$_SESSION["user"]="Pippo";*/

					if($_SESSION["login"]==0) /* Utente non loggato */
						echo '
							<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Login
						</button>
							<div class="dropdown-menu dropdown-menu-right">
							<form class="px-4 py-3" method="post">
								<div class="form-group">
								<label class="ml-2" for="exampleDropdownFormEmail1">Email address</label>
								<input type="email" class="form-control" id="user" name="user" placeholder="email@example.com">
								</div>
								<div class="form-group">
								<label class="ml-2" for="exampleDropdownFormPassword1">Password</label>
								<input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
								</div>
								<div class="form-check ml-2">
								<input type="checkbox" class="form-check-input" id="dropdownCheck">
								<label class="form-check-label" for="dropdownCheck">
									Remember me
								</label>
								</div>
								<button type="submit" class="btn btn-primary ml-2">Sign in</button>
							</form>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">New around here? Sign up</a>
							<a class="dropdown-item" href="#">Forgot password?</a>
							</div>
						</div>';
					else
						echo '
							<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							'.$_SESSION["user"].'
						</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<button class="dropdown-item" type="button">Visualizza Profilo</button>
								<button class="dropdown-item" type="button">Visualizza Recensioni</button>
								<button class="dropdown-item" type="button">Visualizza Curiosità</button>
								<button class="dropdown-item" type="button" onclick="logout()">Logout</button>
							</div>
						</div>';
				?>
			</div>
		  </div>
	</header>

	<main role="main">
	<form name='f' id='f' method='post'>
		<input type='hidden' name='stato' id='stato'>
		<?php
			if(isset($_POST["stato"])&&!empty($_POST["stato"])) {
				$stato=$_POST["stato"];
			}
			else {
				$stato=0;

			}
			switch($stato){
					case 0:
			
		?>
			<!--<section class="jumbotron text-center">
			<div class="container">
			  <h1>Album example</h1>
			  <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
			  <p>
				<a href="#" class="btn btn-primary my-2">Main call to action</a>
				<a href="#" class="btn btn-secondary my-2">Secondary action</a>
			  </p>
			</div>
		  </section>-->

		  <div class="album py-5 bg-light">
			<div class="container">
			  <div class="row">
			  <input type='hidden' name='id' id='id'>
				<?php
					$conn=dbConn();
					$query="SELECT * FROM video WHERE selettore=1;"; /* Preparazione Query */
					//$result=$conn->query($query); /* Risultati della query */
					if ($risultati=$conn->query($query)) { /* Risultati della query */
						if ($risultati->num_rows>0) {
							while ($riga = $risultati->fetch_assoc()) { /* */
								echo ('
									<div class="col-md-4 " onclick="passa_a('.$riga["id"].',1)" >
										<div class="card mb-4 shadow-sm">
											<img src="images/video/'.$riga["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail">
											<div class="card-body">
												<p class="card-text">'.$riga["nome"].'</p>
												<p class="card-text-description">'.$riga["Sinossi"].'</p>
												<div class="d-flex justify-content-between align-items-center">
													<div class="btn-group">
														<button type="button" class="btn btn-sm btn-outline-secondary">View</button>
														<button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
													</div>
													<small class="text-muted">Durata: '.$riga["durata"].' minuti</small>
												</div>
											</div>
										</div>
									</div>
								');
							}
						}
						else {
							echo ('
									<div class="col-md-4 ">
										<div class="card mb-4 shadow-sm">
											<div class="card-body">
												<p class="card-text">Nessun risultato di ricerca trovato</p>
											</div>
										</div>
									</div>
								');
						}
						$risultati->free();
						$conn->close();
					}
				?>
			  </div>
			</div>
		  </div>
		
		<?php
			break;
		case 1:
		
			$id=$_POST["id"];
			$conn=dbConn();
			$query="SELECT * FROM video WHERE id=$id;"; /* Preparazione Query */
			$result=$conn->query($query); /* Risultati della query */
			$riga=$result->fetch_assoc();
			$conn->close();
			echo '<div class="container text-center">';
			echo "<h1 class='mt-4 mb-4'>$riga[nome]</h1>";
			echo '<img src="images/video/'.$id.'.jpg" class="img-fluid mt-4 mb-4" alt="Responsive image">';
			echo '</div>';
			break;
		}
		?>
	</form>
	</main>
    
<footer class="text-muted">
  <div class="container">
    <p class="float-right">
      <a href="#">Back to top</a>
    </p>
	
    <!--<p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
    <p>New to Bootstrap? <a href="https://getbootstrap.com/">Visit the homepage</a> or read our <a href="../getting-started/introduction/">getting started guide</a>.</p>-->
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
