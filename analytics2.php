<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!--global stylesheet begin-->  
    <link href='http://fonts.googleapis.com/css?family=Unica+One' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href="css/reset-min.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="css/analytics.css" media="screen" rel="stylesheet" type="text/css" />
    <!--global stylesheet end-->
</head>
<body>
	<div id="container-report">
		<h2 id="logo-analytics"></h2>
		<ul id="report-list">
<?
	include_once "_misc.php";
	$strSQL = "SELECT * FROM sql_queries";
	//echo "<BR>$strSQL<BR>";
	$reportListInt = 0;
	if ($results = mysql_query($strSQL)) {
		while ($row = mysql_fetch_array($results)) {
//			pr($row);
			//$customerId = $row[id];
			$reportListInt +=1;
			?>
			<!--
				<a class="btn-report" href="analytics.php?function=runquery&queryid=<?echo $row[id];?>">
					<?echo $row[label];?>
				</a>
			-->
			<li>
				<dl>
				  <dt><a class="btn-report" href="analytics.php?function=runquery&queryid=<?=$row[id];?>">#<?=$reportListInt?></dt>
					<dd><a class="btn-report" href="analytics.php?function=runquery&queryid=<?=$row[id];?>"><?=$row[label];?></dd>
				</dl>
			</li>
			<?
		}
	}

	if ($function == "runquery") {

		$strSQL = "SELECT * FROM sql_queries WHERE id='$queryid'";

		if ($results = mysql_query($strSQL)) {

			while ($row = mysql_fetch_array($results)) {

//				pr($row);

//    [headers] => 'level','number who have qualified'

//$headers = explode(",", $row[headers]);

//pr($headers);

				//$customerId = $row[id];

				runAnalyticsQuery($row[query], explode(",", $row[headers]));

			}

		}

	}
?>



		
		<h2 id="logo-foundation"></h2>
	</div>
</body>
</html>

<?
	function runAnalyticsQuery($strSQL, $headers) {

		echo "<BR>$strSQL<BR>";

		//pr($headers);

		if ($results = mysql_query($strSQL)) {

			startTable($headers);

			$j=0;

			while ($row = mysql_fetch_array($results)) {
				//$customerId = $row[id];
			?>
			<TR BGCOLOR="<?echo $j++%2?"DDFFDD":"FFFFFF";?>"><?

				$i=0;

				foreach ($row AS $element) {

					if (!($i++%2)) {

					?>

						<TD ALIGN=RIGHT><?echo $element;?></TD>

					<?

}

				}

				?></TR><?

			}

			?></TABLE><?

		}

	}

////

////

	function startTable($headers) {

		?>

			<TABLE BORDER=1>

				<TH BGCOLOR="CCCCCC">

					<?

						foreach ($headers AS $header) {

							?>

								<TD><CENTER><B><?echo trim($header, "'");?></B></CENTER></TD>

							<?

						}

					?>

				</TH>

		<?

	}

////

////

?>
