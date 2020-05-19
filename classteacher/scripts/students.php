<script type="text/javascript">
//Neue Funktionen
function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
let infoIcon = function(value, data, cell, row, options){ 
		//return '<a href="index.php?site=update&id='+v+'" class="link"><img class="infoImage" src="../style/edit.png" alt="Edit"></a>';
        return "<img class='infoImage' src='../style/edit.png'>";
};
let printIcon = function(cell, formatterParams){ //plain text value
    return "<i class='fa fa-print'></i>";
};
let Config = {
    method:"get", //set request type to Position
    headers: {
		"Content-type": 'application/json; charset=utf-8',//set specific content type
		"uuid": getCookie("uuid"),
		"tab": "yes"
    }
};
let table = new Tabulator("#students", {
    ajaxURL:"../api/v2/class.php?classcode=all", //ajax URL
    //ajaxProgressiveLoad:"scroll", //enable progressive loading
	//ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
	ajaxConfig:Config,
	index:"classcode",
	height:"700px",
	responsiveLayout:true,
	layout:"fitColumns",
	columns:[
        {title:"#",formatter:"rownum", align:"left", width:20, headerSort:false, download:false},
        {title:"Aktiv", field:"active",formatter:"tickCross", headerSort:false},
        {title:"Vorname", field:"surname", headerFilter:"input", headerFilterPlaceholder:"VN"},
		{title:"Nachname", field:"givenname", headerFilter:"input", headerFilterPlaceholder:"NN"},
		{title:"Klasse", field:"classcode"},
		{title:"bearbeiten",formatter:"link", headerSort:false,formatterParams:
			{
				label:"öffnen",
				url:function(cell){return "index.php?site=update&id=" + cell.getData().idstudents}
			}, 
			download:false
		},
		{title:"drucken",formatter:"link", headerSort:false,formatterParams:
			{
				label:"drucken",
				target:"_blank",
				url:function(cell){return "../api/v2/stammblattsus.php?student="+ cell.getData().idstudents+"&uuid="+getCookie("uuid") }
			},
			download:false
		}
    ]
});
$("#download-xlsx").click(function(){
    table.download("xlsx", "alle_meine_Schueler.xlsx", {sheetName:"meine Schüler"});
});

