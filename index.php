<?php
	session_start();
	if(isset($_POST["stato"])&&$_POST["stato"]=="logout"&&isset($_SESSION))
		session_unset();
		session_destroy();
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Album example · Bootstrap</title>


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
    </style>
    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
	<script>
		function logout(){
			f.stato="logout";
			f.submit();
		}
	</script>
  </head>
  <body>
	<form name='f' id='f' method='post'>
		<input type='hidden' name='stato' id='stato'>
		<header>
		  <div class="navbar navbar-dark bg-dark shadow-sm">
			<div class="container d-flex justify-content-between">
			  <a href="#" class="navbar-brand d-flex align-items-center">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
				<strong>Album</strong>
			  </a>
			  
			  <?php
				$login=0;
				if(isset($_POST["user"]) && isset($_POST["pass"])){
					$login=1;
					$utente=trim($_POST["user"]);
					$password=trim($_POST["pass"]);
					$host = ""; /* server MySQL */
					$use = "root"; /* utente */
					$pwd = ""; /* password */
					$dbname = "film"; /* nome database */
					/* connessione al database */
					$conn = new mysqli ( $host , $use , $pwd , $dbname );
					$query="SELECT * FROM utenti WHERE email='".$utente."' AND password='".md5($password)."';";
					$result=$conn->query($query);
					if (!$result->num_rows==0){
						$row=$result->fetch_assoc();
						$admin=$row["admin"];
						$result->free();
						session_start();
						$_SESSION["user"]=$row["username"];
						$_SESSION["admin"]=$row["admin"];
					}
					else
						$login=0;
					//$result->free();
					$conn->close();
				}
				
				if($login==0||!isset($_SESSION))
					echo '
						<div class="dropdown">
					  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Login
					  </button>
						<div class="dropdown-menu dropdown-menu-right">
						  <form class="px-4 py-3" method="post">
							<div class="form-group">
							  <label for="exampleDropdownFormEmail1">Email address</label>
							  <input type="email" class="form-control" id="user" name="user" placeholder="email@example.com">
							</div>
							<div class="form-group">
							  <label for="exampleDropdownFormPassword1">Password</label>
							  <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
							</div>
							<div class="form-check">
							  <input type="checkbox" class="form-check-input" id="dropdownCheck">
							  <label class="form-check-label" for="dropdownCheck">
								Remember me
							  </label>
							</div>
							<button type="submit" class="btn btn-primary">Sign in</button>
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

	  <section class="jumbotron text-center">
		<div class="container">
		  <h1>Album example</h1>
		  <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
		  <p>
			<a href="#" class="btn btn-primary my-2">Main call to action</a>
			<a href="#" class="btn btn-secondary my-2">Secondary action</a>
		  </p>
		</div>
	  </section>

	  <div class="album py-5 bg-light">
		<div class="container">

		  <div class="row">
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>

			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>

			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-md-4">
			  <div class="card mb-4 shadow-sm">
				<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
				<div class="card-body">
				  <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
				  <div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
					  <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
					  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
					</div>
					<small class="text-muted">9 mins</small>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>

	</main>
    </form>
<footer class="text-muted">
  <div class="container">
    <p class="float-right">
      <a href="#">Back to top</a>
    </p>
	
    <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
    <p>New to Bootstrap? <a href="https://getbootstrap.com/">Visit the homepage</a> or read our <a href="../getting-started/introduction/">getting started guide</a>.</p>
  </div>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
