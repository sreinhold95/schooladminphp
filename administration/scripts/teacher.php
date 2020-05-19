<script type="text/javascript">
	function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	$(document).ready(function() {
		$.ajax({
			url:'../api/v2/teacher.php',
			type:'GET',
			headers:{"uuid": getCookie("uuid")},
			success:function(data){
				var i=1
				$.each(data, function(key, value){
					$("#teacher >tbody:last").append(
							$('<tr>').append(
								$('<th scope="row">').append(i)
								.append(
									$('</th>').append(
										$('</tr>')
										)
									)
								)
							);
						i++
					$.each(value, function(k, v){

						if(k === "active"){
							if(v=1){
								$("#teacher >tbody >tr:last").append(
										$('<td>').append('<img src="../style/true.png" alt="active" id="active">')
											.append(
											$('</td>')
										)
								);
							}else{
								$("#teacher >tbody >tr:last").append(
									$('<td>').append('<img src="../style/false.png" alt="active" id="inactive">')
										.append(
										$('</td>')
									)
							);
						}
						}if(k === "surname"){
							$("#teacher >tbody >tr:last").append(

								$('<td>').append(v)
								.append(
									$('</td>')

									)

								);
						}
						if(k === "lastname"){
							$("#teacher >tbody >tr:last").append(

								$('<td>').append(v)
								.append(
									$('</td>')
									)
							);
						}
						if(k === "initials"){
							$("#teacher >tbody >tr:last").append(

								$('<td>').append(v)
								.append(
									$('</td>')

									)

								);
						}
						
						if(k === "name"){
							$("#teacher >tbody >tr:last").append(

								$('<td>').append(v)
								.append(
									$('</td>')

									)

							);
						}
						if(k === "idteacher"){
							$("#teacher >tbody >tr:last").append(
								$('<td>').append('<a href="index.php?site=teacherupdate&idteacher='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
								.append(
									$('</td>')

									)

								);
						}
					});
					
				});
			}
		})
	});
	$( "#search" ).submit( function ( event ) {
		var search = $( 'input#search' ).val();
		event.preventDefault();
		$( ".error_wrap" ).show();
		if ( search == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();
			$( "deleteuser" ).hide();

		} else {
			$.get( 'function.php?teachersearch&search=' + search, function ( data ) {
				if ( data != 0 ) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'function.php?teachersearch_result&searchvalue=' + search, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					$("deleteuser").hide();
					$.get( 'teacherloadusertable.php', function ( data ) {
						$( '#user-table' ).html( data );
					} );
				}
			} );
		}
	} );
</script>