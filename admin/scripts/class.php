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
		}
	});
</script>