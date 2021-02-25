<?
$letter[0]		= rand(1,8);
$letter[1]		= rand(0,9);
$letter[2]		= rand(0,9);
$letter[3]		= rand(0,9);
$letter[4]		= rand(0,9);
$letter[5]		= rand(0,9);
$letter[6]		= rand(0,9);
$letter[7]		= rand(0,9);
$letter[8]		= rand(0,9);
$letter[9]		= rand(0,9);
$letter[10]		= rand(0,9);
$letter[11]		= rand(0,9);
for($i=0;$i<13;$i++){
	//$letter[$i] = rand(0,9);
	$multi[$i] = (13-$i) * $letter[$i];
}
$summ = array_sum($multi);
$c = $summ%11;
if($c>=2){$d = 11-$c;}else{if($c==0){$d=1;}else{$d=0;}}
$letter[12] = $d;
$tssn = $letter[0].$letter[1].$letter[2].$letter[3].$letter[4].$letter[5].$letter[6].$letter[7].$letter[8].$letter[9].$letter[10].$letter[11].$letter[12];
print("Generated SSN for Thai is : ");
print("<font color=red>".$tssn."</font>");
?>
<BR>Check with :
<a href="http://visa.ini3.co.th/register/ChkIDCard.asp" target="_blank">INI3 VISA</a> 
or <a href="https://passport.asiasoft.co.th/onlinepassport/registerCenter/MasterRegisterAP.asp
" target="_blank">Asiasoft Passport</a>

<?
print("<BR><BR>");
?> <Table><TR><TD>Position</TD><TD>Number</TD><TD>Multiply</TD><TD>Result</TD><TD>Summary</TD></TR>
<?
$sumtopos = 0;
for($i=0;$i<12;$i++){
	$sumtopos = $sumtopos + $multi[$i];
	print("<TR><TD>".($i+1)."</TD><TD><font color=red>".$letter[$i]."</font></TD><TD>".(13-$i)."</TD><TD>".$multi[$i]."</TD><TD>".$sumtopos."</TD></TR>");
}
?>
</table>
Summary % 11 =
<?
print($sumtopos%11);
?><BR>
Last number is <font color=red>
<?
if($sumtopos%11>=2){print(11-$c);}else{if($sumtopos%11==0){print('1');}else{print('0');}}
?>
</font>
<BR><BR>
