<?PHP
session_start();
$uname = $_SESSION['uname']; 
$LN = $_SESSION['LN'];                  //added line
$dateErr = "";
$childErr = "";
$date = "";
$child = "";
$errorMes = "";

if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	header ("Location: login1.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	require '../configure1.php';
	
if (empty($_POST["date"])) {
    $dateErr = "Enter date!";
  } else {
    $date = test_input($_POST['date']);
  }
  
 if (empty($_POST["child"])) {
    $childErr = "Enter child's first name!";
  } else {
    $child = test_input($_POST['child']);
  }

  $db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
  $database = "design1";
  $db_found = mysqli_select_db($db_handle, $database);
  
  if ($db_found) {
	if ((!(empty($_POST["date"]))) && (!(empty($_POST["child"])))) {
	$SQL = "SELECT 
			TIME(TofEntry),
			TIME(TofExit),
			R_LN,
			R_FN,
			DATE(TofExit), 
			DATE(TofEntry)
		FROM 
			logbook l
				INNER JOIN
			residentdetails r ON l.R_LN = r.RLN AND l.R_FN = r.RFN AND l.R_MN = r.RMN 
WHERE
	r.P_EaddR = '$uname' AND (DATE(TofExit) = '$date' OR DATE(TofEntry) = '$date') AND R_FN = '$child'";
$result = mysqli_query($db_handle, $SQL);
$cnt = mysqli_num_rows($result);
	if ($cnt > 0) {
		$name = array();
		$entry = array();
		$exit = array();
		
		while ($db_field = mysqli_fetch_assoc($result))
		{
			
			$name[] = $db_field['R_FN'];
			$entry[] = $db_field['TIME(TofEntry)'];
			$exit[] = $db_field['TIME(TofExit)'];
		}
	}
	else {
		$errorMes = "No results found! Please check first name and/or date!";
	}
	
	}
	else {
		$errorMes = "Complete all fields!";
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
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<head>
	<title>Casa Consuelo Dormitory</title>
	<link rel="Stylesheet" TYPE="text/css" HREF="asd.css">

	</head>
	<body>




	<H1>Welcome Mr. and Mrs. <?php echo $LN; ?></H1>
	<form method="POST" action="page1.php">
	<p>Enter date in format(YYYY-MM-DD):<INPUT TYPE = 'TEXT' Name ="date"  value="<?PHP print $date;?>" maxlength="50"> 
	<span class="error"> <?php print $dateErr; ?> </p> </span>
	<p>Enter your child's first name:<INPUT TYPE = 'TEXT' Name ="child"  value="<?PHP print $child;?>" maxlength="50"> 
	<span class="error"> <?php print $childErr; ?> </p> </span>
	<p> <INPUT TYPE = "Submit" NAME = "sub1" VALUE = "Go"> 
	<span class="error"> <?php print $errorMes; ?> </span> </p>
	</form>	
	<TABLE>
<TR>
<TD>Name</TD>
<TD>Time of Entry:</TD>
<TD>Time of Exit:</TD>
</TR>
<TR>
<TD><?php foreach ($name as $n) echo $n."<br>"; ?></TD>
<TD><?php foreach ($entry as $en) echo $en."<br>"; ?></TD>
<TD><?php foreach ($exit as $ex) echo $ex."<br>"; ?></TD>
</TR>
</TABLE>

<P>
<A HREF = changepass.php>Change Password</A>
<A HREF = logout.php>Log out</A>
</P>
	</body>
	</html>
