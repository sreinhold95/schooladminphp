<?php
	require_once('../include/config.inc.php');


	if(isset($_GET['token'])){
		$errors = array();
		$data = array();
		if (isset($_GET['activetoken'])){
			if(isset($_GET['classcode'])){
				$activatedate=date('Y-m-d H:i:s');
				if($_GET['activetoken']=="1"){
					$classtokenstmt=$mysqli->prepare("update class set activetoken=?,tokenactivateat=? where classcode=?");
					$classtokenstmt->bind_param("iss",$_GET['activetoken'],$activatedate,$_GET['classcode']);
				}else{
					$classtokenstmt=$mysqli->prepare("update class set activetoken=? where classcode=?");
					$classtokenstmt->bind_param("is",$_GET['activetoken'],$_GET['classcode']);
				}
				$classtokenstmt->execute();
				$errors["classtokenstmt"]=$classtokenstmt->error;
				$classtokenstmt->close();
				if($errors["classtokenstmt"]=="")
					$data['success'] = true;
				else
					$data['success'] = false;
				$data['errors'] = $errors;
				echo json_encode($data);
			}
		}
	}
	else if(isset($_GET['search'])) {
		$errors = array();
		$data = array();

		$search = $_GET['search'];
		$id = 0;

		if(	! empty($errors)) {
			$data['success'] = false;
			$data['errors'] = $errors;

			$check = false;
		} else {
			$query = $mysqli->query("SELECT idstudents FROM students WHERE (surname='".$search."') OR (middlename LIKE '%".$search."%') OR (classcode LIKE '%".$search."%') OR (givenname LIKE '%".$search."%') OR (moregivenname LIKE'%".$search."%')");
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

	else if(isset($_GET['search_result'])) {
		$search = $_GET['searchvalue'];
?>
		<table class="table" id="user-table">
		<tr>
            <th>Status</th>
            <th>Vorname</th>
			<th>Nachname</th>
			<th>Beruf </th>
            <th>Klasse</th>
            <th></th>
        </tr>
<?php
		$query = $mysqli->query("SELECT * from all_students inner join teacher_class on all_students.classcode=teacher_class.classcode where active=1 and (surname LIKE '%".$search."%') OR (middlename LIKE '%".$search."%') OR (all_students.classcode LIKE '%".$search."%') OR (givenname LIKE '%".$search."%') OR (moregivenname LIKE'%".$search."%')");
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
				echo '<td>' . $get[ 'Beruf' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				if($get[ 'classteacher' ]=="1")
					echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
            }
        }?>
		</table>
<?php
	}

	else if(isset($_GET['filtern'])) {
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
	else if(isset($_GET['filter_class'])) {
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

	else if(isset($_GET['filter_class_result'])) {
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

	else if(isset($_GET['filter_result'])) {
		$filter = $_GET['filter'];
		$idteacher = $_GET['idteacher'];
		?>
		<table class="table" id="user-table">
		<tr>
			<th></th>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>Beruf</th>
			<th>Klasse</th>
			<th></th>
		</tr>
		<?php
		$sql="SELECT * from all_students inner join teacher_class on all_students.classcode=teacher_class.classcode where active=1 and teacher_class.idteacher='".$idteacher."'and all_students.classcode='".$filter."' ;";
		$query = $mysqli->query($sql);
		//$query = $mysqli->query( "SELECT * from all_students_from_department where classcode='".$filter."'");
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
				echo '<td>' . $get[ 'Beruf' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				if($get[ 'classteacher' ]=="1")
					echo '<a href="javascript:deleteuser(' . $get[ 'idstudents' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	}
	else if(isset($_GET['delete']))
	{
		$errors = array();
		$data = array();
		$idstudents=$_GET['idstudents'];
		$deleteuser="update students set active=0 where idstudents='".$idstudents."';";
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
	else if(isset($_GET['user_update'])) {
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