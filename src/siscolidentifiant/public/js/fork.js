$(document).ready(function(){
    $(".fork").each(function(){
        var span = $(this);
        console.log($(this).attr("param"));
        jQuery.get($("#urlfork").val(),'letters='+span.attr("param"),function(retour){
            span.text(retour);
        })
    });
});