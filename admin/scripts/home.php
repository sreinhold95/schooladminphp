<script type="text/javascript">
	//import {TabulatorFull as Tabulator} from 'tabulator-tables';
	function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
	let infoIcon = function(value, data, cell, row, options) {
		//return '<a href="index.php?site=update&id='+v+'" class="link"><img class="infoImage" src="../style/edit.png" alt="Edit"></a>';
		return "<img class='infoImage' src='../style/edit.png'>";
	};
	let printIcon = function(cell, formatterParams) { //plain text value
		return "<i class='fa fa-print'></i>";
	};
	let i=0;
	let Config = {
		method: "get", //set request type to Position
		headers: {
			"Content-type": 'application/json; charset=utf-8', //set specific content type
			"uuid": getCookie("uuid"),
			"tab": "yes"
		}
	};
	let table = new Tabulator("#students", {
		ajaxURL: "../api/v2/students.php?lastdays=7", //ajax URL
		//progressiveLoad:"load",
		//ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
		ajaxConfig: {
			method: "get",
			headers: {
				"Content-type": 'application/json; charset=utf-8', //set specific content type
				"uuid": getCookie("uuid"),
				"tab": "yes"
			}
		},
		index: "classcode",
		//responsiveLayout: true,
		//responsiveLayout: "hide",
		layout: "fitColumns",
		height: "400px",
		pagination: "local",
		columns: [{
				title: "#",
				formatter: "rownum",
				hozAlign:"left",
				width: 20,
				headerSort: false
			},
			{
				title: "Vorname",
				field: "surname"
			},
			{
				title: "Nachname",
				field: "givenname"
			},
			{
				title: "Klasse",
				field: "classcode"
			},
			{
				title: "geändert von",
				field: "changedby"
			},
			{
				title: "bearbeiten",
				formatter: "link",
				headerSort: false,
				formatterParams: {
					label: "öffnen",
					url: function(cell) {
						return "index.php?site=update&id=" + cell.getData().idstudents
					}
				}
			},
			{
				title: "erledigt ?",
				formatter: "buttonTick",
				headerSort: false,
				cellClick: function(e, cell, value, data) {
					var url = "../../api/v2/students.php" ;
					var xhr = new XMLHttpRequest();
					xhr.open("PATCH", url, false);
					xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xhr.setRequestHeader("uuid", getCookie("uuid"))
					xhr.send("setdone=1&idstudent=" + cell.getData().idstudents);
					location.reload();
				}
			}
		]
	});
	/*table.on("dataLoaded", function(data){

		if (i==0){
			table.setData("../api/v2/students.php?lastdays=7",{},Config);
			i=+1;
		}
		else{

		}
});*/
	
	function loaddata() {
		table.setData("../api/v2/students.php?lastdays=7",{},Config);
	}
</script>