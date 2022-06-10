/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    for(var i=0; i<300; i+=30) {
        var url = $('#url').val()+"/init/"+i;
        jQuery.get(url,function(data){
            $('#result').append(data);
        });
    }
});

