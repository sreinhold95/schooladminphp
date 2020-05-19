<script type="text/javascript">

function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
var Config = {
    method:"get", //set request type to Position
    headers: {
		"Content-type": 'application/json; charset=utf-8',//set specific content type
		"uuid": getCookie("uuid"),
		"tab": "yes"
    }
};
var table = new Tabulator("#classes", {
    ajaxURL:"../api/v2/classes.php", //ajax URL
    //ajaxProgressiveLoad:"scroll", //enable progressive loading
	//ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
	ajaxConfig:Config,
	index:"classcode",
	height:"700px",
	//responsiveLayout:true,
	layout:"fitDataFill",
	pagination: "local", //enable local pagination.
	columns:[
        {title:"#",formatter:"rownum", align:"center", width:57, headerSort:false},
        {title:"Klasse", field:"classcode"},
        {title:"Langname", field:"longname"},
		// {title:"öffnen", field:"headidteacher", headerSort:false}
		{title:"öffnen",formatter:"link", headerSort:false,formatterParams:
			{
				label:"öffnen",
				url:function(cell){return "index.php?site=update.class&id=" + cell.getData().classcode}
			}
		},
    ]
});




// $(document).ready(function() {
// 	$.ajax({
// 		url:'../api/v2/classes.php',
// 		type:'GET',
// 		headers:{"uuid": getCookie("uuid")},
// 		success:function(data){
// 			//var result = JSON.parse(data);
// 			var i=1
// 			$.each(data, function(key, value){
// 				$("#classes >tbody:last").append(
// 						$('<tr>').append(
// 							$('<th scope="row">').append(i)
// 							.append(
// 								$('</th>').append(
// 									$('</tr>')
// 									)
// 								)
// 							)
// 						);
// 					i++
// 				$.each(value, function(k, v){
// 					if(k === "classcode"){
// 						$("#classes >tbody >tr:last").append(

// 							$('<td>').append('<a href="index.php?site=update.class&idteacher=0&class=1&id='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
// 							.append(
// 								$('</td>')
// 								)
// 						);
// 					}
// 					if(k === "classcode"){
//                         $("#classes >tbody >tr:last").append(

//                             $('<td>').append(v)
//                             .append(
//                                 $('</td>')

//                                 )

//                             );
// 					}
					
// 					if(k === "longname"){
// 						$("#classes >tbody >tr:last").append(

// 							$('<td>').append(v)
// 							.append(
// 								$('</td>')

// 								)

// 						);
// 					}
// 				});
				
// 			});
// 		}
// 	})
// });
	// $( "#classs" ).change( function ( event ) {
	// 	var classcode = $( "#classs option:selected" ).val();
	// 	event.preventDefault();
	// 	if ( classcode == '' ) {
	// 		$( "#searchempty" ).show();
	// 		$( "#searcherror" ).hide();
	// 		$("deleteuser").hide();

	// 	} else {
	// 		$("#classes tbody tr").remove(); 
	// 		$.ajax({
	// 			url:'../api/v2/classes.php?classcode='+classcode,
	// 			type:'GET',
	// 			headers:{"uuid": getCookie("uuid")},
	// 			success:function(data){
	// 				//var result = JSON.parse(data);
	// 				var i=1
	// 				$.each(data, function(key, value){
	// 					$("#classes >tbody:last").append(
	// 							$('<tr>').append(
	// 								$('<th scope="row">').append(i)
	// 								.append(
	// 									$('</th>').append(
	// 										$('</tr>')
	// 										)
	// 									)
	// 								)
	// 							);
	// 						i++
	// 					$.each(value, function(k, v){
	// 						if(k === "classcode"){
	// 							$("#classes >tbody >tr:last").append(

	// 								$('<td>').append('<a href="index.php?site=update&idteacher=0&class=1&id='+v+'" class="link"><img src="../style/edit.png" alt="Edit"></a>')
	// 								.append(
	// 									$('</td>')
	// 									)
	// 							);
	// 						}
	// 						if(k === "classcode"){
	// 							$("#classes >tbody >tr:last").append(

	// 								$('<td>').append(v)
	// 								.append(
	// 									$('</td>')

	// 									)

	// 								);
	// 						}
							
	// 						if(k === "longname"){
	// 							$("#classes >tbody >tr:last").append(

	// 								$('<td>').append(v)
	// 								.append(
	// 									$('</td>')

	// 									)

	// 							);
	// 						}
	// 					});
						
	// 				});
	// 			}
	// 		})  
	// 	}
	// });
</script>