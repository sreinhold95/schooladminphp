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
        //ajaxURL: "../api/v2/export.php?iserv", //ajax URL,
        ajaxURL: "../api/v2/export.php?iservstudents=1&school=all", //ajax URL
        //ajaxProgressiveLoad:"scroll", //enable progressive loading
        //ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
        ajaxConfig: Config,
        index: "classcode",
        height: "700px",
        responsiveLayout: true,
        responsiveLayout: "hide",
        layout: "fitColumns",
        //pagination: "local", //enable local pagination.
        autoColumns: true
    });
    
    let table1 = new Tabulator("#students1", {
        ajaxURL: "../api/v2/export.php?iservteacher", //ajax URL
        //ajaxProgressiveLoad:"scroll", //enable progressive loading
        //ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
        ajaxConfig: Config,
        index: "classcode",
        height: "700px",
        responsiveLayout: true,
        responsiveLayout: "hide",
        layout: "fitColumns",
        //pagination: "local", //enable local pagination.
        autoColumns: true
    });
   

    $("#exportcsv").click(function() {
        table.download("csv", "iserv.csv", {
            delimiter:",",
            bom:true
        });
    });
    $("#exportcsv1").click(function() {
        table1.download("csv", "iserv_lul.csv", {
            delimiter:",",
            bom:true
        });
    });
</script>