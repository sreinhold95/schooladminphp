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
			$query = $mysqli->query("SELECT idstudents FROM all_students_from_department WHERE headofdepartment='".$_SESSION["idteacher"]."' and (surname='".$search."') OR (middlename LIKE '%".$search."%') OR (classcode LIKE '%".$search."%') OR (givenname LIKE '%".$search."%') OR (moregivenname LIKE'%".$search."%')");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						$id = $get['idstudents'];
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
?>
		<table class="table" id="user-table">
		<tr>
            <th>Status</th>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Klasse</th>
            <th></th>
        </tr>
<?php
		$query = $mysqli->query("SELECT * FROM all_students_from_department WHERE headofdepartment='".$_SESSION["idteacher"]."' and (surname='".$search."') OR (middlename LIKE '%".$search."%') OR (classcode LIKE '%".$search."%') OR (givenname LIKE '%".$search."%') OR (moregivenname LIKE'%".$search."%')");
        if ( $query->num_rows ) {
            while ( $get = $query->fetch_assoc() ) {
				$classteacher=[];
				$query1 = $mysqli->query("SELECT idteacher from classteacher where classcode='". $get[ "classcode" ]."';");
				if ( $query1->num_rows ) {
					$i=0;
					while ( $get1 = $query1->fetch_assoc() ) {
						//if($i==1){
							$classteacher[$i]=$get1[ 'idteacher' ];
						//}
						$i++;
					}
				}
                echo '<tr>';
                if ( $get[ 'active' ] == 1 ) {
                    echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
                } else {
                    echo '<td><img src="../style/false.png" alt="active"></td>';
                }
                echo '<td>' . $get[ 'surname' ] . '</td>';
				echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&idteacher=' . $classteacher[0]. '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
            }
        }?>
		</table>
<?php
	}

	if(isset($_GET['filtern'])) {
		$errors = array();
		$data = array();

		$filter = $_GET['filter'];
		if(	! empty($errors)) {
			$data['success'] = false;
			//$data['errors'] = $errors;
			$check = false;
		} else {
			$query = $mysqli->query("SELECT count(idstudents) as count FROM students WHERE classcode='".$filter."'");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						$data['counter']= $get['count'];
						
					}
					$data['success'] = true;
					$data['message'] = 'Success!';
				}
			$check = true;
		}
		echo json_encode($data);
	}
	if(isset($_GET['filter_class'])) {
		$errors = array();
		$data = array();

		$filter = $_GET['filter'];
		$data['classcode']=$_GET['filter'];
		if(	! empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;
			$check = false;
		} else {
			$query = $mysqli->query("SELECT idstudents FROM students WHERE classcode='".$filter."';");
				if($query->num_rows) {
					while($get = $query->fetch_assoc()) {
						$data['id']=$get['idstudents'];
					}
				}

			$data['success'] = true;
			$data['message'] = 'Success!';

			$check = true;
		}

		echo json_encode($data);
	}

	if(isset($_GET['filter_class_result'])) {
		$filter = $_GET['filter'];

		?>
		<table class="table" id="user-table">
		<tr>
            <th>Status</th>
            <th>Vorname</th>
            <th>Vorname 2</th>
            <th>Nachname</th>
            <th>Nachname weitere</th>
            <th>Klasse</th>
            <th></th>
        </tr>
<?php
		$query = $mysqli->query("SELECT * FROM students WHERE classcode='".$filter."';");
        if ( $query->num_rows ) {
            while ( $get = $query->fetch_assoc() ) {
                echo '<tr>';
                if ( $get[ 'active' ] == 1 ) {
                    echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
                } else {
                    echo '<td><img src="../style/false.png" alt="active"></td>';
                }
                echo '<td>' . $get[ 'surname' ] . '</td>';
                echo '<td>' . $get[ 'middlename' ] . '</td>';
                echo '<td>' . $get[ 'givenname' ] . '</td>';
                echo '<td>' . $get[ 'moregivenname' ] . '</td>';
                echo '<td>';
                echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
                echo '</td>';
                echo '</tr>';
            }
        }
		echo '</table>';
	}

	if(isset($_GET['filter_result'])) {
		$filter = $_GET['filter'];

		?>
		<table class="table" id="user-table">
		<tr>
			<th></th>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>Klasse</th>
			<th></th>
		</tr>
		<?php
		$query = $mysqli->query( "SELECT * from all_students_from_department where classcode='".$filter."'");
		if ( $query->num_rows ) {
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}
				echo '<td>' . $get[ 'surname' ] . '</td>';
				echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '</td>';
				//echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
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
		if(empty($givenname))
			$errors['givenname'] = 'Nachname darf nicht leer sein.';
        if(empty($birthdate))
			$errors['birthdate'] = 'Geburtsdatum darf nicht leer sein.';
		
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
            echo json_encode($data);
		}
	}

	if(isset($_GET['delete']))
	{
		$errors = array();
		$data = array();
		$idstudents=$_GET['idstudents'];
		$deleteuser="delete from students where idstudents='".$idstudents."';";
		$sql=$mysqli->query($deleteuser);
		if($sql){
			$data['success']=true;
			$data['message']='Success!';
		}else{
			$data['success']=false;
			$errors['sqlerror']=$mysqli->error;
		}
		echo json_encode($data);
	}
	if(isset($_GET['user_update'])) {
		$errors = array();
		$data = array();
		
		$surname= $_GET['surname'];
		$middlename = $_GET['middlename'];
		$givenname = $_GET['givenname'];
		$moregivenname = $_GET['moregivenname'];
		$birthdate = $_GET['birthdate'];
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
		$classs = $_GET['class'];
		$email="";
		if(empty($birthtown))
			$errors['Geburtsort'] = 'Geburtsort darf nicht leer sein.';
		if(empty($birthcountry))
			$errors['Geburtsland'] = 'Geburtsland darf nicht leer sein.';
        if(empty($nationality))
			$errors['Nationalität'] = 'Nationalität darf nicht leer sein.';
		if(empty($address))
			$errors['Adresse'] = 'Addresse darf nicht leer sein.';
		if(empty($province))
			$errors['Bundesland'] = 'Bundesland darf nicht leer sein.';
		if(empty($phone))
			$errors['Telefon'] = 'Telefon darf nicht leer sein.';
		/*if(empty($mobilephone))
			$errors['mobilephone'] = 'Mobiltelefon darf nicht leer sein.';
		if(empty($religion))
			$errors['religion'] = 'Religion darf nicht leer sein.';*/
		if(empty($family_speech))
			$errors['Muttersprache'] = 'Muttersprache darf nicht leer sein.';
		if(!empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;
			$data['emptyfield']=true;
		} else {
			$updateuser = "update students set surname = '".$surname."', middlename = '".$middlename."', givenname = '".$givenname."', moregivenname = '".$moregivenname."', birthtown = '".$birthtown."', birthcountry = '".$birthcountry."', nationality = '".$nationality."', address = '".$address."', province = '".$province."', phone = '".$phone."', mobilephone = '".$mobilephone."', family_speech = '".$family_speech."', email = '".$email."', active='".$active."', classcode='".$classs."' , birthdate='".$birthdate."' where idstudents='".$_GET['idstudents']."';";
			$mysqli->query($updateuser);

			$data['success'] = true;
			$data['message'] = 'Success!';
			$data['query']= $updateuser;
		}

		echo json_encode($data);
	}
?>