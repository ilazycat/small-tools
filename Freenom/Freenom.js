
//Login 
function login(){
	//LoginPassword = document.getElementById('LoginPassword').value;
	document.getElementById('login').innerHTML = 'Procssing...';
	Password = document.getElementById('loginPassword').value;
	ajaxLogin = new XMLHttpRequest;
	ajaxLogin.open("POST","Freenom.php",true);
	ajaxLogin.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajaxLogin.send("Option=Login&Password="+Password);
	ajaxLogin.addEventListener("load",function(){
		ajaxLoginText = ajaxLogin.responseText;
		if (ajaxLoginText == 'Success') {
			alert("Login Success!");
			loadPage('Search','NULL');
		}
		else{
			document.getElementById('inputPassword').className="form-group has-error col-xs-3 col-xs-offset-4";
			document.getElementById('login').className = "btn btn-block btn-lg btn-danger";
			document.getElementById('login').innerHTML = "Wrong Password.";
		}
	},false);
}

//Reset login form
function resetLoginForm(){
	document.getElementById('inputPassword').className="form-group col-xs-3 col-xs-offset-4";
	document.getElementById('login').className = "btn btn-block btn-lg btn-info";
	document.getElementById('login').innerHTML = "Login";
}
function resetSearchForm(){
	document.getElementById('inputDomainName').className="form-group col-xs-6 col-xs-offset-2";
	document.getElementById('search').className = "btn btn-block btn-lg btn-info";
	document.getElementById('search').innerHTML = "Search";
	document.getElementById('reservedDown').innerHTML = "";
	document.getElementById('reservedDown').className = '';
}

function loadPage(Page,DomainName){
	pageContent = new XMLHttpRequest;
	pageContent.open("POST","Freenom.php",true);
	pageContent.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	pageContent.send("Option=SwitchPage&Page="+Page+"&DomainName="+DomainName);
	pageContent.addEventListener("load",function(){
		pageContentText = pageContent.responseText;
		document.getElementById('PageContent').innerHTML = pageContentText;
	},false);
}

//For Searching domain.
function search(){
	ajaxSearch = new XMLHttpRequest;
	ajaxSearch.open("POST","Freenom.php",true);
	ajaxSearch.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	domainName = document.getElementById('domainName').value;
	document.getElementById('search').innerHTML = 'Procssing...';
	ajaxSearch.send("Option=Search&DomainName="+domainName);
	ajaxSearch.addEventListener("load",function(){
		ajaxSearchXML = ajaxSearch.responseXML;
		document.getElementById('search').innerHTML = 'Search';
		if(ajaxSearchXML.getElementsByTagName('status')[1].childNodes[0].nodeValue != 'OK'){
			document.getElementById('inputDomainName').className="form-group has-error col-xs-6 col-xs-offset-2";
			document.getElementById('search').className = "btn btn-block btn-lg btn-danger";
			document.getElementById('search').innerHTML = "Search";
			document.getElementById('reservedDown').className = 'col-xs-8 col-xs-offset-2';
			document.getElementById('reservedDown').innerHTML = "<button class='btn btn-block btn-warning' onclick=\"loadPage('Search','')\" />Domain not available. Try another. </button>";
		}
		else if(ajaxSearchXML.getElementsByTagName('result')[0].childNodes[0].nodeValue == 'DOMAIN NOT AVAILABLE'){
			document.getElementById('inputDomainName').className="form-group col-xs-6 col-xs-offset-2 has-error";
			document.getElementById('search').className = "btn btn-block btn-lg btn-danger";
			document.getElementById('search').innerHTML = "Search";
			document.getElementById('reservedDown').className = 'col-xs-8 col-xs-offset-2';
			document.getElementById('reservedDown').innerHTML = "<button class='btn btn-block btn-warning' onclick=\"loadPage('Search','')\" />Domain not available. Try another. </button>";
		}
		else if(ajaxSearchXML.getElementsByTagName('result')[0].childNodes[0].nodeValue == 'DOMAIN AVAILABLE'){
			loadPage('Regist',domainName);
		}
	},false);
}

//Regist function
function regist(){
	document.getElementById('regist').innerHTML = 'Procssing...';
	ajaxRegist = new XMLHttpRequest;
	ajaxRegist.open("POST","Freenom.php",true);
	ajaxRegist.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	domainName = document.getElementById('domainName').value;
	ajaxRegist.send("Option=Regist&DomainName="+domainName);
	ajaxRegist.addEventListener("load",function(){
		ajaxRegistXML = ajaxRegist.responseXML;
		if(ajaxRegistXML.getElementsByTagName('status')[1].childNodes[0].nodeValue == 'OK'){
			if(ajaxRegistXML.getElementsByTagName('result')[0].childNodes[0].nodeValue == 'DOMAIN REGISTERED'){
				loadPage('Success',domainName);
			}
		}
	},false);
}