function print(studentid){
	$.ajax({
        url: '../api/v2/stammblattsus.php??student='+studentid,
		method: 'GET',
		headers:{"uuid": getCookie("uuid")},
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            const a = document.createElement('a');
            let url = window.URL.createObjectURL(data);
            a.href = url;
            a.download = 'myfile.pdf';
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        }
    });
}
	// $(document).ready(function() {
	// 	$.ajax({
	// 		url:'../api/v2/class.php?username='+getCookie("username")+'&classcode=all',
	// 		type:'GET',
	// 		headers:{"uuid": getCookie("uuid")},
	// 		success:function(data){
	// 			var i=1
	// 			$.each(data, function(key, value){
	// 				$("#students >tbody:last").append(
	// 						$('<tr>').append(
	// 							$('<th scope="row">').append(i)
	// 							.append(
	// 								$('</th>').append(
	// 									$('</tr>')
	// 								)
	// 							)
	// 						)
	// 					);
	// 				i++
	// 				$.each(value, function(k, v){

	// 					if(k === "active"){
	// 						if(v=1){
	// 							$("#students >tbody >tr:last").append(
	// 									$('<td>').append('<img src="../style/true.png" alt="active" id="active">')
	// 										.append(
	// 										$('</td>')
	// 									)
	// 							);
	// 						}else{
	// 							$("#students >tbody >tr:last").append(
	// 								$('<td>').append('<img src="../style/false.png" alt="active" id="inactive">')
	// 									.append(
	// 									$('</td>')
	// 								)
	// 							);
	// 						}
	// 					}
	// 					if(k === "surname"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
						
	// 					if(k === "givenname"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')
	// 								)
	// 						);
	// 					}
						
	// 					if(k === "classcode"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
	// 					if(k === "idstudents"){
	// 						$("#students >tbody >tr:last").append(
	// 							$('<td>').append('<a href="index.php?site=update&id='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 						$("#students >tbody >tr:last").append(
	// 							$('<td>').append('<a href="javascript:deleteuser('+v+')" class="link"><img src="../style/false.png" alt="delete"></a>')
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
	// 				});
					
	// 			});
	// 		}
	// 	})
	// });

	$( "#classs" ).change( function ( event ) {
		var classcode = $( "#classs option:selected" ).val();
		event.preventDefault();
		if ( classcode == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();
			$("deleteuser").hide();

		} else {
			if(classcode=="alle")
				table.clearFilter();
			else
				table.setFilter("classcode","like",classcode);
			// $("#students tbody tr").remove(); 
			// $.ajax({
			// url:'../api/v2/class.php?username='+getCookie("username")+'&classcode='+classcode,
			// type:'GET',
			// headers:{"uuid": getCookie("uuid")},
			// success:function(data){
			// 	var i=1
			// 	$.each(data, function(key, value){
			// 		$("#students >tbody:last").append(
			// 				$('<tr>').append(
			// 					$('<th scope="row">').append(i)
			// 					.append(
			// 						$('</th>').append(
			// 							$('</tr>')
			// 						)
			// 					)
			// 				)
			// 			);
			// 		i++
			// 		$.each(value, function(k, v){

			// 			if(k === "active"){
			// 				if(v=1){
			// 					$("#students >tbody >tr:last").append(
			// 							$('<td>').append('<img src="../style/true.png" alt="active" id="active">')
			// 								.append(
			// 								$('</td>')
			// 							)
			// 					);
			// 				}else{
			// 					$("#students >tbody >tr:last").append(
			// 						$('<td>').append('<img src="../style/false.png" alt="active" id="inactive">')
			// 							.append(
			// 							$('</td>')
			// 						)
			// 					);
			// 				}
			// 			}
			// 			if(k === "surname"){
			// 				$("#students >tbody >tr:last").append(

			// 					$('<td>').append(v)
			// 					.append(
			// 						$('</td>')

			// 						)

			// 					);
			// 			}
						
			// 			if(k === "givenname"){
			// 				$("#students >tbody >tr:last").append(

			// 					$('<td>').append(v)
			// 					.append(
			// 						$('</td>')
			// 						)
			// 				);
			// 			}
						
			// 			if(k === "classcode"){
			// 				$("#students >tbody >tr:last").append(

			// 					$('<td>').append(v)
			// 					.append(
			// 						$('</td>')

			// 						)

			// 					);
			// 			}
			// 			if(k === "idstudents"){
			// 				$("#students >tbody >tr:last").append(
			// 					$('<td>').append('<a href="index.php?site=update&id='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
			// 					.append(
			// 						$('</td>')

			// 						)

			// 					);
			// 				$("#students >tbody >tr:last").append(
			// 					$('<td>').append('<a href="javascript:deleteuser('+v+')" class="link"><img src="../style/false.png" alt="delete"></a>')
			// 					.append(
			// 						$('</td>')

			// 						)

			// 					);
			// 			}
			// 		});
					
			// 	});
			// }
		// }) 
		}
	});

	// $( "#search_students" ).submit( function ( event ) {
	// 	var search = $( 'input#search' ).val();
	// 	event.preventDefault();
	// 	$( ".error_wrap" ).show();
	// 	if ( search == '' ) {
	// 		$( "#searchempty" ).show();
	// 		$( "#searcherror" ).hide();
	// 		$( "deleteuser" ).hide();

	// 	} else {
	// 		$("#students tbody tr").remove(); 
	// 		$.ajax({
	// 		url:'../api/v2/students.php?search='+search,
	// 		type:'GET',
	// 		headers:{"uuid": getCookie("uuid")},
	// 		success:function(data){
	// 			var i=1
	// 			$.each(data, function(key, value){
	// 				$("#students >tbody:last").append(
	// 						$('<tr>').append(
	// 							$('<th scope="row">').append(i)
	// 							.append(
	// 								$('</th>').append(
	// 									$('</tr>')
	// 								)
	// 							)
	// 						)
	// 					);
	// 				i++
	// 				$.each(value, function(k, v){
	// 					if(k === "active"){
	// 						if(v=1){
	// 							$("#students >tbody >tr:last").append(
	// 									$('<td>').append('<img src="../style/true.png" alt="active" id="active">')
	// 										.append(
	// 										$('</td>')
	// 									)
	// 							);
	// 						}else{
	// 							$("#students >tbody >tr:last").append(
	// 								$('<td>').append('<img src="../style/false.png" alt="active" id="inactive">')
	// 									.append(
	// 									$('</td>')
	// 								)
	// 							);
	// 						}
	// 					}
	// 					if(k === "surname"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
						
	// 					if(k === "givenname"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')
	// 								)
	// 						);
	// 					}
						
	// 					if(k === "classcode"){
	// 						$("#students >tbody >tr:last").append(

	// 							$('<td>').append(v)
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
	// 					if(k === "idstudents"){
	// 						$("#students >tbody >tr:last").append(
	// 							$('<td>').append('<a href="index.php?site=update&id='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 						$("#students >tbody >tr:last").append(
	// 							$('<td>').append('<a href="javascript:deleteuser('+v+')" class="link"><img src="../style/false.png" alt="delete"></a>')
	// 							.append(
	// 								$('</td>')

	// 								)

	// 							);
	// 					}
	// 				});
					
	// 			});
	// 		}
	// 	}) 
	// 	}
	// } );
