$(document).ready(function(){
    
});
        
$(document).ajaxStart(function(){
    $("#loader").show();
});

$(document).ajaxStop(function(){
    $("#loader").hide();
});     

$.getJSON('./config.json', function(json) {

    var json_server = "{server:[";

    $.each( json["server"], function(key,url){

        json_server += "{url:'" + url + "',tbl_content:["; 

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
                        var regNewline = new RegExp("(\r\n|\r|\n)", "g" );
                        version["local"]  = version["local"].replace(regNewline,''); 
                        version["remote"] = version["remote"].replace(regNewline,''); 

                        if(version["local"] == version["remote"]) {
                            var label = "label-success";
                        }
                        else if(version["local"] == 0 || version["remote"] == 0) {
                            var label = "";
                        }
                        else {
                            var label = "label-important";
                        }
                        
                        json_server += "{app:'" + app + "',label:'" + label + "',version:'" + version["local"] + "',latest:'" + version["remote"] + "'},";

                    });
                });
                
            },
            error: function (XMLHttpRequest, textStatus, errorThrows) {
                console.log("error");
            }
        });

        json_server += "]},";

    });

    json_server += "]}";

    nameDecorator = function() { 
        return "<span class='label " + this.label + "'>" + this.version + "</span>"; 
    };
    var directives = { server: { tbl_content: { vs: { html: nameDecorator } } } };

    $('#checker').render(eval("(" + json_server + ")"), directives);
});
