function settown(){
    var postalcode = $( "#postalcode option:selected" ).val()
    //var postcalcode = document.getElementById("postalcode").value;
    //console.log (postalcode);
    $.get('../../api/v1/plz.php?plz='+postalcode,function(data){
        //console.log (data)
        //var jsondata = JSON.parse(data)
        document.getElementById("town").value = data.ort;
        $.get('../../api/v1/province.php?province='+data.bundesland,function(data1){
            //console.log (data1)
            document.getElementById('province').value=data1.idprovince
        });
        
    });
}
function settown(select,inputtown,province){
    var postalcode = $( select).val()
    //var postcalcode = document.getElementById("postalcode").value;
    //console.log (postalcode);
    $.get('../../api/v1/plz.php?plz='+postalcode,function(data){
        //console.table (data)
        //var jsondata = JSON.parse(data)
        document.getElementById(inputtown).value = data.ort;
        if(province=="true"){
            $.get('../../api/v1/province.php?province='+data.bundesland,function(data1){
                //console.log (data1)
                document.getElementById('province').value=data1.idprovince
            });
        }
    });
}
function settownpr(select,inputtown,province,provincefield){
    var postalcode = $( select).val()
    //var postcalcode = document.getElementById("postalcode").value;
    //console.log (postalcode);
    $.get('../../api/v1/plz.php?plz='+postalcode,function(data){
        //console.table (data)
        //var jsondata = JSON.parse(data)
        document.getElementById(inputtown).value = data.ort;
        if(province=="true"){
            $.get('../../api/v1/province.php?province='+data.bundesland,function(data1){
                //console.log (data1)
                document.getElementById(provincefield).value=data1.province
            });
        }
    });
}
function setdate(select,datefield){
    //$.get('../../api/v1/plz.php?plz='+postalcode,function(data){
        var state = $( select).val()
        if (state=="0"){
            var datum = new Date()
            date=datum.getDate()+"."+ (datum.getMonth()+ 1)+"." + datum.getFullYear()
            document.getElementById(datefield).value = date ;
        }else
            document.getElementById(datefield).value =null ;
    //});
}

function stringreplace(select,elementid,rep_string,replacer){
    var select = $( select).val()
    document.getElementById(elementid).value = select.replace(rep_string,replacer);
        
}
function print(classcode,idteacher){
    var endpoint = "../../pdf/Stammdaten.php";
    var params = {
        idteacher:idteacher,
        classcode:classcode
      }
    var url = endpoint + formatParams(params)
    var win = window.open(url, '_blank');
    win.focus();
}

function formatParams( params ){
    return "?" + Object
          .keys(params)
          .map(function(key){
            return key+"="+encodeURIComponent(params[key])
          })
          .join("&")
  }
function tabellen_none(select,tabelle,apikey){
    var Berufs_ID = $( select).val()
    
    var endpoint = "../../api/v1/beruf.php";
    var params = {
        Berufs_ID: Berufs_ID, 
        apikey: apikey
      }
    var url = endpoint + formatParams(params)
		var xhr = new XMLHttpRequest();
		xhr.open("get", url, true);
		xhr.onload = function () {
		var users = JSON.parse(xhr.responseText);
			if (xhr.readyState == 4 && xhr.status == "200") {
				if(users.Schulform=="Teilzeit"){
                    document.getElementById(tabelle).style.display="";
                    document.getElementById("Abgang").style.display="";
                }else{
                    document.getElementById(tabelle).style.display="none";
                    document.getElementById("Abgang").style.display="none";
                }
			} else {
				console.error(users);
			}
		}
		xhr.send();
}

function classfilter(){
    var classcode = $( "#classs option:selected" ).val();
    event.preventDefault();
    if ( classcode == '' ) {
        $( "#searchempty" ).show();
        $( "#searcherror" ).hide();
        $("deleteuser").hide();

    } else {
        $.get( 'function.php?class&filter=' + classcode, function ( data ) {
            console.log(data);
            var jsondata = JSON.parse( data );
            if ( jsondata.success ) {
                $( "#searcherror" ).hide();
                $( "#searchempty" ).hide();
                $("deleteuser").hide();
                $.get( 'function.php?filter_class_result1&filter=' + classcode, function ( data ) {
                    $( '#user-table' ).html( data );
                } );
            } else {
                $( "#searcherror" ).show();
                $( "#searchempty" ).hide();
                $("deleteuser").hide();
                $.get( 'loadusertable.php', function ( data ) {
                    $( '#user-table' ).html( data );
                } );
            }
        } );
    }
} 