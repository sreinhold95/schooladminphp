<script type="text/javascript">
    function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}
    $(".hBack").on("click", function(e) {
        e.preventDefault();
        window.history.back();
    });
    $("#adminuser").submit(function(event) {
        var teacher = {
            'surname':$( 'input#surname' ).val(),
            'middlename':$( 'input#middlename' ).val(),
            'lastanme': $( 'input#lastname' ).val(),
            'moregivenname':$( 'input#moregivenname' ).val(),
            'initials':$( 'input#initials' ).val(),
            'school':$( '#school option:selected' ).val(),
            'idteacher':<?php echo $idteacher?>
        };
        if ( surname==''||lastname==''||initials==''||school==''||idteacher=='') {
            //$( "#emptyfield" ).show();
        }
        else {
            var url = "../api/v2/teacher.php";
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
                            window.location.replace('/admin/index.php?site=teacher');
                            }
                    }
                } else {
                    console.error(users);
                }
            }
            var json = JSON.stringify(teacher)
            xhr.send("teacher=" +json);
        }
    });
</script>