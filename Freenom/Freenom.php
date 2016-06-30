<?php
/*
 *	Author LazyCat
 *	Email LazyCat@iLazyCat.com
 *	Site http://iLazyCat.com
 *	A simple PHP program
*/
//Set basic variables.
$fnemail = 'freenom_email';
$fnpasswd = base64_decode('freenom_password_base64');
$user = 'username';
$passwd = 'scriptpassword';

// A simple function check whether you are admin.
function isAdmin(){
	global $user,$passwd;
	if(isset($_COOKIE['User']) && isset($_COOKIE['Auth'])){
		if($_COOKIE['User'] == $user && $_COOKIE['Auth'] == str_split(md5($passwd),10)[0]){
			return True;
		}
		else{
			return False;
		}
	}
	else{
		return False;
	}
}

//Get Current URL
function curPageURL(){
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	}
	else{
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//Main functions.
if(isset($_POST['Option'])){
	switch ($_POST['Option']) {
		case 'Test':
			echo "Test Successfully <br />";
			break;
		//Check username and password and login if correct.
		case 'Login':
			if ($_POST['Password'] == $passwd) {
				setcookie("User",$user);
				setcookie("Auth",str_split(md5($passwd),10)[0]);
				echo "Success";
			}
			else{
				echo "Failed";
			}
			break;
		case 'Search':
			$url="https://api.freenom.com/v2/domain/search.xml";
			header('Content-type: text/xml');
			echo file_get_contents($url."?domainname=".$_POST['DomainName']."&domaintype=FREE");
			break;
		case 'Regist':
			header('Content-type: text/xml');
			$url="https://api.freenom.com/v2/domain/register.xml";
			$url.="?domainname=".$_POST['DomainName'];
			$url.="&period=12M";
			$url.="&nameserver=A.DNSPOD.COM";
			$url.="&nameserver=B.DNSPOD.COM";
			$url.="&nameserver=C.DNSPOD.COM";
			$url.="&email=".$fnemail;
			$url.="&password=".$fnpasswd;
			$url.="&domaintype=FREE";
			$url.="&method=POST";
			echo file_get_contents($url);
			break;
		case 'SwitchPage':
			if(!isAdmin()){header("Location: ".curPageURL());}
			else{
				switch ($_POST['Page']) {
					default:
					case 'Search':
				?>
						<div id="Center" class="Center">
							<div id="reservedUp"></div>
							<div id="inputDomainName" class="form-group col-xs-6 col-xs-offset-2">
								<input type="text" id="domainName" class="form-control" placeholder="Domain Name.   .ML .TK .CF .GA and .GQ is supported." onkeydown="resetSearchForm()" />
							</div>
							<div id="domainButton" class="col-xs-2">
								<button id="search" class="btn btn-block btn-lg btn-info" onclick="search()">Search</button>
							</div>
							<div id="reservedDown"></div>
						</div>
				<?php	break;
					case 'Regist':
				?>
						<div id="Center" class="Center">
							<div id="reservedUp"></div>
							<div id="inputDomainName" class="form-group col-xs-6 col-xs-offset-2">
								<input type="text" id="domainName" class="form-control" value=<?php echo $_POST['DomainName']; ?> onkeydown="loadPage('Search','')" />
							</div>
							<div id="domainButton" class="col-xs-2">
								<button id="regist" class="btn btn-block btn-lg btn-info" onclick="regist()">Regist!</button>
							</div>
							<div id="reservedDown">
								<p> <br /><br /><br />
									Default regist period is 12 months. <br />
									Default DNS is A.DNSPOD.COM B.DNSPOD.COM C.DNSPOD.COM <br />
									You can modify DNS records on <a href="https://www.dnspod.com/">DNSPOD.COM</a><br />
									More function is under development. Enjoy it.<br />
								</p>
							</div>
						</div>
				<?php	break;
					case 'Success':
				?>
						<div id="Center" class="Center">
							<div id="reservedUp">
								<h4>
									<?php echo $_POST['DomainName']; ?> Registed successfully!
								</h4>
							</div>
							<div id="reservedDown">
								<div id="RegistAnother" class="col-xs-4 col-xs-offset-4">
									<button id="RegistAnotherButton" class="btn btn-block btn-lg btn-info" onclick="loadPage('Search','')">Regist Another!</button>
								</div>
								<p> <br /><br /><br />
									Default regist period is 12 months. <br />
									Default DNS is A.DNSPOD.COM B.DNSPOD.COM C.DNSPOD.COM <br />
									You can modify DNS records on <a href="https://www.dnspod.com/">DNSPOD.COM</a><br />
									More function is under development. Enjoy it.<br />
								</p>
							</div>
						</div>
				<?php	break;
				}
			}
			break;
		default:
			header("Location: ".curPageURL());
			break;
	}
}
else{
	if(!isAdmin()){
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
			<title>Freenom Domain Regist Program.</title>
			<link rel="stylesheet" type="text/css" href="http://lazycat.qiniudn.com/FlatUI/dist/css/vendor/bootstrap.min.css" />
			<link rel="stylesheet" type="text/css" href="http://lazycat.qiniudn.com/FlatUI/dist/css/flat-ui.css" />
			<link rel="stylesheet" type="text/css" href="Freenom.css" />
		</head>
		<body>
			<div id="PageContent" class="PageContent wrapper">
				<div id="Center" class="Center">
					<div id="LoginForm">
						<div id="inputPassword" class="form-group col-xs-3 col-xs-offset-4">
							<input type="password" id="loginPassword" class="form-control" placeholder="Password" style="text-align:center;" onkeypress="resetLoginForm()" />
						</div>
						<div id="loginButton" class="col-xs-3 col-xs-offset-4">
							<button id="login" class="btn btn-block btn-lg btn-info" onclick="login()">Login</button>
						</div>
					</div>
				</div>
			</div>
		</body>
		<footer>
			<script src="http://lazycat.qiniudn.com/FlatUI/dist/js/vendor/jquery.min.js"></script>
			<script src="http://lazycat.qiniudn.com/FlatUI/dist/js/flat-ui.min.js"></script>
			<script src="Freenom.js"></script>
		</footer>
		</html>
	<?php }
	else if (!isset($_POST['Option'])){ ?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
			<title>Freenom Domain Regist Program.</title>
			<link rel="stylesheet" type="text/css" href="http://lazycat.qiniudn.com/FlatUI/dist/css/vendor/bootstrap.min.css" />
			<link rel="stylesheet" type="text/css" href="http://lazycat.qiniudn.com/FlatUI/dist/css/flat-ui.css" />
			<link rel="stylesheet" type="text/css" href="Freenom.css" />
		</head>
		<body onload="loadPage('Search','NULL')">
			<div id="PageContent" class="PageContent wrapper">
			</div>
		</body>
		<footer>
			<script src="http://lazycat.qiniudn.com/FlatUI/dist/js/vendor/jquery.min.js"></script>
			<script src="http://lazycat.qiniudn.com/FlatUI/dist/js/flat-ui.min.js"></script>
			<script src="Freenom.js"></script>
		</footer>
		</html>
	<?php }
}
?>
