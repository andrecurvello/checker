$(document).ready(function(){
    
});
        
$(document).ajaxStart(function(){
    $("#loader").show();
});

$(document).ajaxStop(function(){
    $("#loader").hide();
});     

var json = {
    "server":[]
};

$.getJSON('./config.json', function(json) {

    $.each( json["server"], function(key,url){

        var json_server = {
            "url": url,
            "tbl_content" : []
        };
        
        json.server.push(json_server);

        $.ajax({
            type: "GET",
            cache: false,
            async: false,
            dataType: "text",
            url: "call_probe.php?server="+url,
            success: function (data) {

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
                            var label = "label-important";
                        }
                        
                        var json_app = {
                            "app": app,
                            "label": label,
                            "version": version["local"],
                            "latest": version["remote"]
                        };
                        json_server.tbl_content.push(json_app);
                        
                    });
                });
                
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                console.log("error");
            }
        });

    });

    nameDecorator = function() { 
        return "<span class='label " + this.label + "'>" + this.version + "</span>"; 
    };
    var directives = { server: { tbl_content: { vs: { html: nameDecorator } } } };

    $('#checker').render(json, directives);
});
