<?php
	/* 
		DA FARE:
		- Aggiungere il numero di stagioni nell'elenco serie (case 2)
		- Aggiungere la lista degli episodi,delle stagioni, le recensioni e le curiosità in dettagli serie (case 5)
		- Pagina tutte le saghe (case 3)
		- Pagina dettagli saghe (case 7)
		- Aggiungere campo datauscita al video
		- Copiare e incollare quanto fatto per le recensioni dei film per le curiosità dei film (non c'è voto, possono esserci più commenti(?))
		- Copiare e incollare quanto sopra per le recensioni e le curiosità delle serie
		- Copiare e incollare quanto sopra per le recensioni e le curiosità delle saghe (?)
		- Permettere ad Admin di validare commenti  
		- Ricerca
		- Registrazione (nuova pagina)
		- Ultimi accessi (ultimo) per controllo
		- Rifare lo schema ER/logico in base alle modifiche (PIPPO)

		SCHEMA SWITCH:
		- 0 HOMEPAGE
		- 1 TUTTI I FILM
		- 2 TUTTE LE SERIE TV
		- 3 TUTTE LE SAGHE
		- 4 TUTTI I DOCUMENTARI
		- 5 DETTAGLI VIDEO
		- 6 DETTAGLI SERIE 
		- 7 DETTAGLI SAGHE
		- 8 DETTAGLI PERSONE
		- 9 DETTAGLI PERSONAGGI
		- 10 RICERCA
	*/
	/* Controllo Sessioni */
	session_start();
	if(!isset($_SESSION["login"]) || $_SESSION["login"]!=1) /* Mancata presenza di dati integri per login */
		session_unset();
	if(isset($_GET["stato"])&&$_GET["stato"]=="logout") { /* Operazione di logout */
		session_unset();
		session_destroy();
		session_start();
		if(isset($_GET["logout"]))
			$_GET["stato"]=$_GET["logout"];
	}
	
	function dbConn() { /* Connessione al DB */
		$host = ""; /* Host Server MySQL */
		$user = "root"; /* User Server MySQL */
		$pwd = ""; /* Password Server MySQL */
		$dbname = "catalogo"; /* Nome DB MySQL */
		$conn = new mysqli ( $host , $user , $pwd , $dbname ); /* Inizializzazione Connesione DB */
		if ($conn->connect_errno) { /* Controllo della corretta connesione */
			printf("Errore nella connessione al DB:</br>", $conn->connect_error); /* Stampa eventuali errori */
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
		.rate {
			float: left;
			height: 46px;
			padding: 0 10px;
		}
		.rate:not(:checked) > input {
			position:absolute;
			top:-9999px;
		}
		.rate:not(:checked) > label {
			float:right;
			width:1em;
			overflow:hidden;
			white-space:nowrap;
			cursor:pointer;
			font-size:30px;
			color:#ccc;
		}
		.rate:not(:checked) > label:before {
			content: '★ ';
		}
		.rate > input:checked ~ label {
			color: #ffc700;    
		}
		.rate:not(:checked) > label:hover,
		.rate:not(:checked) > label:hover ~ label {
			color: #deb217;  
		}
		.rate > input:checked + label:hover,
		.rate > input:checked + label:hover ~ label,
		.rate > input:checked ~ label:hover,
		.rate > input:checked ~ label:hover ~ label,
		.rate > label:hover ~ input:checked ~ label {
			color: #c59b08;
		}
		
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

		.col-md-3 :hover {
			cursor: pointer;
			background-color: black;
			transition: all 0.7s;
			color: white;	   
		}

		.card-text-description {
			display: none;
		}

		.col-md-3 :hover .card-text-description {
			display: block;
		}

		.text-left :hover {
			cursor: pointer;
		}

		</style>
		
		<!--<link href="album.css" rel="stylesheet">-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script> /* Funzioni JavaScript */
			function logout(id,stato) { /* Effettua il logout */
				f.stato.value="logout";
				f.submit();
			}
			
			function passa_a(id,stato) { /* Cambia lo stato (e quindi la pagina) e l'identificativo */
				f.id.value=id;
				f.stato.value=stato;
				f.submit();
			}
			
			function recensione(id){ /* Controlli sulla recensione ed effettivo inserimento */
				if(f.rate.value==0)
					alert("Errore! Devi inserire un voto per lasciare una recensione");
				else{
					if(f.textarea.value!="")
						recensioni.rec.value=f.textarea.value;
					recensioni.rate.value=f.rate.value;
					recensioni.submit();
				}
			}
			function elimina(id,idUtente) { /* Elimina una recensione */
				recensioni.rate.value="ELIMINA";
				recensioni.idUtente.value=idUtente;
				recensioni.submit();
			}
			function verifica(id,idUtente){ /* Verifica della recensione da parte dell'admin */
				recensioni.rate.value="VERIFICA";
				recensioni.idUtente.value=idUtente;
				recensioni.submit();
			}
			
		</script>
  	</head>
  	<body>
		<header>
		  	<div class="navbar navbar-dark bg-dark shadow-sm"> <!-- Base della Navbar-->
		 		<div class="container d-flex justify-content-between"> <!-- Contenitore delle scorciatoie -->
			  		<a href="#" class="navbar-brand d-flex align-items-center" onclick="passa_a(null,0)"> <!-- Scorciatoia Homepage -->
						<!--<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>-->
						<strong>Homepage</strong>
					</a>
					
					<?php
						if(!isset($_SESSION["login"])) /* Sessione Login non inizializzata */
							$_SESSION["login"]=0;
						if(isset($_POST["user"]) && isset($_POST["pass"])){ /* Username e Password specificati */
							$login=1;
							$utente=filter_var(trim($_POST["user"]), FILTER_SANITIZE_STRING);
							$password=filter_var(trim($_POST["pass"]), FILTER_SANITIZE_STRING);
							$conn=dbConn();
							$query="SELECT * FROM utenti WHERE email='".$utente."' AND password='".md5($password)."';"; /* Preparazione Query: Controllo Accesso */
							$risultati=$conn->query($query); /* Risultati della query */
							if (!$risultati->num_rows!=1){
								$riga=$risultati->fetch_assoc();
								$admin=$riga["admin"];
								$risultati->free();
								$_SESSION["user"]=$riga["username"];
								$_SESSION["idUser"]=$riga["id"];
								$_SESSION["admin"]=$riga["admin"];
								$_SESSION["login"]=1;
							}
							else
								$login=0;
						
							$conn->close();
						}

						if($_SESSION["login"]==0) /* Utente non loggato */
							echo (' 
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
											<label class="form-check-label" for="dropdownCheck">Remember me</label>
										</div>
										<button type="submit" class="btn btn-primary ml-2 mt-2">Sign in</button>
									</form>
									<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#">New around here? Sign up</a>
										<a class="dropdown-item" href="#">Forgot password?</a>
									</div>
								</div>
								'); /* Tendina Login */
						else /* Utente loggato */
							echo ('
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
								</div>
							'); /* Tendina gestione contenuti */
					?>
				</div>
		  	</div>
		</header>

		<main role="main">
			<form id='search' name='search' method='get'>
				<div class="container d-flex justify-content-center mt-2">
					<div class="form-row align-items-center">
						<div class="col-auto">
							<input class=" d-flex align-items-center form-control" name='ricerca' id='ricerca' type="text" placeholder="Ricerca">
						</div>
						<div class="col-auto">
							<button type="submit" class=" btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				
			</form>
			<form name='f' id='f' method='get'> <!-- Form Principale -->
				<input type='hidden' name='stato' id='stato'> <!-- Identificativo della pagina da caricare -->
				<div class="album py-5 bg-light">
				<!--<a href="#" class="navbar-brand d-flex align-items-center" onclick="passa_a(null,6)"> <!-- Scorciatoia Homepage -->
						<!--<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>-->
						<!--<strong>Saghe</strong>
					</a>
					<a href="#" class="navbar-brand d-flex align-items-center" onclick="passa_a(null,4)"> <!-- Scorciatoia Homepage -->
						<!--<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>-->
						<!--<strong>Serie</strong>
			  		</a>-->
					<div class="container">
					
						<div class="row">
							<?php
								echo "<input type='hidden' name='id' id='id'";//Identificativo dell'oggetto a cui si fa riferimento
								if(isset($_GET["id"])&&!empty($_GET["id"])){
									 echo "value='$_GET[id]'";
								}
								echo ">";
								if(isset($_GET["stato"])&&!empty($_GET["stato"])) { /* Stato conosciuto */
									$stato=$_GET["stato"];
									echo "<input type='hidden' name='logout' id='logout' value='$stato'>"; // Identificativo dell'oggetto a cui si fa riferimento -->
								}
								
								
								else  /* Stato sconosciuto */
									if(isset($_GET["ricerca"]))
										$stato=10;
									else
										$stato=0;

								switch($stato) {
									case 0: /* 
											****************
											*** HOMEPAGE ***
											****************
											*/

										$conn=dbConn();

										/* MIGLIORI VIDEO*/
										$query="SELECT V.id, V.nome, V.durata, V.idSaga, V.idSerie, V.numero, V.stagione, V.selettore, V.sinossi, RV.voto, RV.idAdmin, AVG(RV.voto) mediaVoto
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore!=2
										GROUP BY RV.idVideo
										ORDER BY mediaVoto DESC
										LIMIT 8"; /* Preparazione Query: Migliori video (base voto) */
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Video</h2>
											</div>
											');
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($elemento["mediaVoto"],2).'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di video */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun video è ancora stato recensito</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free();	
										}

										/* MIGLIORI FILM*/
										$query="SELECT V.id, V.nome, V.durata, V.idSaga, V.idSerie, V.numero, V.stagione, V.selettore, V.sinossi, RV.voto, RV.idAdmin, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore=1
										GROUP BY RV.idVideo
										ORDER BY mediaVoti DESC
										LIMIT 8"; /* Preparazione Query: Migliori film (base voto) */
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Film</h2>
											</div>
											');
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($film = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$film["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$film["nome"].'">
																	<p class="card-text">'.$film["nome"].'</p>
																	<p class="card-text-description">'.$film["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$film["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($film["mediaVoti"],2).'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di film */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film è ancora stato recensito</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free();	
										}
										echo ('	
														<div class="container" onclick="passa_a(null,1)"><a href="#">Vedi tutti</a></div>	
											'); /* Link mostra tutti i film */

										/* MIGLIORI SERIE TV */
										$query="SELECT S.*, AVG(RS.voto) mediaVoti, MAX(V.stagione) nStagioni, COUNT(DISTINCT V.id) nEpisodi
										FROM recensioneserie RS JOIN serie S ON S.id=RS.idSerie JOIN video V on V.idserie=S.id
										WHERE RS.idAdmin IS NOT NULL
										GROUP BY S.id
										ORDER BY mediaVoti DESC
										LIMIT 8"; /* Preparazione Query: Migliori serie TV (base voto) */
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Serie TV</h2>
											</div>
											');
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($serie = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni serie TV */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$serie["id"].',6)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$serie["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$serie["nome"].'">
																	<p class="card-text">'.$serie["nome"].'</p>
																	<p class="card-text-description">'.$serie["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Stagioni: '.$serie["nStagioni"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi totali: '.$serie["nEpisodi"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($serie["mediaVoti"],2).'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di serie TV */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna serie TV è ancora stata recensita</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free();
										}
										echo ('	
														<div class="container" onclick="passa_a(null,2)"><a href="#">Vedi tutte</a></div>	
											'); /* Link mostra tutte le serie */

										/* MIGLIORI SAGHE */
										$query="SELECT AVG (Medie.mediaVoti) media, COUNT(DISTINCT V.id) nFilm, S.id, S.nome
										FROM (
												SELECT  AVG(R.voto) mediaVoti, V.*
												FROM recensionevideo R JOIN video V ON V.id=R.idVideo JOIN saghe S ON S.id=V.idSaga
												WHERE R.idAdmin IS NOT NULL
												GROUP BY V.id
											) Medie
										JOIN saghe S ON S.id=Medie.idSaga
                                        JOIN video V ON V.idSaga=Medie.idSaga
										GROUP BY Medie.idSaga
										ORDER BY Medie.mediaVoti DESC
										LIMIT 8"; /* Preparazione Query: Migliori saghe (base voto) */
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Saghe</h2>
											</div>
											');
										if ($saghe=$conn->query($query)) { /* Query effettuata con successo */
											if ($saghe->num_rows>0) { /* Almeno un risultato */
												while ($saga = $saghe->fetch_assoc()) { /* Costruisco un riquadro per ogni saga TV */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',6)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/saghe/'.$saga["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
																	<p class="card-text">'.$saga["nome"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Film: '.$saga["nFilm"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($saga["media"],2).'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di saghe */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna saga è ancora stata recensita</p>
																</div>
															</div>
														</div>
													');
											}
											$saghe->free();
										}
										echo ('	
														<div class="container" onclick="passa_a(null,3)"><a href="#">Vedi tutte</a></div>	
											'); /* Link mostra tutte le saghe */

										/* MIGLIORI DOCUMENTARI */
										$query="SELECT V.id, V.nome, V.durata, V.idSaga, V.idSerie, V.numero, V.stagione, V.selettore, V.sinossi, RV.voto, RV.idAdmin, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore=3
										GROUP BY RV.idVideo
										ORDER BY mediaVoti DESC
										LIMIT 8"; /* Preparazione Query: Migliori documentari (base voto) */
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Documentari</h2>
											</div>
											');
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($documentario = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni documentario */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$documentario["id"].',5)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$documentario["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$documentario["nome"].'">
																	<p class="card-text">'.$documentario["nome"].'</p>
																	<p class="card-text-description">'.$documentario["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$documentario["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($documentario["mediaVoti"],2).'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di documentari */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun documentario ancora stato recensito</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free();
										}
										echo ('	
														<div class="container" onclick="passa_a(null,4)"><a href="#">Vedi tutti</a></div>	
											'); /* Link mostra tutte i documentari */

										$conn->close();
										break;
									

									case 1: /* 
										******************
										*** TUTTI FILM ***
										******************
										*/
										echo "TUTTI I FILM";
										break;

									case 2: /* 
										*************************
										*** TUTTE LE SERIE TV ***
										*************************
										*/

										$conn=dbConn();
										$query="SELECT * FROM serie"; /* Preparazione Query: Tutte le serie */
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie piu\' recenti</h2>
											</div>
											');
										if ($serie=$conn->query($query)) { /* Query effettuata con successo */
											if ($serie->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $serie->fetch_assoc()) { /* Costruisco un riquadro per ogni serie */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
														');
														$query="SELECT COUNT(S.id) nepisodi
														FROM serie S JOIN video V ON S.id=V.idSerie 
														WHERE V.idSerie=$id"; /* Preparazione Query: Numero episodi della serie */
														if ($risultato=$conn->query($query)) { /* Query effettuata con successo */
															if ($risultato->num_rows==1) { /* Almeno un risultato */
																$nepisodi = $risultato->fetch_assoc();
													echo ('			
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$nepisodi["nepisodi"].'</small>
																	</div>
																</div>
															</div>
														</div>
														');
															}
														}
														$risultato->free();
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$serie->free();	
										}
										$conn->close();
										break;

									case 3: /* 
										**********************
										*** TUTTE LE SAGHE ***
										**********************
										*/
										echo "TUTTE LE SAGHE";
										
										$conn=dbConn();
										$query="SELECT S.id, S.nome, COUNT(*) nFilm
										FROM saghe S
										JOIN video V ON S.id=V.idSaga
										GROUP BY S.id"; /* Preparazione Query: Tutte le saghe */
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutte le saghe</h2>
											</div>
											');
										if ($saghe=$conn->query($query)) { /* Query effettuata con successo */
											if ($saghe->num_rows>0) { /* Almeno un risultato */
												while ($saga = $saghe->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('	
														<div class="container text-center"> 
															<h3 class="mt-4 mb-4" >'.$saga['nome'].' - Numero film: '.$saga['nFilm'].'</h3>
														</div>
														');
													$query="SELECT id, nome, durata, sinossi
													FROM video
													WHERE idSaga=$saga[id]
													ORDER BY numero;"; /* Preparazione Query: Tutte i film della saga */
													if ($video=$conn->query($query)) { /* Query effettuata con successo */
														if ($video->num_rows>0) { /* Almeno un risultato */
															while ($film = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
																echo ('
																	<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5)">
																		<div class="card h-100 mb-4 shadow-sm">
																			<div class="card-body">
																				<img src="images/video/'.$film["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$film["nome"].'">
																				<p class="card-text">'.$film["nome"].'</p>
																				<p class="card-text-description">'.$film["sinossi"].'</p>
																				<div class="d-flex flex-row-reverse align-items-center">
																					<small class="text-muted">Durata: '.$film["durata"].' minuti</small>
																				</div>
																				
																			</div>
																		</div>
																	</div>
																');
															}
														}
													}
												}
											}
											else { /* Comunicazione mancanza di film */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film è ancora stato recensito</p>
																</div>
															</div>
														</div>
													');
											}
											$saghe->free();	
										}
										
										
										break;
									
									case 4: /* 
										***************************
										*** TUTTI I DOCUMENTARI ***
										***************************
										*/
										echo "TUTTI I DOCUMENTARI";
										break;
									
									case 5: /* 
										**********************
										*** DETTAGLI VIDEO ***
										**********************
										*/
										$id=$_GET["id"]; /* idVideo */
										$conn=dbConn();
										
										
										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { /* E' stato dato un voto */
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") /* E' stata data una recensione */
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensionevideo WHERE idVideo=$id AND idUtente=$_SESSION[idUser]"; /* Controllo che non abbia già fatto una recensione */
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { /* Pubblico o modifico la recensione */
												if($controllo->num_rows==0){ /* Non ha già recensito:  */
													$recens="INSERT INTO recensionevideo VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) /* Inserimento nel DB riuscito */
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else /* Inserimento nel DB NON riuscito */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensionevideo SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) /* Modifica del DB riuscita */
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else /* Modifica del DB NON riuscita */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { /* Eliminazione del DB riuscita */
													$recens="DELETE FROM recensionevideo WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$recens="UPDATE recensionevideo SET idAdmin=$_SESSION[idUser] WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}

										$query="SELECT V.id,V.nome,V.durata,V.idSaga,v.idSerie,V.numero,V.stagione,V.sinossi,Se.nome nomeSe,Sa.nome nomeSa 
										FROM video V LEFT JOIN serie Se ON V.idSerie=Se.id LEFT JOIN saghe Sa ON Sa.id=V.idSaga 
										WHERE V.id=$id;"; /* Preparazione Query: Dettagli video */
										$risultati=$conn->query($query);
										$video = $risultati->fetch_assoc();
										$risultati->free();

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$video["nome"].'</h1>
													<img src="images/video/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$video["nome"].'">
													<div class="container-sm col-md-6 py2">
														<p class="card-text" style="text-align:center !important">'.$video["sinossi"].'</p>
													</div>
												</div>
												</div>
												<div class="d-flex justify-content-end bd-highlight mb-3">
													<small class="text-muted">Durata: '.$video["durata"].' minuti</small>
												</div>
												<div class="row">
											');

										if($video["idSerie"]!=null)
											echo ('
												<div class="container text-left">
												<p class="card-text" onclick="passa_a('.$video["idSerie"].',6);><strong>Serie: </strong><a href="#"> '.$video["nomeSe"].'('.$video["stagione"].'x'.$video["numero"].')</a></p>
												</div>
											');
										else if($video["idSaga"]!=null)
											echo ('
													<div class="container text-left">
														<p class="card-text" onclick="passa_a('.$video["idSaga"].',7);"><strong>Saga: </strong><a href="#"> '.$video["nomeSa"].' ('.$video["numero"].'° film)</a></p>
													</div>
												');
										
										$query="SELECT Par.idPersona, Per.nome, Per.cognome, Pggi.nome nomeP 
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona JOIN interpretazioni I ON I.idAttore=Per.id JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										WHERE V.id=$id AND Par.selettore=2"; /* Preparazione Query: Attori Film */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["idPersona"].',8);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>');
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free();
										}

										$query="SELECT Par.idPersona, Per.nome, Per.cognome
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona
										WHERE V.id=$id AND Par.selettore=1"; /* Preparazione Query: Registi Film */

										if ($registi=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) {
												while ($regista = $registi->fetch_assoc()) { /* Costruisco un riquadro per ogni regista */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["idPersona"].',8)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$registi->free();
										}
																				
										$query="SELECT Par.idPersona, Per.nome, Per.cognome
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona 
										WHERE V.id=$id AND Par.selettore=3"; /* Preparazione Query: Produttori Film */

										if ($produttori=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) {
												while ($produttore = $produttori->fetch_assoc()) { /* Costruisco un riquadro per ogni produttore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["idPersona"].',8)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$produttori->free();
										}

										$query="SELECT P.* 
										FROM video V JOIN comparizioni C ON V.id=c.idVideo JOIN personaggi P ON P.id=C.idPersonaggio 
										WHERE V.id=$id"; /* Preparazione Query: Personaggi Film */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) {
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
										}
										$personaggi->free();
										
										if($_SESSION["login"]==1){
											$query="SELECT voto, testo, username FROM recensionevideo LEFT JOIN Utenti ON idAdmin=id WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
											$recensione=$conn->query($query);
											if($recensione->num_rows==0){
												echo('
													<div class="container text-center"> 
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#exampleModalCenter">
														  Lascia una recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Recensioni</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10" />
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9" />
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8" />
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7" />
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6" />
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5" />
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4" />
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3" />
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2" />
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1" />
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua recensione"></textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>');
											}
											else{
												$rec=$recensione->fetch_assoc();
												echo('
													<div class="container text-center"> 
														<h4>Il tuo voto è: '.$rec['voto'].'/10<label style="color:#ffc700">★</label></h4>
														<div class="container-sm col-md-6 mt-2 mb-2 py2">
															<p class="card-text" style="text-align:center !important">'.$rec["testo"].'</p>
														</div>
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModalCenter">
														  Modifica la tua recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10"');
																	if($rec["voto"]==10)
																		echo 'checked';
																	echo '/>
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9"';
																	if($rec["voto"]==9)
																		echo 'checked';
																	echo '/>
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8"';
																	if($rec["voto"]==8)
																		echo 'checked';
																	echo '/>
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7"';
																	if($rec["voto"]==7)
																		echo 'checked';
																	echo '/>
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6"';
																	if($rec["voto"]==6)
																		echo 'checked';
																	echo '/>
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5"';
																	if($rec["voto"]==5)
																		echo 'checked';
																	echo '/>
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4"';
																	if($rec["voto"]==4)
																		echo 'checked';
																	echo '/>
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3"';
																	if($rec["voto"]==3)
																		echo 'checked';
																	echo '/>
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2"';
																	if($rec["voto"]==2)
																		echo 'checked';
																	echo '/>
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1"';
																	if($rec["voto"]==1)
																		echo 'checked';
																	echo ('/>
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255"  placeholder="Scrivi la tua recensione">'.$rec["testo"].'</textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#conferma">Elimina recensione</button>
																
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Modifica recensione</button>
															  </div>
															</div>
														  </div>
														</div>
														
													</div>
													<!-- Modal -->
													<div class="modal fade" id="conferma" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalCenterTitle">Conferma</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
															Sei sicuro di voler eliminare la recensione? L\'operazione sarà irreversibile
														  </div>
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
															<button type="button" onclick="elimina('.$id.','.$_SESSION["idUser"].')" class="btn btn-primary">Sì</button>
														  </div>
														</div>
													  </div>
													</div>');
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												');
										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensionevideo R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idVideo=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;";
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0){
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>';
										}		
										else{
												while($riga = $recensioni->fetch_assoc()){
													echo 	
														'<div class="col-md-3 py2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																	if($riga["testo"]!=null)
																		echo '<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" data-toggle="modal" data-target="#conferma">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
															</div>
														</div>';
												}
												$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
												FROM recensionevideo R 
												INNER JOIN utenti U ON U.id=R.idUtente
												LEFT JOIN utenti A ON A.id=R.idAdmin
												WHERE R.testo IS NOT NULL AND idVideo=$id
												ORDER BY R.idAdmin DESC,R.idUtente;";
												$recensioni=$conn->query($query);
												if($recensioni->num_rows>4){
													echo '
													<div class="container text-center">
														<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModalScrollable">
														  Visualizza tutte le recensioni
														</button>
														<!-- Modal -->
														<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-scrollable" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalScrollableTitle">Recensioni degli utenti</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">';
															  while($riga = $recensioni->fetch_assoc()){
																  echo '
																	<div class="card h-100 mb-4 shadow-sm">
																		<div class="card-body">
																		<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>
																			<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																			if($riga["admin"]!=null)
																				echo '
																					<div class="d-flex justify-content-end bd-highlight mb-3">
																						<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																					</div>';
																			echo '</div>';
																		
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="elimina('.$id.','.$riga["id"].')" data-dismiss="modal">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
																		</div>';
															  }
															  echo '
															</div>
														  </div>
														</div>
													</div>
												</div>';
												
												}
										}
										$conn->close();
								
			echo ('
						</div>
					</div>
				</div>
				<div class="container"> 
					<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
				</div>
				');
									break;

									case 6: /* 
										**********************
										*** DETTAGLI SERIE ***
										**********************
										*/

										$id=$_GET["id"]; /* idSerie */
										$conn=dbConn();				

										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { /* E' stato dato un voto */
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") /* E' stata data una recensione */
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensioneserie WHERE idSerie=$id AND idUtente=$_SESSION[idUser]"; /* Controllo che non abbia già fatto una recensione */
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { /* Pubblico o modifico la recensione */
												if($controllo->num_rows==0){ /* Non ha già recensito:  */
													$recens="INSERT INTO recensioneserie VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) /* Inserimento nel DB riuscito */
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else /* Inserimento nel DB NON riuscito */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensioneserie SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) /* Modifica del DB riuscita */
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else /* Modifica del DB NON riuscita */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { /* Eliminazione del DB riuscita */
													$recens="DELETE FROM recensioneserie WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$recens="UPDATE recensioneserie SET idAdmin=$_SESSION[idUser] WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										$query="SELECT * FROM serie WHERE id=$id"; /* Preparazione Query: Dettagli Serie */
										$risultati=$conn->query($query);
										$serie = $risultati->fetch_assoc();
										$risultati->free();

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$serie["nome"].'</h1>
													<img src="images/serie/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$serie["nome"].'">
													<div class="container-sm col-md-6 py2">
														<p class="card-text" style="text-align:center !important">'.$serie["sinossi"].'</p>
													</div>
												</div>
												</div>
												<div class="row">
											');
										
										$query="SELECT Pers.*,Pggi.nome nomeP 
										FROM partecipazioni P JOIN interpretazioni I ON I.idAttore=P.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.idVideo IN 
														(SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
											AND P.selettore=2 
										GROUP BY P.idPersona"; /* Preparazione Query: Attori Serie  */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>');
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free();
										}

										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=1 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Registi Serie */

										if ($registi=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) {
												while ($regista = $registi->fetch_assoc()) { /* Costruisco un riquadro per ogni regista */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["id"].',8)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$registi->free();
										}
																				
										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=3 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Produttori Serie */

										if ($produttori=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) {
												while ($produttore = $produttori->fetch_assoc()) { /* Costruisco un riquadro per ogni produttore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["id"].',8)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$produttori->free();
										}

										$query="SELECT Pggi.* 
										FROM comparizioni C JOIN personaggi Pggi ON Pggi.id=C.idPersonaggio 
										WHERE C.idVideo IN ( 
															SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY C.idPersonaggio"; /* Preparazione Query: Personaggi Serie */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) {
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$personaggi->free();
										}
										
										
										/*
										**************************
										*****RECENSIONI SERIE*****
										**************************
										*/
										
										if($_SESSION["login"]==1){
											$query="SELECT voto, testo, username FROM recensioneserie LEFT JOIN Utenti ON idAdmin=id WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
											$recensione=$conn->query($query);
											if($recensione->num_rows==0){
												echo('
													<div class="container text-center"> 
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#exampleModalCenter">
														  Lascia una recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Recensione</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10" />
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9" />
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8" />
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7" />
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6" />
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5" />
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4" />
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3" />
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2" />
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1" />
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua recensione"></textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>');
											}
											else{
												$rec=$recensione->fetch_assoc();
												echo('
													<div class="container text-center"> 
														<h4>Il tuo voto è: '.$rec['voto'].'/10<label style="color:#ffc700">★</label></h4>
														<div class="container-sm col-md-6 mt-2 mb-2 py2">
															<p class="card-text" style="text-align:center !important">'.$rec["testo"].'</p>
														</div>
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModalCenter">
														  Modifica la tua recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10"');
																	if($rec["voto"]==10)
																		echo 'checked';
																	echo '/>
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9"';
																	if($rec["voto"]==9)
																		echo 'checked';
																	echo '/>
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8"';
																	if($rec["voto"]==8)
																		echo 'checked';
																	echo '/>
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7"';
																	if($rec["voto"]==7)
																		echo 'checked';
																	echo '/>
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6"';
																	if($rec["voto"]==6)
																		echo 'checked';
																	echo '/>
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5"';
																	if($rec["voto"]==5)
																		echo 'checked';
																	echo '/>
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4"';
																	if($rec["voto"]==4)
																		echo 'checked';
																	echo '/>
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3"';
																	if($rec["voto"]==3)
																		echo 'checked';
																	echo '/>
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2"';
																	if($rec["voto"]==2)
																		echo 'checked';
																	echo '/>
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1"';
																	if($rec["voto"]==1)
																		echo 'checked';
																	echo ('/>
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255"  placeholder="Scrivi la tua recensione">'.$rec["testo"].'</textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#conferma">Elimina recensione</button>
																
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Modifica recensione</button>
															  </div>
															</div>
														  </div>
														</div>
														
													</div>
													<!-- Modal -->
													<div class="modal fade" id="conferma" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalCenterTitle">Conferma</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
															Sei sicuro di voler eliminare la recensione? L\'operazione sarà irreversibile
														  </div>
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
															<button type="button" onclick="elimina('.$id.','.$_SESSION["idUser"].')" class="btn btn-primary">Sì</button>
														  </div>
														</div>
													  </div>
													</div>');
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												');
										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensioneserie R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idSerie=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;";
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0){
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>';
										}		
										else{
												while($riga = $recensioni->fetch_assoc()){
													echo 	
														'<div class="col-md-3 py2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																	if($riga["testo"]!=null)
																		echo '
																	<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" data-toggle="modal" data-target="#conferma">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
															</div>
														</div>';
												}
												$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
												FROM recensioneserie R 
												INNER JOIN utenti U ON U.id=R.idUtente
												LEFT JOIN utenti A ON A.id=R.idAdmin
												WHERE R.testo IS NOT NULL AND idSerie=$id
												ORDER BY R.idAdmin DESC,R.idUtente;";
												$recensioni=$conn->query($query);
												if($recensioni->num_rows>4){
													echo '
													<div class="container text-center">
														<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModalScrollable">
														  Visualizza tutte le recensioni
														</button>
														<!-- Modal -->
														<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-scrollable" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalScrollableTitle">Recensioni degli utenti</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">';
															  while($riga = $recensioni->fetch_assoc()){
																  echo '
																	<div class="card h-100 mb-4 shadow-sm">
																		<div class="card-body">
																		<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>
																			<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																			if($riga["admin"]!=null)
																				echo '
																					<div class="d-flex justify-content-end bd-highlight mb-3">
																						<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																					</div>';
																			echo '</div>';
																		
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="elimina('.$id.','.$riga["id"].')" data-dismiss="modal">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
																		</div>';
															  }
															  echo '
															</div>
														  </div>
														</div>
													</div>
												</div>';
												
												}
										}
										
										
										
										$conn->close();	
										
										echo ('
															</div>
															</div>
														</div>
													</div>
												</div>
							</div>
						</div>
					</div>
					<div class="container"> 
						<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
					</div>
					');

										break;

									case 7: /* 
										**********************
										*** DETTAGLI SAGHE ***
										**********************
										*/
										echo "DETTAGLI SAGHE";
										break;

									case 8: /* 
										************************
										*** DETTAGLI PERSONE ***
										************************
										*/
										$id=$_GET["id"]; /* idPersona */
										$conn=dbConn();
										$query="SELECT nome,cognome FROM persone WHERE id=$id;"; /* Preparazione Query: Dettagli Persona */
										$risultati=$conn->query($query);
										$persona = $risultati->fetch_assoc();
										$risultati->free();

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$persona["nome"].' '.$persona["cognome"].'</h1>
													<img src="images/persone/'.$id.'.jpg" style="max-width: 50%; height: auto;" class="mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$persona["nome"].' '.$persona["cognome"].'">
												</div>
											');

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=2 AND Per.id=$id"; /* Preparazione Query: Video da Attore */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if ($video->num_rows>0) {
												echo ('	
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Attore in</h2>
													</div>
													');

												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													');
												}
											}
											
											$video->free();
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=1 AND Per.id=$id"; /* Preparazione Query: Video da Regista */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if($video->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Regista in</h2>
														</div>
													');
												
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													');
												}
											}																				
											$video->free();
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=3 AND Per.id=$id"; /* Preparazione Query: Video da Produttore */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if($video->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttore in</h2>
														</div>
													');
												
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													');
												}
											}																				
											$video->free();
										}
										//-------------------------------------------------
										$query="SELECT Pggi.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idAttore JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										WHERE Pers.id=$id"; /* Preparazione Query: Personaggi interpretati */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											if($personaggi->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Interpreta</h2>
														</div>
													');
												
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														');
												}
											}																				
											$personaggi->free();
										}
			echo ('
						</div>
					</div>
				</div>
				<div class="container"> 
					<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
				</div>
				');
										break;
									
									case 9: /* 
											****************************
											*** DETTAGLI PERSONAGGIO ***
											****************************
											*/
										$id=$_GET["id"]; /* idPersonaggio */
										$conn=dbConn();
										$query="SELECT nome FROM personaggi WHERE id=$id"; /* Preparazione Query: Dettagli personaggio */
										$risultati=$conn->query($query);
										$personaggio = $risultati->fetch_assoc();
										$risultati->free();

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$personaggio["nome"].'</h1>
													<img src="images/personaggi/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto del personaggio '.$personaggio["nome"].'">
												</div>
											');
										
										$query="SELECT Pers.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idAttore JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio
										WHERE Pggi.id=$id"; /* Preparazione Query: Attori che hanno interpretato il personaggio */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori che lo hanno interpretato</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free();
										}

										$query="SELECT V.id, V.nome, V.durata, V.sinossi
										FROM comparizioni C JOIN video V ON V.id=c.idVideo
										WHERE C.idPersonaggio=$id"; /* Preparazione Query: Video in cui compare il personaggio */
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											echo ('	
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Video in cui e\' apparso</h2>
												</div>
												');
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free();
											$conn->close();
										}

										break;
									
									case 10: /* 
										***************
										*** RICERCA ***
										***************
										*/
										$ricerca=$_GET["ricerca"];
										$conn=dbConn();
										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
										');
										
										$query="SELECT id,nome,Sinossi FROM video WHERE selettore=1 AND nome LIKE '%$ricerca%'"; 
										
										if ($risultati=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Film</h2>
														</div>
												');
											if ($risultati->num_rows>0) {
												while ($elemento = $risultati->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["Sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$risultati->free();
										}
										
										
										$query="SELECT S.id,S.nome,S.sinossi,COUNT(*)
										FROM serie S 
										JOIN video V on V.idSerie=S.id 
										WHERE S.nome LIKE '%$ricerca%' 
										GROUP BY S.id"; 
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie</h2>
											</div>
											');
										if ($serie=$conn->query($query)) { /* Query effettuata con successo */
											if ($serie->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $serie->fetch_assoc()) { /* Costruisco un riquadro per ogni serie */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
														');
														$query="SELECT COUNT(S.id) nepisodi
														FROM serie S JOIN video V ON S.id=V.idSerie 
														WHERE V.idSerie=1"; /* Preparazione Query: Numero episodi della serie */
														if ($risultato=$conn->query($query)) { /* Query effettuata con successo */
															if ($risultato->num_rows==1) { /* Almeno un risultato */
																$nepisodi = $risultato->fetch_assoc();
													echo ('			
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$nepisodi["nepisodi"].'</small>
																	</div>
																</div>
															</div>
														</div>
														');
															}
														}
														$risultato->free();
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$serie->free();	
										}
										
										$query="SELECT S.id, S.nome, COUNT(*) nFilm
										FROM saghe S
										JOIN video V ON S.id=V.idSaga
										WHERE S.nome LIKE '%$ricerca%'
										GROUP BY S.id"; /* Preparazione Query: Tutte le saghe */
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Saghe</h2>
											</div>
											');
										if ($saghe=$conn->query($query)) { /* Query effettuata con successo */
											if ($saghe->num_rows>0) { /* Almeno un risultato */
												while ($saga = $saghe->fetch_assoc()) { /* Costruisco un riquadro per ogni saga TV */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',6)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/saghe/'.$saga["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
																	<p class="card-text">'.$saga["nome"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Film: '.$saga["nFilm"].'</small>
																	</div>
																</div>
															</div>
														</div>
													');
												}
											}
											else { /* Comunicazione mancanza di saghe */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna saga è ancora stata recensita</p>
																</div>
															</div>
														</div>
													');
											}
											$saghe->free();
										}
										
										$query="SELECT id,nome,cognome FROM persone WHERE nome LIKE '%$ricerca%' OR cognome LIKE '%$ricerca%'"; 
										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Persone</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}	
											$attori->free();
										}
										
										
										
										$query="SELECT id,nome FROM personaggi WHERE nome LIKE '%$ricerca%'"; 
										
										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											if($personaggi->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Personaggi</h2>
														</div>
													');
												
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														');
												}
											}																				
											$personaggi->free();
										}
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Saghe</h2>
											</div>
											');
											
										
										
										$conn->close();
										
										break;
									default:
										echo "<h1><strong>404. PAGE NOT FOUND</strong></h1>";
										break;
								}
		 
							?>
			</form>
		</main>
		<form name="recensioni" id="recensioni" method="post" action="
		<?php
			echo "index.php?stato=$_GET[stato]&id=$_GET[id]";
		?>
		">
			<input type='hidden' name='rate' id='rate'> <!-- Memorizzazione voto -->
			<input type='hidden' name='rec' id='rec'> <!-- Memorizzazione recensione -->
			<input type='hidden' name='idUtente' id='idUtente'> <!-- Memorizzazione recensione -->
				
		</form>
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
			<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
			<script src="assets/dist/js/bootstrap.bundle.js"></script>
	</body>
</html>