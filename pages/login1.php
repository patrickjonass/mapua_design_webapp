<?PHP
session_start();

$uname = "";
$LN = "";
$pword = "";
$errorMessage = "";
$unameError = "";
$passError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	require '../configure1.php';

if (empty($_POST["username"])) {
    $unameError = "Username is required!";
  } else {
    $uname = test_input($_POST['username']);
  }
  
  if (empty($_POST["password"])) {
    $passError = "Password is required!";
  } else {
    $pword = test_input($_POST["password"]);
	$pword = md5($pword);
  }

  $db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
  $database = "design1";
  $db_found = mysqli_select_db($db_handle, $database);
  
  if (($db_found) && (!(empty($_POST["username"]))) && (!(empty($_POST["password"])))) 
  {
	  $SQL = "SELECT * FROM parentdetails WHERE PEadd = '$uname'";
	  $result = mysqli_query($db_handle, $SQL);
	  $cnt = mysqli_num_rows($result);
	  
	  if ($cnt == 1) 
	  {
		$db_field = mysqli_fetch_assoc($result);
		if ($pword == $db_field['P_Pass'])
		{
			session_start();
			$_SESSION['login'] = "1";
			$_SESSION['uname'] = $uname;
			$_SESSION['LN'] = $db_field['PLN'];
			header ("Location: page1.php");
		}
		else 
		{
			$errorMessage = "Invalid username or password!";
			session_start();
			$_SESSION['login'] = '';
		}

	  }
	  else
	  {
		  $errorMessage = "Invalid username or password!";
	  }
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
  }
  
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<title>Casa Consuelo Dormitory</title>
<link rel="Stylesheet" TYPE="text/css" HREF="asd.css">
</head>
<body>

<FORM NAME ="form1" METHOD ="POST" ACTION ="login1.php">
<h1> Casa Consuelo Dormitory </h1>
<h3> Parent's Login </h3>
<P>
Username: <INPUT TYPE = 'TEXT' Name ='username'  value="<?PHP print $uname;?>" maxlength="50"> 
<span class="error"> <?PHP print $unameError; ?> </span>
</P> 
<P>
Password: <INPUT TYPE = 'PASSWORD' Name ='password'  maxlength="50"> 
<span class="error"> <?PHP print $passError; ?> </span>
</P> 
<P>
<INPUT TYPE = "Submit" Name = "Submit1"  VALUE = "Login">
</P>

</FORM>



<span class="error"><?PHP 
	print $errorMessage;

?></span>



</body>
</html>