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
	if(isset($_GET['filter_class_result'])) {
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
				echo '<a href="index.php?site=update&id=' . $get[ 'classcode' ] . '" class="link"><img src="../style/edit.png" alt="Edit"></a>';
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