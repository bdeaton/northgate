﻿<?
	include_once "_misc.php";

	$redemption_code	= $_REQUEST["redemption_code"];
$phoneNumberEntered	= $_REQUEST["phonenumber"];
	$managerid			= $_REQUEST["managerid"];

	showHeader();
?>
<?
	function getMobileNumberFromCustomerId($customerId) {
		$strSQL = "SELECT * FROM customers WHERE id='$customerId'";
		$mobileNumber = 0;
		if ($results = mysql_query($strSQL)) {
			while ($row = mysql_fetch_array($results)) {
				$mobileNumber = $row[mobilenumber];
			}
		}
		return $mobileNumber;
	}
?>
<?

	$strSQL = "SELECT * FROM redemption_codes WHERE code='$redemption_code'";

	$validCode = false;
$phoneNumbersMatch = false;
	$codeAlreadyUsed = false;
	if ($results = mysql_query($strSQL)) {
		if ($row = mysql_fetch_array($results)) {
			$validCode = true;
			$redeemedTimestamp = $row[dateandtime_redeemed];
			$redeemedManagerId = $row[redeemed_by_manager_id];
			$customerId = $row[customer_id];

			// If the redemption code has not been used yet, then it is still redeemable
			if (($redeemedTimestamp == 0) && ($redeemedManagerId == 0)) {
// check to see if the phone numbers match
//echo "do phone numbers match...look up phone number for customer: $customerId<BR>";
$customerMobileNumber = getMobileNumberFromCustomerId($customerId);
//echo "their mobile number is .$customerMobileNumber.<BR>";
//echo "phone number entered is .$phoneNumberEntered.<BR>";
if ($customerMobileNumber == $phoneNumberEntered) {
	$phoneNumbersMatch = true;
}
//exit;
			}
			else {
				$codeAlreadyUsed = true;
			}
		}
	}

?>
	<TR>
		<TD>
			<CENTER><? displayContents($redemption_code, $validCode, $codeAlreadyUsed, $phoneNumbersMatch, $redeemedTimestamp, $redeemedManagerId, $managerid); ?></CENTER>
		</TD>
	</TR>
	<TR HEIGHT=5><TD></TR></TD>
<?
	showFooter();
?>
<?
////
////
	function displayContents($redemptionCode, $validCode, $codeAlreadyUsed, $phoneNumbersMatch, $redeemedTimestamp, $redeemedManagerId, $loggedInManagerId) {

		if ($validCode) {
			if ($codeAlreadyUsed) {

				$date = date("n/j/y", $redeemedTimestamp);
				$time =  date("g:ia", $redeemedTimestamp-(9*60*60));
				$id = $redeemedManagerId;

				echo "Redemption code $redemptionCode was already redeemed on $date at $time by manager #$id.<BR>";
				?>
				<BR>
				<BR>
				<A HREF="validate_redemptioncode.php?managerid=<?echo $loggedInManagerId;?>&redemption_code=<?echo $redemptionCode;?>">Try another Redemption Code</A>
				<?
			}
else if (!$phoneNumbersMatch) {
	echo "phone numbers do not match<BR>";
	?>
		<BR>
		<BR>
		<A HREF="validate_redemptioncode.php?managerid=<?echo $loggedInManagerId;?>&redemption_code=<?echo $redemptionCode;?>">Try again</A>
	<?
}
			else {
				//echo "code is still redeemable<BR>";
				showRedemptionForm($redemptionCode, $loggedInManagerId);
			}
		}
		else {
			echo "invalid code: $redemptionCode<BR>";
			?>
				<BR>
				<BR>
				<A HREF="validate_redemptioncode.php?managerid=<?echo $loggedInManagerId;?>&redemption_code=<?echo $redemptionCode;?>">Try again</A>
			<?
		}

	}
////
////
	function showRedemptionForm($redemptionCode, $loggedInManagerId) {

		?>

			The code <?echo $redemptionCode;?> is still valid and unused.<BR>
			Enter the ticket id's below and press Redeem and hand the
			tickets to the customer.
			<BR>
			<BR>

			<FORM ACTION="redeem_code_for_tickets.php">
				<INPUT TYPE=HIDDEN NAME=redemption_code VALUE="<?echo $redemptionCode;?>">
				<INPUT TYPE=HIDDEN NAME=managerid VALUE="<?echo $loggedInManagerId;?>">
				<TABLE BORDER=0>
					<TR>
						<TD>Ticket 1 ID:</TD>
						<TD><INPUT TYPE=TEXT NAME=ticket1 ID=ticket1></TD>
					</TR>
					<TR>
						<TD>Ticket 2 ID:</TD>
						<TD><INPUT TYPE=TEXT NAME=ticket2 ID=ticket2></TD>
					</TR>
					<TR HEIGHT=20>
						<TD></TD>
						<TD></TD>
					</TR>
				</TABLE>
				<INPUT TYPE=SUBMIT VALUE="Redeem">
			</FORM>

			</CENTER>
			<!--<A HREF="managerlogin.php">Cancel</A>-->
			<A HREF="validate_redemptioncode.php?managerid=<?echo $loggedInManagerId;?>&redemption_code=<?echo $redemptionCode;?>">Cancel</A>
			<CENTER>
			<BR>
			<BR>

		<?

	}
////
////
?>