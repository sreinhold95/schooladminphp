<script>
    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }
    $("#useranlegen").keypress(function(e) {
        //Enter key
        if (e.which == 13) {
            return false;
        }
    });


    $("#useranlegen").submit(function(event) {
        event.preventDefault();
        error = ['keine']
        var nodes = document.querySelectorAll(".form-control form-control-sm");
        for (var i = 0; i < nodes.length; i++) {
            if (nodes[i].value == "") {
                nodes[i].style = "background-color:red"
                error.push(nodes[i].name)
            } else {
                nodes[i].style = "background-color:white"
            }

        }
        if (new Date($('input#birthdate').val()) - new Date(new Date().toISOString().slice(0, 10)) > 0) {
            var nodes = document.querySelectorAll(".form-control form-control-sm eltern");
            for (var i = 0; i < nodes.length; i++) {
                if (nodes[i].value == "") {
                    nodes[i].style = "background-color:red"
                    error.push(nodes[i].name)
                } else {
                    nodes[i].style = "background-color:white"
                }

            }
        }
        if (error != null) {
            if (error.length > 1) {
                console.log(error)
                $(".error_wrap").show();
                $("#emptyfield").show();
                if (confirm("Das Speichern ist nicht möglich, \nbitte füllen Sie alle markierten Felder.")) {}
            } else {
                var student = {
                    'surname': $('input#surname').val(),
                    'middlename': $('input#middlename').val(),
                    'givenname': $('input#givenname').val(),
                    'moregivennname': $('input#moregivenname').val(),
                    'street': $('input#street').val()+' '+$('input#hnb').val(),
                    'postalcode': $('input#postalcode').val(),
                    'province': $('#province option:selected').val(),
                    'birthdate': $('input#birthdate').val(),
                    'birthtown': $('input#birthtown').val(),
                    'birthcountry': $('input#birthcountry').val(),
                    'nationality': $('#nationality option:selected').val(),
                    'sex': $('#sex option:selected').val(),
                    'classcode': $("#classc option:selected'").val(),
                    'family_speech': $('input#family_speech').val(),
                    'phone': $('input#phone').val(),
                    'mobilephone': $('input#mobilephone').val(),
                    'email': $('input#email').val(),
                    'religion': $('input#religion').val(),
                    'idgraduation': $('#graduation option:selected').val(),
                    'idberuf': $('#Ausbildungsberuf option:selected').val(),
                    'lanisid': 0,
                    'lusdid': 0,
                    'active': 1,
                    'indeutschlandseit': $('input#indeutschlandseit').val(),
                    'sprachniveau': $('#sprachniveau option:selected').val(),
                    'parents': {
                        'mother_surname': $('input#mother_surname').val(),
                        'mother_lastname': $('input#mother_givenname').val(),
                        'mother_address': $('input#mother_address').val(),
                        'mother_postalcode': $('input#mother_plz').val(),
                        'mother_phone': $('input#mother_phone').val(),
                        'mother_mobilephone': $('input#mother_mobilephone').val(),
                        'father_surname': $('input#father_surname').val(),
                        'father_lastname': $('input#father_givenname').val(),
                        'father_address': $('input#father_address').val(),
                        'father_postalcode': $('input#father_plz').val(),
                        'father_phone': $('input#father_phone').val(),
                        'father_mobilephone': $('input#father_mobilephone').val()
                    },
                    'lastschool': $('input#lastschool').val(),
                    'lastschooldate': $('input#lastschooldate').val(),
                    'lastschooltown': $('input#lastschooltown').val(),
                    'lastschoolprovince': $('#lastschoolprovince option:selected').val(),
                    'Ausbildungsbeginn': $('input#ausbildungsbeginn').val(),
                    'Ausbildungsbetrieb': {
                        'Name': $('input#ausbildungsbetrieb_name').val(),
                        'Strasse': $('input#ausbildungsbetrieb_strasse').val()+' '+$('input#ausbildungsbetrieb_hnb').val(),
                        'PLZ': $('input#ausbildungsbetrieb_plz').val(),
                        'Telefon': $('input#ausbildungsbetrieb_telefon').val(),
                        'Fax': $('input#ausbildungsbetrieb_fax').val(),
                        'Email': $('input#ausbildungsbetrieb_email').val(),
                        'Ausbilder': {
                            'Anrede': $('#ausbildungsbetrieb_ausbilder_anrede option:selected').val(),
                            'Name': $('input#ausbildungsbetrieb_ausbilder_name').val(),
                        }
                    },
                    'dsgvo':1,
                    'houserules':1,
                    'edvrules':1,
                };
                var url = "../../api/v2/students.php";
                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("uuid", getCookie("uuid"))
                xhr.setRequestHeader("class",$("input#classc").val())
                xhr.setRequestHeader("classtoken",getCookie("classtoken"))
                xhr.onload = function() {
                    var users = JSON.parse(xhr.responseText);
                    if (xhr.readyState == 4 && xhr.status == "200") {
                        console.table(users);
                        if (users.success) {
                            var nodes = document.querySelectorAll(".field");
                            if (confirm("gespeichert.")) {}
                            for (var i = 0; i < nodes.length; i++) {
                                nodes[i].value == "";
                            }
                            window.location.replace('../ndex.php?site=home');
                        }
                    } else {
                        console.error(users);
                    }
                }
                var json = JSON.stringify(student)
                xhr.send("student=" + json);
            }
        }
    });
</script>