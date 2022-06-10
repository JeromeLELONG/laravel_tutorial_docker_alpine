$(document).ready(function(){
    $(".deletable").each(function(e,field){
        //console.log($(field).attr("id"));
        $(field).after(" <img class='delete_"+$(field).attr("id")+"' src='/admindsi/img/icons/delete.png");
        activeDeleter($(field).attr("id"));
    });
    $(".adder").mouseup(function(e){
        var id = $(this).attr("id");
        id = id.substr(3, id.length);
        var lasttagId = "add"+id+"-label";
        var numTag = $(this).parent().parent().children("dt").length -1 ;
        var input = $(this).parent().parent().children("dd").children("input");
        input = input[0];
        var size = $(input).attr("size");
        $("#"+lasttagId).before("<dt class='"+id+"_"+numTag+"'>"+numTag+":</dt><dd class='"+id+"_"+numTag+"'><input type='text' name='"+id+"_"+numTag+"' size='"+size+"'/><img class='delete_"+id+"_"+numTag+"' src='/img/icons/delete.png'/></dd>");
        activeDeleter(id+"_"+numTag);
        //$(e).stopPropagation();
    });
});

function activeDeleter(id){
    $(".delete_"+id).click(function(){
        if(confirm("Voulez-vous sipprimer ce champs ?")) {
            $(this).remove();
            $("."+id).remove();
            $('dt label').each(function(e,field){
                //alert($(field).attr('for')+" "+id);
                if($(field).attr("for")==id) $(field).parent().remove();
            });
        }
    });
}

