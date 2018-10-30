<?php
	require_once('../include/config.inc.php');

	if(isset($_GET['search'])) {
		$errors = array();
		$data = array();

		$search = $_GET['search'];
		$id = 0;

		if(	! empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;

			$check = false;
		} else {
			$query = $mysqli->query("SELECT id FROM user WHERE (kdnr='".$search."') OR (schulname LIKE '%".$search."%') OR (username LIKE'%".$search."%')");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						$id = $get['id'];
					}
				}

			$data['success'] = true;
			$data['message'] = 'Success!';

			$check = true;
		}

		echo json_encode($id);
	}

	if(isset($_GET['search_result'])) {
		$search = $_GET['searchvalue'];

		echo '<table class="usertable" id="user-table"';
		echo '<tr>';
		echo '<th></th>';
		echo '<th>Kundennummer</th>';
		echo '<th>Kundenname</th>';
		echo '<th>Benutzername</th>';
		echo '<th>Letztes Update</th>';
		echo '<th></th>';
		echo '</tr>';
		$query = $mysqli->query("SELECT * FROM user WHERE (kdnr='".$search."') OR (schulname LIKE '%".$search."%') OR (username LIKE'%".$search."%')");
			if($query->num_rows) {
				while($get = $query->fetch_assoc()) {
					echo '<tr>';
					if($get['isactiv'] == 1) {
						echo '<td><img src="../style/true.png" alt="active"></td>';
					}
					else {
						echo '<td><img src="../style/false.png" alt="active"></td>';
					}
					echo '<td>'.$get['kdnr'].'</td>';
					echo '<td>'.$get['schulname'].'</td>';
					echo '<td>'.$get['username'].'</td>';
					if(date('d.m.Y', strtotime($get['lastupdate'])) == '30.11.-0001') {
							echo '<td>-</td>';
					} else {
							echo '<td>'.date('d.m.Y', strtotime($get['lastupdate'])).'</td>';
					}
					echo '<td>';
					echo '<a href="index.php?site=update&id='.$get['id'].'" class="link"><img src="../style/edit.png" alt="Edit"></a>';
					echo '</td>';
					echo '</tr>';
				}
			}
		echo '</table>';
	}

	if(isset($_GET['filtern'])) {
		$errors = array();
		$data = array();

		$filter = $_GET['filter'];
		$id = 0;

		if(	! empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;

			$check = false;
		} else {
			$query = $mysqli->query("SELECT id FROM user WHERE isschool='".$filter."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						$id = $get['id'];
					}
				}

			$data['success'] = true;
			$data['message'] = 'Success!';

			$check = true;
		}

		echo json_encode($id);
	}

	if(isset($_GET['filter_result'])) {
		$filter = $_GET['filter'];

		echo '<table class="usertable" id="user-table"';
		echo '<tr>';
		echo '<th></th>';
		echo '<th>Schulnummer</th>';
		echo '<th>Schulname</th>';
		echo '<th>Benutzername</th>';
		echo '<th>Letztes Update</th>';
		echo '<th></th>';
		echo '</tr>';
		$query = $mysqli->query("SELECT * FROM user WHERE isschool='".$filter."'");
			if($query->num_rows) {
				while($get = $query->fetch_assoc()) {
					echo '<tr>';
					if($get['isactiv'] == 1) {
						echo '<td><img src="../style/true.png" alt="active"></td>';
					}
					else {
						echo '<td><img src="../style/false.png" alt="active"></td>';
					}
					echo '<td>'.$get['kdnr'].'</td>';
					echo '<td>'.$get['schulname'].'</td>';
					echo '<td>'.$get['username'].'</td>';
					if(date('d.m.Y', strtotime($get['lastupdate'])) == '30.11.-0001') {
							echo '<td>-</td>';
					} else {
							echo '<td>'.date('d.m.Y', strtotime($get['lastupdate'])).' - '.date('H:i:s', strtotime($get['lastupdate'])).'</td>';
					}
					echo '<td>';
					echo '<a href="index.php?site=update&id='.$get['id'].'" class="link"><img src="../style/edit.png" alt="Edit"></a>';
					echo '</td>';
					echo '</tr>';
				}
			}
		echo '</table>';
	}

	if(isset($_GET['add_student'])) {
		$errors = array();
		$data = array();
        
        $surname=$_GET['surname'];
        $middlename=$_GET['middlename'];
        $givenname=$_GET['givenname'];
        $moregivenname=$_GET['moregivenname'];
        $birthdate=$_GET['birthdate'];
		$birthtown = $_GET['birthtown'];
		$birthcountry = $_GET['birthcountry'];
		$nationality = $_GET['nationality'];
		$address = $_GET['address'];
		$province = $_GET['province'];
		$phone = $_GET['phone'];
		$mobilephone = $_GET['mobilephone'];
		$idgraduation = $_GET['graduation'];
		$religion = $_GET['religion'];
		$family_speech = $_GET['family_speech'];
        $token=$_GET['token'];
		if(empty($surname))
			$errors['surname'] = 'Vorname darf nicht leer sein.';
		if(empty($middlename))
			$errors['middlename'] = '2ter Vorname darf nicht leer sein.';
		if(empty($givenname))
			$errors['givenname'] = 'Nachname darf nicht leer sein.';
        if(empty($birthdate))
			$errors['birthdate'] = 'Geburtsdatum darf nicht leer sein.';
		$class_query = $mysqli->query("SELECT classcode FROM class WHERE token='".$token."'");
		if($class_query->num_rows == 1) {
			 while ( $get = $class_query->fetch_assoc() ) {
                 $classs=$get['classcode'];
             }
		}
		if(!empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;

			$check = false;
		} else {
			$createuser = "INSERT INTO students(surname,middlename,givenname,moregivenname,birthdate,birthtown,birthcountry,nationality,address,province,phone,mobilephone, idgraduation,religion,family_speech,classcode,active)VALUES('".$mysqli->real_escape_string($surname)."','".$mysqli->real_escape_string($middlename)."','".$mysqli->real_escape_string($givenname)."','".$mysqli->real_escape_string($moregivenname)."','".$mysqli->real_escape_string($birthdate)."','".$mysqli->real_escape_string($birthtown)."','".$mysqli->real_escape_string($birthcountry)."','".$mysqli->real_escape_string($nationality)."','".$mysqli->real_escape_string($address)."','".$mysqli->real_escape_string($province)."','".$mysqli->real_escape_string($phone)."','".$mysqli->real_escape_string($mobilephone)."','".$mysqli->real_escape_string($idgraduation)."','".$mysqli->real_escape_string($religion)."','".$mysqli->real_escape_string($family_speech)."','".$mysqli->real_escape_string($classs)."','1');";

			$data['success'] = true;
			$data['message'] = 'Success!';
			$check = true;
			$mysqli->query($createuser);
            echo json_encode($check);
		}
	}

	if(isset($_GET['user_update'])) {
		$errors = array();
		$data = array();

		$birthtown = $_GET['birthtown'];
		$birthcountry = $_GET['birthcountry'];
		$nationality = $_GET['nationality'];
		$address = $_GET['address'];
		$province = $_GET['province'];
		$phone = $_GET['phone'];
		$mobilephone = $_GET['mobilephone'];
		//$religion = $_GET['religion'];
		$family_speech = $_GET['family_speech'];
		$active = $_GET['active'];
		$email="";
		if(empty($birthtown))
			$errors['birthtown'] = 'Geburtsort darf nicht leer sein.';
		if(empty($birthcountry))
			$errors['birthcountry'] = 'Geburtsland darf nicht leer sein.';
        if(empty($nationality))
			$errors['nationality'] = 'Nationalität darf nicht leer sein.';
		if(empty($address))
			$errors['address'] = 'Addresse darf nicht leer sein.';
		if(empty($province))
			$errors['province'] = 'Bundesland darf nicht leer sein.';
		if(empty($phone))
			$errors['phone'] = 'Telefon darf nicht leer sein.';
		if(empty($mobilephone))
			$errors['mobilephone'] = 'Mobiltelefon darf nicht leer sein.';
		/*if(empty($religion))
			$errors['religion'] = 'Religion darf nicht leer sein.';*/
		if(empty($family_speech))
			$errors['family_speech'] = 'Muttersprache darf nicht leer sein.';
		if(!empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;

			$check = false;
		} else {
			$updateuser = "update students set birthtown = '".$birthtown."', birthcountry = '".$birthcountry."', nationality = '".$nationality."', address = '".$address."', province = '".$province."', phone = '".$phone."', mobilephone = '".$mobilephone."', family_speech = '".$family_speech."', email = '".$email."', active='".$active."' where idstudents='".$_GET['idstudents']."';";
			//$updateuser = "UPDATE user SET schulname='".$schoolname."', user='".$username."', isactiv='".$active."' WHERE kdnr='".$schoolnumber."'";
			$mysqli->query($updateuser);

			$data['success'] = true;
			$data['message'] = 'Success!';
			$data['query']= $updateuser;
			$check = true;
		}

		echo json_encode($data);
	}
?>