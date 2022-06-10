$(document).ready(function(){
//    $('#zoom').css("left", parseInt($('table.list').width())+310);
//    if(parseInt($("table.list").width())>430)
//        $('table.list').css({"width":'430px','overflow':'hidden'});

    $(".etudiant").click(function(event){
        event.stopPropagation();
        var view = $(this);
        var user = $(this).children('td').last().html();
        $('#zoom').html("<img src='/img/icons/load.gif'/>");
        //alert(view.attr("id"));
        if(view.attr("id")) {
            jQuery.get(view.attr("ajax"),function(data){
                //if(!$(data).first().is('h1')) history.go(0);
                //else
                $('#zoom').html(data);
                $("#addemail").click(function(){
                    var form = $(this).parent()
                    var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
                    if(reg.test($("#email").val())) {
                        $("#email").val($("#email").val().toLowerCase());
                        jQuery.post(form.attr("action"),form.serialize(),function(dataemail){
                            form.parent().html(dataemail);
                            form.remove();
                            editEmail();
                        });
                    }else alert("Format d'adresse mail incorrect !");
                });
                editEmail();

                $('#testpassword').html("<img src='/img/icons/load.gif'/>");
                $('#testpassword').ready(function(){
                    jQuery.get($('#testpassword').attr("ajax"),function(data2){
                        $('#testpassword').html(data2);
                        var url = $("#reinit").attr("href");
                        $("#reinit").removeAttr("href");
                        $("#reinit").css('cursor','pointer');
                        
                        $("#sendMail").click(function(){
                            $("#sendMailHidden").val(this.checked);
                        });
                        $("#reinit").click(function(){
                            var sendMail = $("#sendMail").is(":checked");//$("#sendMailHidden").val(); 
                            $('#testpassword').html("<img src='/img/icons/load.gif'/>");
                            jQuery.get(url+'?sendmail='+sendMail,function(data){
                                console.log(data);
                                if(data=="true")
                                    $('#testpassword').html("Mot de passe réinitialisé !");
                                else $('#testpassword').html(data);
                            });
                        });
                    });
                });
            });
        } else {
            //alert(view.attr("ajax"));
            $('#zoom').html("<h1 style='color:orange'>"+user+"</h1>Compte à faire recréer par la DSI.  Cliquez sur <a id='alertsiscol' href='javascript:void(0);'>Alerter l'assistance siscol</a> pour envoyer un mail automatique à l'assistance siscol.");
            $('#alertsiscol').click(function(){
                jQuery.get(view.attr("ajax")+" "+user,function(retour){
                    var email = $("#siscolmail").val();
                    alert(email);
                    if(retour=="1")
                        $('#zoom').html("L'assistance siscol a été prévenue.<br/>Merci d'appeler l'assistance siscol !");
                    else
                        $('#zoom').html("Echec de l'envoi de mail !<br>L'assistance n'a pas été prévenue ! Veuiller envoyer un mail à siscol.assistance@cnam.fr en donnant les informations suivantes :<br>"+user+" "+url[1]);
                });
            });
        }
    });
    $(".addgroup").each(function(index){
        $(this).attr('ajax',$(this).attr('href'));
        $(this).removeAttr("href");
        var pos=$(this).position();
        var user = $(this);
        $(this).bind("click",function(){
            $(".addgroup").each(function(index){
                $(this).parent().css("color","");
            });
            $(this).parent().css("color","red");
            var url = $(this).attr('ajax');
            jQuery.get($(this).attr('ajax'),function(data){
                $('#zoom').html(data);
                $('#zoom').css({
                    "position":"absolute",
                    "top":pos.top-30,
                    "left":pos.left+150
                });
                $("#addingroup").click(function(){
                    url = url+"/group/"+$("#ingroup").attr("value");
                    jQuery.get(url,function(data){
                        //$('#zoom').html(data);
                        $('#zoom').empty();
                        window.location.reload();
                    });
                });
            });
        });
    });
    
    $(".deletegroup").each(function(el){
        $(this).attr('ajax',$(this).attr('href'));
        $(this).removeAttr("href");
        var pos=$(this).position();
        var group = $(this);        
        var p = group.parent().parent().children("td");
        p = p[1];
        var username = $(p).html();
        $(this).bind("click",function(){
            $(".addgroup").each(function(index){
                $(this).parent().css("color","");
            });
            $(this).parent().css("color","red");
            var url = $(this).attr('ajax');
            
            $('#zoom').html("<div>Retirer "+username+" du groupe "+$(this).text()+"?</div><input type=\"button\" id=\"deletegroup\" value=\"Oui\"/> </div><input type=\"button\" id=\"escape\" value=\"Non\"/>");
            $('#zoom').css({
                "position":"absolute",
                "top":pos.top-30,
                "left":pos.left+150
            });
            $("#deletegroup").click(function(){
                
                jQuery.get(url,function(data){
                    $('#zoom').empty();
                    group.remove();
                    //window.location.reload();
                });
            });
            $("#escape").click(function(){
                $('#zoom').empty();
            });
        });        
    });

    $(".delete").each(function(el){
        $(this).attr('action',$(this).attr('href'));
        $(this).removeAttr('href');
        $(this).click(function(e){
            if(confirm("Voulez-vous supprimer cette enregistrement ?"))
                $(this).attr('href',$(this).attr('action'));
            e.stopPropagation();
        });
    });
    
    $('#printuser').click(function(){
        var mywindow = window.open('', 'DSI Cnam', 'height=400,width=600');
        mywindow.document.write('<html><head><title>my div</title>');
        mywindow.document.write('<html><head><title>my div</title>');
        mywindow.document.write('<link type="text/css" rel="stylesheet" media="screen" href="/css/screen.css">');
        mywindow.document.write('<link type="text/css" rel="stylesheet" media="print" href="/css/print.css">');
        mywindow.document.write('</head><body >');
        var content = $('<div id="content"></div>').html($('#userView').html());
        content.find(".noprint").remove();
        mywindow.document.write(content.html());
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();
    });
    //alert(document.width/2-300);
//    $("#dansreprise").css("left",document.width/2-260);
//    $("#dansreprise a").click(function(){
//        $("#dansreprise").remove();
//    });
//    $("#dansreprise").ready(function(){
//
//        $("#dansreprise").css("border-color","#A00");
//        setInterval(function(){
//            if($("#dansreprise").css("border-color")=="rgb(170, 0, 0)") {
//                $("#dansreprise").css("border-color","#Acc");
//            }
//            else $("#dansreprise").css("border-color","#A00");
//        },500);
//    });
});


