/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    for(var i=0; i<26; i++) {
        for(var j=0; j<26; j++) {
            var nom = String.fromCharCode(i+65)+String.fromCharCode(j+65)+"*";
            var url = $('#url').val();
            jQuery.get(url,"sn="+nom,function(response){
                $('#result').append("<tr><td>"+response+"</tr></td>");
            });
        }
    }
});

