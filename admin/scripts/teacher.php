<script type="text/javascript">
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
	let Config = {
		method: "get", //set request type to Position
		headers: {
			"Content-type": 'application/json; charset=utf-8', //set specific content type
			"uuid": getCookie("uuid"),
			"tab": "yes"
		}
	};
	let table = new Tabulator("#teacher", {
		ajaxURL: "../api/v2/teacher.php", //ajax URL
		//ajaxProgressiveLoad:"scroll", //enable progressive loading
		//ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
		ajaxConfig: Config,
		index: "initials",
		responsiveLayout: true,
		responsiveLayout: "hide",
		layout: "fitColumns",
		height: "800px",
		pagination: "local",
		columns: [{
				title: "#",
				formatter: "rownum",
				align: "left",
				width: 20,
				headerSort: false
			},
			{
				title: "Aktiv",
				field: "active",
				formatter: "tickCross",
				width: 60,
				headerSort: false
			},
			{
				title: "Kürzel",
				width: 90,
				field: "initials"
			},
			{
				title: "Vorname",
				width: 120,
				field: "surname"
			},
			{
				title: "Nachname",
				width: 120,
				field: "lastname"
			},
			{
				title: "Zeugnis Name",
				field: "name"
			},
			{
				title: "bearbeiten",
				formatter: "link",
				headerSort: false,
				width: 90,
				formatterParams: {
					label: "öffnen",
					url: function(cell) {
						return "index.php?site=update.teacher&idteacher=" + cell.getData().idteacher
					}
				}
			},
		]
	});
</script>