function editEmail() { 
    $("#editemail").click(function(){
        var email = jQuery.trim($(this).parent().first().text());
        var url = $(this).attr('action');
        var form = $('<form style="display:inline" method="post"><input type="text" id="email" name="email" value="'+email+'"/><input type="button" value="ok" id="saveemail" /></form>');
        $(this).parent().html(form);
        $("#saveemail").click(function(){
            var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
            if(reg.test($("#email").val())) {
                $("#email").val($("#email").val().toLowerCase());
                jQuery.post(url,form.serialize(),function(dataemail){
                    form.parent().html($(dataemail));
                    editEmail();
                    form.remove();
                });
            }else alert("Format d'adresse mail incorrect !");
        });
    });

    $('#printuser').click(function(){
        var mywindow = window.open('', 'DSI Cnam', 'height=400,width=600');
        mywindow.document.write('<html><head><title>my div</title>');
        mywindow.document.write('<html><head><title>my div</title>');
        mywindow.document.write('<link type="text/css" rel="stylesheet" media="screen" href="/css/screen.css">');
        mywindow.document.write('<link type="text/css" rel="stylesheet" media="print" href="/css/print.css">');
        mywindow.document.write('</head><body >');
        var content = $('<div id="content"></div>').html($('#userView').html());
        content.find(".noprint").remove();
        mywindow.document.write(content.html());
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();
    });
}