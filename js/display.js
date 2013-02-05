$(document).ready(function(){
    
});
        
$(document).ajaxStart(function(){
    $("#loader").show();
});

$(document).ajaxStop(function(){
    $("#loader").hide();
});     

$.getJSON('./config.json', function(json) {

    $.each( json["server"], function(key,url){

        $.ajax({
            type: "GET",
            cache: false,
            dataType: "text",
            url: "call_probe.php?server="+url,
            success: function (data) {
console.log(data);        
                json_version = JSON.parse(data);
                
                $.each(json_version, function(key, value){
                    $.each(json_version[key], function(app, version){
                        if(version["local"] == version["remote"]) {
                            var label = "label-success";
                        }
                        else if(version["local"] == 0 || version["remote"] == 0) {
                            var label = "";
                        }
                        else {
                            var label = "label-warning";
                        }
                        
                        $("#tbl_content").append("<tr>"+
                                                 "<td>"+app+"</td>"+
                                                 "<td><span class=\"label "+label+"\">"+version["local"]+"</span></td>"+
                                                 "<td>"+version["remote"]+"</td>/tr>");
                    });
                });
                
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                console.log("error");
            }
        });
    });
});