// Alte Funktionen
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

	// $( "#classs" ).change( function ( event ) {
	// 	var classcode = $( "#classs option:selected" ).val();
	// 	event.preventDefault();
	// 	if ( classcode == '' ) {
	// 		$( "#searchempty" ).show();
	// 		$( "#searcherror" ).hide();
	// 		$("deleteuser").hide();

	// 	} else {
	// 		$.get( 'function.php?filtern&filter=' + classcode, function ( data ) {
	// 			console.log(data);
	// 			var jsondata = JSON.parse( data );
	// 			if ( jsondata.success ) {
	// 				$( "#searcherror" ).hide();
	// 				$( "#searchempty" ).hide();
	// 				$("deleteuser").hide();
	// 				$.get( 'function.php?filter_result&filter=' + classcode, function ( data ) {
	// 					$( '#user-table' ).html( data );
	// 				} );
	// 			} else {
	// 				$( "#searcherror" ).show();
	// 				$( "#searchempty" ).hide();
	// 				$("deleteuser").hide();
	// 				$.get( 'loadusertable.php', function ( data ) {
	// 					$( '#user-table' ).html( data );
	// 				} );
	// 			}
	// 		} );
	// 	}
	// } );
	$( "#filter" ).submit( function ( event ) {
		var classcode = $( "#classs option:selected" ).val();
		console.log(classcode);
		event.preventDefault();
		if ( classcode == '' ) {
			$( "#searchempty" ).show();
			$( "#searcherror" ).hide();

		} else {
			$.get( 'function.php?filtern&filter=' + classcode, function ( data ) {
				var jsondata = JSON.parse(data);
				if ( jsondata.success) {
					$( "#searcherror" ).hide();
					$( "#searchempty" ).hide();
					$.get( 'function.php?filter_result&filter=' + classcode, function ( data ) {
						$( '#user-table' ).html( data );
					} );
				} else {
					$( "#searcherror" ).show();
					$( "#searchempty" ).hide();
					$.get( 'loadusertable.php', function ( data ) {
						$( '#user-table' ).html( data );
					} );
				}
			} );
		}
	} );
</script>