<!DOCTYPE HTML>
<!--
	This is the basic structure of the document, written by Ben Saletta
	all design, functionalty, and content will be added via external documents
	
	
	
-->	
<head>
	<title>Charity Chain</title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<?php include 'script/functions.php'; ?>
</head>	
<body>
	<div id="loginForm">
		<span id="login">Username:<input type="text" name="uname" onbeforedeactivate="if(document.getElementById('signup').style.visibility=='visible') ajax(1,this.value)" /><br/>
						 Password:<input type="password" name="paswd"/><br/>
						 <input type="submit" value="login" id="loginButton"/> or <a href="" id="signupButton" onclick="showSignup()">Signup</a>
		</span>
		<span id="signup">
			Confirm Password:<input type="password" name="conf paswd">
			Email:<input type="email" name="email"/>
			<input type="button" value="Signup" />
		</span>
		</div>
		<div id="title">
		 <h1 id="Heading">Charity Chain</h1>
		 <h3 id="Subheading">Social Networking giving back to society</h3>
		 </div>
	<div id="main">
		<?php echo contentGen("content.xml", "main_discription"); ?>
		 <input type="button" value="JS test" onclick="userCheck()" />
		 </div>
		 <span id="testspace"><?php #db('verify','1'); ?></span>
	<div id="nav">
		Hey this is the NAV and it loaded!!!
		 </div>
	<script type="text/javascript" src="script/script.js"></script> <!-- JavaScript loaded at the bottom for more efficent page loading -->
</body>