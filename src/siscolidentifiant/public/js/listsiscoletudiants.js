$(document).ready(function(){
    var debut = 0;
    var fin = 100;
    var url = $('#url').val();
    var url2 = $('#url2').val();
    var url3 = $('#url3').val();
    for(var i=debut; i<fin; i++) {
        var is = i+"";
        var sapidMin="";
        for(var z=0; z<9-is.length; z++) sapidMin+="0";
        var sapidMax = sapidMin;
        sapidMin+=is+'0';
        sapidMax+=is+'9';

        //$('#etudiants').append('<tr><td>'+sapidMin+'</td><td>'+sapidMax+'</td><td>'+sapidMin.length+'<td></tr>');
        var datas = "sapidMin="+sapidMin+"&sapidMax="+sapidMax;
        $.get(url,datas,function(response){
            $('#etudiants').append(response);
            var etudiants = $(response).filter('.etudiant');
            $.each(etudiants, function(i,etudiant){
                var sapid = etudiant.id;
                //$("#"+sapid).append($("<td>"+sapid+"</td>"));
                $.get(url2,'sapid='+sapid,function(email){
//                    //console.log(sapid+email);
                    if(email) {
                        $.get(url3,'sapid='+sapid,function(emailLdap){
                            if(email == emailLdap) {
                                $('#'+sapid).append("<td style='color:blue'>"+email+"</td>");
                            } else if(emailLdap=="" && emailLdap=="siscol.mel@cnam.fr") {
                                $.get(url3,'sapid='+sapid+"&email="+email,function(copieOk){
                                    if(copieOk)
                                          $('#'+sapid).append("<td style='color:green'>"+email+"</td>");
                                    else $('#'+sapid).append("<td style='color:red'>"+email+" echec de copie vers LDAP</td>");
                                });
                            }else {
                                $('#'+sapid).append("<td style='color:red'>"+email+" <> "+emailLdap+"(LDAP)</td>");
                            }
                        });
                    }
                    else {
                        $.get(url3,'sapid='+sapid,function(emailLdap){
                            if(emailLdap!="" && emailLdap!="siscol.mel@cnam.fr") {
                                $.get(url3,'sapid='+sapid+"&emailLdap="+emailLdap,function(copieOk){
                                    if(copieOk)
                                          $('#'+sapid).append("<td style='color:blue'>"+emailLdap+"</td>");
                                    else $('#'+sapid).append("<td style='color:orange'>"+emailLdap+" echec de copie depuis LDAP</td>");
                                });
                            } else  {
                                $('#'+sapid).append("<td style='color:red'>pas d'email</td>");
                            }
                        });

                    }
                });
            });
        });
    }
});