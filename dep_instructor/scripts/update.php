<script type="text/javascript">
function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
function deleteuser( idstudents ) {
    //var idteacher="<?php /*echo $idteacher*/?>";
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
}
$("#adminuser").submit(function(event) {
var student = {
        'surname':$( 'input#surname' ).val(),
        'middlename':$( 'input#middlename' ).val(),
        'givenname': $( 'input#givenname' ).val(),
        'moregivenname':$( 'input#moregivenname' ).val(),
        'street':$( 'input#street' ).val(),
        'postalcode':$( 'input#postalcode' ).val(),
        'province':$( '#province option:selected' ).val(),
        'birthdate':$( 'input#birthdate' ).val(),
        'birthtown':$( 'input#birthtown' ).val(),
        'birthcountry':$( 'input#birthcountry' ).val(),
        'nationality':$( '#nationality option:selected' ).val(),
        'sex':$( '#sex option:selected' ).val(),
        'classcode':$( '#classs option:selected' ).val(),
        'family_speech':$( 'input#family_speech' ).val(),
        'phone':$( 'input#phone' ).val(),
        'mobilephone':$( 'input#mobilephone' ).val(),
        'email':$( 'input#email' ).val(),
        'religion':$( 'input#religion' ).val(),
        'idgraduation':$( '#graduation option:selected' ).val(),
        'idberuf':$( '#Ausbildungsberuf option:selected' ).val(),
        'lanisid':0,
        'lusdid':0,
        'active':$("input:radio[name=status]:checked").val(),
        'indeutschlandseit':$( 'input#indeutschlandseit' ).val(),
        'sprachniveau':$( '#sprachniveau option:selected' ).val(),
        'parents':{
            'mother_surname':$( 'input#mother_surname' ).val(),
            'mother_lastname':$( 'input#mother_givenname' ).val(),
            'mother_address':$( 'input#mother_address' ).val(),
            'mother_postalcode':$( 'input#mother_plz' ).val(),
            'mother_phone':$( 'input#mother_phone' ).val(),
            'mother_mobilephone':$( 'input#mother_mobilephone' ).val(),
            'father_surname':$( 'input#father_surname' ).val(),
            'father_lastname':$( 'input#father_givenname' ).val(),
            'father_address':$( 'input#father_address' ).val(),
            'father_postalcode':$( 'input#father_plz' ).val(),
            'father_phone':$( 'input#father_phone' ).val(),
            'father_mobilephone':$( 'input#father_mobilephone' ).val(),
            'idparents':<?php echo $idparents; ?>
        },
        'lastschool':$( 'input#lastschool' ).val(),
        'lastschooldate':$( 'input#lastschooldate' ).val(),
        'lastschooltown':$( 'input#lastschooltown' ).val(),
        'lastschoolprovince':$( '#lastschoolprovince option:selected' ).val(),
        'Ausbildungsbeginn':$( 'input#ausbildungsbeginn' ).val(),
        'Ausbildungsbetrieb':{
            'Name':$( 'input#ausbildungsbetrieb_name' ).val(),
            'Strasse':$( 'input#ausbildungsbetrieb_strasse' ).val(),
            'PLZ':$( 'input#ausbildungsbetrieb_plz').val(),
            'Telefon':$( 'input#ausbildungsbetrieb_telefon' ).val(),
            'Fax':$( 'input#ausbildungsbetrieb_fax' ).val(),
            'Email':$( 'input#ausbildungsbetrieb_email' ).val(),
            'Ausbilder':{
                'Anrede':$( '#ausbildungsbetrieb_ausbilder_anrede option:selected' ).val(),
                'Name':$( 'input#ausbildungsbetrieb_ausbilder_name' ).val(),
            }
        },
        'idstudent':'<?php echo $id ?>',
        'entryDate':$( 'input#entryDate' ).val(),
        'exitDate':$( 'input#exitDate' ).val()
    };
    <?php
        echo " idstudents = \"".$id."\";";
    ?>
event.preventDefault();
$(".error_wrap").show();
if ( surname==''||givenname==''||birthdate=='') {
        $( "#emptyfield" ).show();
}
else {
    var url = "../api/v2/students.php";
    var xhr = new XMLHttpRequest();
    xhr.open("PATCH", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.setRequestHeader("uuid",getCookie("uuid"))
    xhr.onload = function () {
    var users = JSON.parse(xhr.responseText);
        if (xhr.readyState == 4 && xhr.status == "200") {
            console.table(users);
            if(users.success){
                var nodes = document.querySelectorAll(".field");
                if ( confirm( "gespeichert." ) ){
                    window.location.replace('/dep_instructor/index.php?site=update.class&id='+student.classcode);
                    }
            }
        } else {
            console.error(users);
        }
    }
    var json = JSON.stringify(student)
    xhr.send("student=" +json);
}
});
</script>