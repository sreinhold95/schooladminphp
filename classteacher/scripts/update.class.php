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
        ajaxURL: "../api/v2/class.php?classcode=<?php echo $classcode ?>", //ajax URL
        //ajaxProgressiveLoad:"scroll", //enable progressive loading
        //ajaxProgressiveLoadScrollMargin:300, //triger next ajax load when scroll bar is 300px or less from the bottom of the table.
        ajaxConfig: Config,
        index: "classcode",
        height: "700px",
        responsiveLayout: true,
        layout: "fitColumns",
        columns: [{
                title: "#",
                formatter: "rownum",
                align: "left",
                width: 20,
                headerSort: false,
                download: false
            },
            {
                title: "Status",
                field: "active",
                formatter: "tickCross",
                headerSort: false
            },
            {
                title: "Vorname",
                field: "surname",
                headerFilter: "input",
                headerFilterPlaceholder: "VN"
            },
            {
                title: "weitere VN",
                field: "middlename"
            },
            {
                title: "Nachname",
                field: "givenname",
                headerFilter: "input",
                headerFilterPlaceholder: "NN"
            },
            {
                title: "weitere NN",
                field: "moregivenname"
            },
            {
                title: "Geburtsdatum",
                field: "birthdate"
            },
            {
                title: "bearbeiten",
                headerSort: false,
                formatter: "link",
                formatterParams: {
                    label: "öffnen",
                    url: function(cell) {
                        return "index.php?site=update&id=" + cell.getData().idstudents
                    }
                },
                download: false
            },
            {
                title: "drucken",
                headerSort: false,
                formatter: "link",
                formatterParams: {
                    label: "drucken",
                    target: "_blank",
                    url: function(cell) {
                        return "../api/v2/stammblattsus.php?student=" + cell.getData().idstudents + "&uuid=" + getCookie("uuid")
                    }
                },
                download: false
            }
        ]
    });
    $("#download-xlsx").click(function() {
        table.download("xlsx", "alle_meine_Schueler.xlsx", {
            sheetName: "meine Schüler"
        });
    });

    /*function deleteuser( idstudents ) {
        var idteacher="";
        if ( confirm( "Möchten Sie wirklich deaktivieren" ) )
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
                        $.get( 'loadusertable.php?idteacher='+idteacher, function ( data ) {
                            $( '#user-table' ).html( data );
                        } );
                    }
                } );
            }
    }*/

    $("#Klasseneinstellungen").submit(function(event) {
        event.preventDefault();

        var activetoken = $('input#activate:checked').val()
        if (activetoken == 1) {
            if (confirm("Der Token gilt ab jetzt 15 Minutren.")) {
                var activetoken = $('input#activate:checked').val()
                var classcode = "<?php echo $classcode ?>"
                $.get('../../classteacher/function.php?token&activetoken=' + activetoken + '&classcode=' + classcode, function(data) {
                    data = JSON.parse(data);
                    if (data.success)
                        location.reload();
                });
            } else {}
        } else {
            if (confirm("Der Token wird jetzt deaktiviert.")) {
                var activetoken = $('input#activate:checked').val()
                var classcode = "<?php echo $classcode ?>"
                $.get('../../classteacher/function.php?token&activetoken=' + activetoken + '&classcode=' + classcode, function(data) {
                    data = JSON.parse(data);
                    if (data.success)
                        location.reload();
                });
            } else {}
        }
    });
</script>