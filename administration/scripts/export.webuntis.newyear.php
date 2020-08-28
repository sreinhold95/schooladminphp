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
    let table = new Tabulator("#students", {
        ajaxURL: "../api/v2/export.php?webuntisnewyear", //ajax URL
        //ajaxProgressiveLoad:"scroll", //enable progressive loading
        //ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
        ajaxConfig: Config,
        index: "classcode",
        height: "700px",
        width:"700px",
        responsiveLayout: true,
        responsiveLayout: "hide",
        //layout: "fitColumns",
        //pagination: "local", //enable local pagination.
        autoColumns: true
        /*columns:[
        	{title:"#",formatter:"rownum", align:"left", width:20, headerSort:false},
        	{title:"Aktiv", field:"active",formatter:"tickCross", headerSort:false},
        	{title:"Vorname", field:"surname", headerFilter:"input", headerFilterPlaceholder:"VN"},
        	{title:"Nachname", field:"givenname", headerFilter:"input", headerFilterPlaceholder:"NN"},
        	{title:"Klasse", field:"classcode"},
        	{title:"bearbeiten",formatter:"link", headerSort:false,formatterParams:
        		{
        			label:"öffnen",
        			url:function(cell){return "index.php?site=update&id=" + cell.getData().idstudents}
        		}
        	},
        	{title:"drucken",formatter:"link", headerSort:false,formatterParams:
        		{
        			label:"drucken",
        			target:"_blank",
        			url:function(cell){return "../api/v2/stammblattsus.php?student="+ cell.getData().idstudents+"&uuid="+getCookie("uuid") }
        		}
        	},
        	{title:"deaktivieren",formatter:"link", headerSort:false,formatterParams:
        		{
        			label:"deaktivieren",
        			target:"_blank",
        			url:function(cell){return ""}
        		}
        	}
        ]*/
    });
    $("#exportcsv").click(function() {
        table.download("csv", "webuntis.csv", {
            delimiter:";",
            bom:true
        });
    });
</script>