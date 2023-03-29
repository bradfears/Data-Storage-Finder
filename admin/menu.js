$(document).ready(function(){
    $(document).on('click','#save-reorder',function(){
        var list = new Array();
        $('#sortable').find('.ui-state-default').each(function(){
             var id=$(this).attr('data-id');    
             list.push(id);
        });
        var data=JSON.stringify(list);        
        $.ajax({
        url: 'menu.php', // server url
        type: 'POST', //POST or GET 
        data: {token:'reorder',data:data}, // data to send in ajax format or querystring format        
        datatype: 'json',
        success: function(message) {
            alert("Menu updated successfully!");
        }
  
        });

    });    
     
});



