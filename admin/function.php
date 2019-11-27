<?php
	require_once('../include/config.inc.php');

	//wenn nix geht das löschen bis zum nächsten kommentar
	/*if ( isset( $_SESSION[ 'loggedin' ] ) ) {
		$loggedin = $_SESSION[ 'loggedin' ];
	}else
		$loggedin = false;
	if ( $loggedin == true ) {
		if ( $_SESSION[ 'userrole' ] == 1 ) {
			
		}
	} else {
		header( 'location: ../index.php' );
	}*/
	//bis hier hin
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

	if(isset($_POST["Importlanis"])){
		set_time_limit(600);
		$filename=$_FILES["file"]["tmp_name"];    
		 if($_FILES["file"]["size"] > 0)
		 {
			$errors[]=array();
			$file = fopen($filename, "r");
			$i=0;
			$result="";
			//$studentstmt =$mysqli->prepare("update students set lanisid=? where birthdate=? givenname=? and surname=?;");
			while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				if($i>0){
					
					$year=substr($getData[4],-4);
					$month=substr($getData[4],-7,2);
					$day=substr($getData[4],-10,2);
					//$date = $getData[6];
					$newDate=$year."-".$month."-".$day;
					//$studentstmt->bind_param('isss',
					//$getData[10],
					//$newDate,
					//$getData[0],
					//$getData[2]);
					//$studentstmt->execute();
					$gname=preg_replace('/(\d+)/','',$getData[0]);
					$sname=preg_replace('/(\d+)/','',$getData[2]);
					$sql = "update students set lanisid=".$getData[10]." where birthdate='".$newDate."' and givenname='".$gname."' and surname='".$sname."';";
					$error=$mysqli->query($sql);
					//echo $studentstmt->error;
					//$result = $studentstmt->error;
					//$error=$mysqli->error();
					//echo $sql;
				}
				$i++;
				
			}
			//$studentstmt->close();
			if(!isset($result))
			{
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";    
			}
			else {
				echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				window.location = \"index.php?site=lanisimport\"
			</script>";
			}
			fclose($file);  
		 }
	  }
	  
	  if(isset($_POST["importsus"])){
		set_time_limit(600);
		$filename=$_FILES["file"]["tmp_name"];    
		 if($_FILES["file"]["size"] > 0)
		 {
			$errors[]=array();
			$file = fopen($filename, "r");
			$i=0;
			$result="";
			$entrydate=date('Y-m-d');
			$plz='00000';
			$studentstmt =$mysqli->prepare( "insert into students (surname,givenname,birthdate,entryDate,classcode,plz,idberuf) values(?,?,?,?,?,?,?)");
			while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				if($i>0){
					switch($getData[4]){
						case "FISI":
							$beruf=21;
						case "FIAE":
							$beruf=18;
						case "ITSE":
							$beruf=23;
						case "ITSK":
							$beruf=24;
						case "IK":
							$beruf=22;
						case "BFS":
							$beruf=11;
						case "EV":
							$beruf=2;
						case "EH":
							$beruf=1;
						case "FL":
							$beruf=15;	
					} 
					$year=substr($getData[2],-4);
					$month=substr($getData[2],-7,2);
					$day=substr($getData[2],-10,2);
					//$date = $getData[6];
					$birthdate=$year."-".$month."-".$day;
					$year=substr($getData[5],-4);
					$month=substr($getData[5],-7,2);
					$day=substr($getData[5],-10,2);
					//$date = $getData[6];
					$entrydate=$year."-".$month."-".$day;
					$studentstmt->bind_param('ssssssi',
					$getData[0],
					$getData[1],
					$birthdate,
					$entrydate,
					$getData[3],
					$plz,
					$beruf);
					$studentstmt->execute();
					echo $studentstmt->error;
				}
				$i++;
				
			}
			$studentstmt->close();
			if(!isset($result))
			{
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";    
			}
			else {
				echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				//window.location = \"index.php?site=lanisimport\"
			</script>";
			}
			fclose($file);  
		 }
	  }
	  if(isset($_POST["susbetrieb"])){
		set_time_limit(600);
		$filename=$_FILES["file"]["tmp_name"];    
		 if($_FILES["file"]["size"] > 0)
		 {
			$errors[]=array();
			$file = fopen($filename, "r");
			$i=0;
			$result="";
			$entrydate=date('Y-m-d');
			$plz='00000';
			$ausbildungsbeginn="2019-08-01";
			//Import SuS Nachname,Vorname,Geburtsdatum,Klasse,Beruf,entryDate,Betrieb,Betrieb_PLZ,Betrieb_Email
			$studentstmt =$mysqli->prepare( "insert into students (givenname,surname,birthdate,classcode,idberuf,entryDate,Ausbildungsbetrieb,Ausbildungsbetrieb_strasse,Ausbildungsbetrieb_PLZ,Ausbildungsbetrieb_Email,Ausbildungsbeginn) values(?,?,?,?,?,?,?,?,?,?,?)");
			while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				if($i>0){
					switch($getData[4]){
						case "FISI":
							$beruf=21;
						case "FIAE":
							$beruf=18;
						case "ITSE":
							$beruf=23;
						case "ITSK":
							$beruf=24;
						case "IK":
							$beruf=22;
						case "BFS":
							$beruf=11;
						case "EV":
							$beruf=2;
						case "EH":
							$beruf=1;
						case "FL":
							$beruf=15;		
					} 
					$year=substr($getData[2],-4);
					$month=substr($getData[2],-7,2);
					$day=substr($getData[2],-10,2);
					//$date = $getData[6];
					$birthdate=$year."-".$month."-".$day;
					$studentstmt->bind_param('ssssissssss',
					$getData[0],
					$getData[1],
					$birthdate,
					$getData[3],
					$beruf,
					$getData[5],
					$getData[6],
					$getData[7],
					$getData[8],
					$getData[9],
					$ausbildungsbeginn);
					$studentstmt->execute();
					echo $studentstmt->error;
				}
				$i++;
				
			}
			$studentstmt->close();
			if(!isset($result))
			{
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				//window.location = \index.php?site=lanisimport\"
				</script>";    
			}
			else {
				echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				//window.location = \"index.php?site=lanisimport\"
			</script>";
			}
			fclose($file);  
		 }
	  }




	  if(isset($_POST["Importlusd"])){
		set_time_limit(600);
		$filename=$_FILES["file"]["tmp_name"];    
		 if($_FILES["file"]["size"] > 0)
		 {
			$errors[]=array();
			$file = fopen($filename, "r");
			$i=0;
			$result="";
			//$updatestmt=$mysqli->prepare("update students set lanisid=? where lastname=? and surname=?;");
			while (($getData = fgetcsv($file, 10000, ";")) !== FALSE)
			{
				if($i>0){
					//$updatestmt->bind_param('iss',$getData[7],$getData[0],$getData[1]);
					//$updatestmt->execute();
					$year=substr($getData[6],-4);
					$month=substr($getData[6],-6,2);
					$day=substr($getData[6],-8,2);
					//$date = $getData[6];
					$newDate=$year."-".$month."-".$day;
					$sql = "update students set lanisid=".$getData[7]." where birthdate='".$newDate."' and givenname='".$getData[0]."' and surname='".$getData[1]."';";

					$result = $mysqli->query($sql);
					//$error $mysqli->error();
					//echo $sql;
				}
				$i++;
				
			}
			//$errors["updatestmt"]=$updatestmt->error;
			//$updatestmt->close();
			if(!isset($result))
			{
			//}
			//echo $errors;
			echo "<script type=\"text/javascript\">
				alert(\"Invalid File:Please Upload CSV File.\");
				window.location = \index.php?site=lanisimport\"
				</script>";    
			}
			else {
				//echo $result;
				echo "<script type=\"text/javascript\">
				alert(\"CSV File has been successfully Imported.\");
				window.location = \"index.php?site=lanisimport\"
			</script>";
			}
			fclose($file);  
		 }
	  }   


	if(isset($_GET['search_result'])) {
		$search = $_GET['searchvalue'];
?>
		<table class="table" id="user-table">
		<tr>
            <th>Status</th>
            <th>Vorname</th>
            <th>Vorname 2</th>
            <th>Nachname</th>
            <th>Nachname weitere</th>
            <th>Klasse</th>
            <th>bearbeiten</th>
			<th>löschen</th>
        </tr>
<?php
		$query = $mysqli->query("SELECT surname,middlename,givenname,moregivenname,classcode,idstudents,active FROM all_students WHERE (surname='".$search."') OR (middlename LIKE '%".$search."%') OR (classcode LIKE '%".$search."%') OR (givenname LIKE '%".$search."%') OR (moregivenname LIKE'%".$search."%')");
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
                echo '<td>' . $get[ 'middlename' ] . '</td>';
                echo '<td>' . $get[ 'givenname' ] . '</td>';
				echo '<td>' . $get[ 'moregivenname' ] . '</td>';
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>';
				echo '<a href="index.php?site=update&idteacher=' . $classteacher[0]. '&id=' . $get[ 'idstudents' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				echo '</td>';
				echo '<td>';
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
	if(isset($_GET['class'])) {
		$errors = array();
		$data = array();

		$filter = $_GET['class'];
		if(	! empty($errors)) {
			$data['success'] = false;
			//$data['errors'] = $errors;
			$check = false;
		} else {
			$query = $mysqli->query("SELECT count(classcode) as count FROM class WHERE classcode='".$filter."'");
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
            <th>Status</th>
            <th>Vorname</th>
            <th>Vorname 2</th>
            <th>Nachname</th>
            <th>Nachname weitere</th>
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
			$errors['birthtown'] = 'Geburtsort darf nicht leer sein.';
		if(empty($birthcountry))
			$errors['birthcountry'] = 'Geburtsland darf nicht leer sein.';
        if(empty($nationality))
			$errors['nationality'] = 'Nationalität darf nicht leer sein.';
		if(empty($address))
			$errors['address'] = 'Addresse darf nicht leer sein.';
		if(empty($province))
			$errors['province'] = 'Bundesland darf nicht leer sein.';
		/*if(empty($phone))
			$errors['phone'] = 'Telefon darf nicht leer sein.';*/
		/*if(empty($mobilephone))
			$errors['mobilephone'] = 'Mobiltelefon darf nicht leer sein.';
		if(empty($religion))
			$errors['religion'] = 'Religion darf nicht leer sein.';*/
		if(empty($family_speech))
			$errors['family_speech'] = 'Muttersprache darf nicht leer sein.';
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

	if(isset($_GET['filter_class_result1'])) {
		$filter = $_GET['filter'];

		?>
		<table class="table" id="user-table">
		<tr>
            <th>Klasse</th>
            <th>Langname</th>
            <th></th>
        </tr>
<?php
		$query = $mysqli->query( "SELECT * FROM class where classcode='".$filter."';" );
		if ( $query->num_rows ) {
			while ( $get = $query->fetch_assoc() ) {
				echo '<tr>';
				/*if ( $get[ 'active' ] == 1 ) {
					echo '<td><img src="../style/true.png" alt="active" id="aktiv"></td>';
				} else {
					echo '<td><img src="../style/false.png" alt="active"></td>';
				}*/
				echo '<td>' . $get[ 'classcode' ] . '</td>';
				echo '<td>' . $get[ 'longname' ] . '</td>';				
				echo '<td>';
				echo '<a href="index.php?site=update&class=1&id=' . $get[ 'classcode' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
				//echo '</td>';
				//echo '<td>';
				echo '<a href="javascript:deleteuser(' . $get[ 'classcode' ] . ')" class="link"><img src="../style/false.png" alt="delete"></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	}
	
?>
<script>

function deleteuser( idstudents ) {
if ( confirm( "Möchten Sie wirklich löschen" ) )
	if ( idstudents == "" ) {
		$( "#deleteuser" ).show();
		$( "#searcherror" ).hide();
	} else {
		$.get( 'function.php?delete&idstudents=' + idstudents, function ( data ) {
			var jsonobj = JSON.parse( data );
			if ( !jsonobj.success ) {
				$( "#deleteuser" ).show();
				$( "#success" ).hide();
			} else {
				$( "#success" ).show();
				$( "#deleteuser" ).hide();
				$.get( 'loadusertable.php', function ( data ) {
					$( '#user-table' ).html( data );
				} );
			}
		} );
	}
}
</script>