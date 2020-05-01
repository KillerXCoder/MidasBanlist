<?php
date_default_timezone_set ("Europe/Bratislava");
$servername = 'localhost';
$username = '';
$password = '';
$dbname = 'litebans';
$conn = new mysqli($servername, $username, $password, $dbname);
$conn -> set_charset("utf8");
$sql = "SELECT * FROM litebans_mutes ORDER BY id DESC";
$result = $conn->query($sql);
if (isset($_GET['strana'])) {
$strana = $_GET['strana'];
} else {
	$strana = 1;
}
$pocet_na_stranu = 10;
$offset = ($strana-1) * $pocet_na_stranu; 
$celkovo_stranky = ceil($result->num_rows / $pocet_na_stranu);
$celkovo_bany = $result->num_rows;


$sql = "SELECT * FROM litebans_mutes ORDER BY id DESC LIMIT ".$offset.",". $pocet_na_stranu;
$result = $conn->query($sql);


echo '<style>

.padd th, .padd td { padding: 10px 10px; vertical-align: middle }
.lh { line-height: 24px; }

.color tr:nth-child(even) { background: #ebebeb; }
.color tr:nth-child(odd) { background: #FFF; border: none;}

.mnu th { padding: 0; color: white; transition: 0.25s ease-out; background: #2A2A2A; border: none;  vertical-align: middle }
.mnu th:first-child { border-right: 1px solid rgba(255, 255, 255, 0.1); }
.mnu th:hover { background: #E64946 }
.mnu a, .mnu a:hover { padding: 10px 10px; color: white; text-decoration: none; display: block; height: 100% width: 100%; }

</style>';
echo '
<style>
.pagination {

}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
 }
.hladat{
  width: 20%;
  padding: 10px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
.potvrdit{
  width: 10%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.pravidla{

  width: 10%;
  background-color: #303030;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color:white !IMPORTANT;
  text-decoration:none !IMPORTANT;
}
.pravidla:hover{

  width: 10%;
  background-color: #ff0000;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color:white !IMPORTANT;
  text-decoration:none !IMPORTANT;
}

@media all and (max-width: 694px) {
	.pravidla{

	display:block;
	margin:0px;
	width: 90%;
	}
	
	.pravidla:hover{

	background-color: #ff0000;
	display:block;
	margin:0px;
	width: 90%;

	}
}

</style>';
echo "<h1 style='text-align:center;'>Umlčania</h1><br>";
echo '<div style="text-align: center">';
echo '<a href="https://midascraft.sk/pravidla/" class="pravidla">PRAVIDLÁ</a> &nbsp&nbsp&nbsp&nbsp';
echo '<a href="https://midascraft.sk/forum/forum/ziadosti-o-unban/" class="pravidla">UNBAN</a>&nbsp&nbsp&nbsp&nbsp';
echo '<a href="https://midascraft.sk/bany2/" class="pravidla">BANY</a>&nbsp&nbsp&nbsp&nbsp';
echo '<a href="https://midascraft.sk/upozornenia/" class="pravidla">UPOZORNENIA</a>&nbsp&nbsp&nbsp&nbsp';
echo '<a href="https://midascraft.sk/vykopnutia/" class="pravidla">VYKOPNUTIA</a>';
echo '</div>';
echo "<br><p style='text-align:center;'>Celkový počet aktívnych umlčaní: ";
echo "<h2 style='text-align:center; font-weight:bold;'><i class='fas fa-microphone-alt-slash'></i> ". $celkovo_bany . "</h2></p>";
echo '<br>
<form style="text-align:center" method="get">
  <input type="search" class="hladat" placeholder="Meno…" value="" name="nick" style="float:center">
  <input type="submit" class="potvrdit" value="Nájdi" style="float:center">
  </form>';
  
  
  
if (isset($_GET['nick'])) {
	$nickname = $_GET['nick'];
	$sql2 = "SELECT * FROM litebans_history WHERE name=\"". $nickname . "\"";
	$result2 = $conn->query($sql2);
	if($result2->num_rows > 0){
		while($row2 = $result2->fetch_assoc()) {
			$uuid = $row2['uuid'];
		}
	}
	else{
		$uuid = "---";
	}
	$sql = "SELECT * FROM litebans_bans WHERE uuid=\"" . $uuid . "\" ORDER BY id DESC ";
	$result = $conn->query($sql);
	echo'<h3>Bany:</h3><br>';
	if ($result->num_rows > 0) {
		echo '<div style="overflow-x:auto !IMPORTANT;">';
		echo '<table class="widefat color padd"><tbody>';
		echo '<tr><th width=5% style="text-align: center">Herný nick</th><th width=5% style="text-align: center">Ban udelil</th><th width=40% style="text-align: center">Dôvod</th><th width=20% style="text-align: center">Dátum udelenia</th><th width=20% style="text-align: center">Dátum expirácie</th></tr>';
		
		while($row = $result->fetch_assoc()) {
			if(!empty($row['uuid'])){
				$sql2 = "SELECT * FROM litebans_history WHERE uuid=\"". $row['uuid'] . "\"";
				$result2 = $conn->query($sql2);
				if($result2->num_rows > 0){
					while($row2 = $result2->fetch_assoc()) {
						$meno2 = $row2['name'];
						$meno3 = $row2['name'];
					}
				}
				else{
					$meno2 = "Neznámy nick";
					$meno3 = "MHF_Question";
				}
			}
			else{
				$meno2 = "---";
				$meno3 = "MHF_Question";
			}
			
			if($row['until'] == -1){
				$koniec = "Trvalý ban";
			}
			else{
				if($row['active'] == 1){
					$koniec = date("d.m.Y H:i",$row['until']/1000);
				}
				else{
					$koniec = date("d.m.Y H:i",$row['until']/1000) . " (vypršal)" ;
				}
			}
			
			if($row['banned_by_name'] == "Administrator"){
				$meno4 = "Console";
			}
			else{
				$meno4 = $row['banned_by_name'];
			}
			
			echo '<tr><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno3.'" width="24px" height="24px"> <br>'.$meno2 .'</td><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno4.'" width="24px" height="24px"> <br>'. $row['banned_by_name'] .'</td><td align="center">'. $row['reason'] .'</td><td align="center">'. date("d.m.Y H:i",$row['time']/1000) .'</td><td align="center">'. $koniec .'</td></tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
	} 
	else
	{
	echo "<br><h3 style='text-align:center; color:red;'>Hráč s nickom " . $nickname . " u nás nemá žiaden ban!</h3>";
	}
	echo'<br><h3>Umlčania:</h3>';
	$sql = "SELECT * FROM litebans_mutes WHERE uuid=\"" . $uuid . "\" ORDER BY id DESC ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo '<div style="overflow-x:auto !IMPORTANT;">';
		echo '<table class="widefat color padd"><tbody>';
		echo '<tr><th width=5% style="text-align: center">Herný nick</th><th width=5% style="text-align: center">Umlčanie udelil</th><th width=40% style="text-align: center">Dôvod</th><th width=20% style="text-align: center">Dátum udelenia</th><th width=20% style="text-align: center">Dátum expirácie</th></tr>';
		
		while($row = $result->fetch_assoc()) {
			if(!empty($row['uuid'])){
				$sql2 = "SELECT * FROM litebans_history WHERE uuid=\"". $row['uuid'] . "\"";
				$result2 = $conn->query($sql2);
				if($result2->num_rows > 0){
					while($row2 = $result2->fetch_assoc()) {
						$meno2 = $row2['name'];
						$meno3 = $row2['name'];
					}
				}
				else{
					$meno2 = "Neznámy nick";
					$meno3 = "MHF_Question";
				}
			}
			else{
				$meno2 = "---";
				$meno3 = "MHF_Question";
			}
			
			if($row['until'] == -1){
				$koniec = "Trvalé umlčanie";
			}
			else{
				if($row['active'] == 1){
					$koniec = date("d.m.Y H:i",$row['until']/1000);
				}
				else{
					$koniec = date("d.m.Y H:i",$row['until']/1000) . " (vypršalo)" ;
				}
			}
			
			if($row['banned_by_name'] == "Administrator"){
				$meno4 = "Console";
			}
			else{
				$meno4 = $row['banned_by_name'];
			}
			
			echo '<tr><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno3.'" width="24px" height="24px"> <br>'.$meno2 .'</td><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno4.'" width="24px" height="24px"> <br>'. $row['banned_by_name'] .'</td><td align="center">'. $row['reason'] .'</td><td align="center">'. date("d.m.Y H:i",$row['time']/1000) .'</td><td align="center">'. $koniec .'</td></tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
	} 
	else
	{
	echo "<br><h3 style='text-align:center; color:red;'>Hráč s nickom " . $nickname . " u nás nebol umlčaný!</h3>";
	}
	echo'<br><h3>Upozornenia:</h3>';
	$sql = "SELECT * FROM litebans_warnings WHERE uuid=\"" . $uuid . "\" ORDER BY id DESC ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo '<div style="overflow-x:auto !IMPORTANT;">';
		echo '<table class="widefat color padd"><tbody>';
		echo '<tr><th width=5% style="text-align: center">Herný nick</th><th width=5% style="text-align: center">Upozornenie udelil</th><th width=40% style="text-align: center">Dôvod</th><th width=20% style="text-align: center">Dátum udelenia</th><th width=20% style="text-align: center">Dátum expirácie</th></tr>';
		
		while($row = $result->fetch_assoc()) {
			if(!empty($row['uuid'])){
				$sql2 = "SELECT * FROM litebans_history WHERE uuid=\"". $row['uuid'] . "\"";
				$result2 = $conn->query($sql2);
				if($result2->num_rows > 0){
					while($row2 = $result2->fetch_assoc()) {
						$meno2 = $row2['name'];
						$meno3 = $row2['name'];
					}
				}
				else{
					$meno2 = "Neznámy nick";
					$meno3 = "MHF_Question";
				}
			}
			else{
				$meno2 = "---";
				$meno3 = "MHF_Question";
			}
			
			if($row['until'] == -1){
				$koniec = "Trvalé upozornenie";
			}
			else{
				if($row['active'] == 1){
					$koniec = date("d.m.Y H:i",$row['until']/1000);
				}
				else{
					$koniec = date("d.m.Y H:i",$row['until']/1000) . " (vypršalo)" ;
				}
			}
			
			if($row['banned_by_name'] == "Administrator"){
				$meno4 = "Console";
			}
			else{
				$meno4 = $row['banned_by_name'];
			}
			
			echo '<tr><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno3.'" width="24px" height="24px"> <br>'.$meno2 .'</td><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno4.'" width="24px" height="24px"> <br>'. $row['banned_by_name'] .'</td><td align="center">'. $row['reason'] .'</td><td align="center">'. date("d.m.Y H:i",$row['time']/1000) .'</td><td align="center">'. $koniec .'</td></tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
	} 
	else
	{
	echo "<br><h3 style='text-align:center; color:red;'>Hráč s nickom " . $nickname . " u nás nemá žiadne upozornenie!</h3>";
	}
	echo'<br><h3>Vykopnutia:</h3>';
	$sql = "SELECT * FROM litebans_kicks WHERE uuid=\"" . $uuid . "\" ORDER BY id DESC ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo '<div style="overflow-x:auto !IMPORTANT;">';
		echo '<table class="widefat color padd"><tbody>';
		echo '<tr><th width=5% style="text-align: center">Herný nick</th><th width=5% style="text-align: center">Hráča vykopol</th><th width=40% style="text-align: center">Dôvod</th><th width=20% style="text-align: center">Dátum vykopnutia</th><th width=20% style="text-align: center">Server</th></tr>';
		
		while($row = $result->fetch_assoc()) {
			if(!empty($row['uuid'])){
				$sql2 = "SELECT * FROM litebans_history WHERE uuid=\"". $row['uuid'] . "\"";
				$result2 = $conn->query($sql2);
				if($result2->num_rows > 0){
					while($row2 = $result2->fetch_assoc()) {
						$meno2 = $row2['name'];
						$meno3 = $row2['name'];
					}
				}
				else{
					$meno2 = "Neznámy nick";
					$meno3 = "MHF_Question";
				}
			}
			else{
				$meno2 = "---";
				$meno3 = "MHF_Question";
			}
			
			if(empty($row['server_origin'])){
				$koniec = "-";
			}
			else{
				$koniec = $row['server_origin'];
			}
			if($row['banned_by_name'] == "Administrator"){
				$meno4 = "Console";
			}
			else{
				$meno4 = $row['banned_by_name'];
			}
			
			
			echo '<tr><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno3.'" width="24px" height="24px"> <br>'.$meno2 .'</td><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno4.'" width="24px" height="24px"> <br>'. $row['banned_by_name'] .'</td><td align="center">'. $row['reason'] .'</td><td align="center">'. date("d.m.Y H:i",$row['time']/1000) .'</td><td align="center">'. $koniec .'</td></tr>';
		}
		echo '</tbody></table>';
		echo '</div>';
	} 
	else
	{
	echo "<br><h3 style='text-align:center; color:red;'>Hráč s nickom " . $nickname . " nebol ešte vykopnutý!</h3>";
	}
	$conn->close();

} 
else {
	

echo '  
<div class="pagination">
  <a href="';
  if($strana <= 1){ echo '#'; } else { echo "?strana=".($strana - 1); } 
  echo '" style="float:left"><i class="fas fa-arrow-left"></i></a>
  <a href="';
  if($strana >= $celkovo_stranky){ echo '#'; } else { echo "?strana=".($strana + 1); }
  
  echo '"style="float:right"><i class="fas fa-arrow-right"></i></a>
</div>
';
echo '<br><br>';
echo '<div style="overflow-x:auto !IMPORTANT;">';
echo '<table class="widefat color padd"><tbody>';
echo '<tr><th width=5% style="text-align: center">Herný nick</th><th width=5% style="text-align: center">Umlčanie udelil</th><th width=40% style="text-align: center">Dôvod</th><th width=20% style="text-align: center">Dátum udelenia</th><th width=20% style="text-align: center">Dátum expirácie</th></tr>';

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		if(!empty($row['uuid'])){
			$sql2 = "SELECT * FROM litebans_history WHERE uuid=\"". $row['uuid'] . "\"";
			$result2 = $conn->query($sql2);
			if($result2->num_rows > 0){
				while($row2 = $result2->fetch_assoc()) {
					$meno2 = $row2['name'];
					$meno3 = $row2['name'];
				}
			}
			else{
				$meno2 = "Neznámy nick";
				$meno3 = "MHF_Question";
			}
		}
		else{
			$meno2 = "---";
			$meno3 = "MHF_Question";
		}
		
		if($row['until'] == -1){
			$koniec = "Trvalé umlčanie";
		}
		else{
			if($row['active'] == 1){
				$koniec = date("d.m.Y H:i",$row['until']/1000);
			}
			else{
				$koniec = date("d.m.Y H:i",$row['until']/1000) . " (vypršalo)" ;
			}
		}
		
		if($row['banned_by_name'] == "Administrator"){
			$meno4 = "Console";
		}
		else{
			$meno4 = $row['banned_by_name'];
		}
		
		echo '<tr><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno3.'" width="24px" height="24px"> <br>'.$meno2 .'</td><td align="center"><img style="margin-right: 7px; border-radius: 5px" src="https://cravatar.eu/head/'.$meno4.'" width="24px" height="24px"> <br>'. $row['banned_by_name'] .'</td><td align="center">'. $row['reason'] .'</td><td align="center">'. date("d.m.Y H:i",$row['time']/1000) .'</td><td align="center">'. $koniec .'</td></tr>';
	}
} 
else
{
echo "prazdna tabulka";
}
echo '</tbody></table>';
echo '</div>';
$conn->close();

echo '

<div class="pagination">
  <a href="';
  if($strana <= 1){ echo '#'; } else { echo "?strana=".($strana - 1); } 
  echo '" style="float:left"><i class="fas fa-arrow-left"></i></a>
  <a href="';
  if($strana >= $celkovo_stranky){ echo '#'; } else { echo "?strana=".($strana + 1); }
  
  echo '"style="float:right"><i class="fas fa-arrow-right"></i></a>
</div>
';
}
?>
