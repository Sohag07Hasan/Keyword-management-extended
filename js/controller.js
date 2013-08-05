jQuery(function($) {
        var cache = {};
        $( "#unique_keyword" ).autocomplete({
            minLength: 1,
            source: function( request, response ) {
                var term = request.term;
                              
                if ( term in cache ) {
                    response( cache[ term ] );
                    return;
                }
 
                $.getJSON( "http://localhost/wordpress/wp-content/themes/jf/includes/kwAjax.php", request, function( data, status, xhr ) {
                    var text = [];
                    var numbers = [];
                    var reA = /[^a-zA-Z]/g;
                    var reN = /[^0-9]/g;
                    for (var key in data) {
                        //console.log(data[key]);
                        var character = data[key].charAt(0);
                        if(character.replace(reA, "") == "")
                        {
                            numbers[key] = data[key];
                        } else if(character.replace(reN, "") == "")
                        {
                            text[key] = data[key];   
                        }
                    }
                    text = text.sort();
                    var all = text.concat(numbers);
                    var finalObject = '[';
                    var finalArray = [];
                    for(var i=0;i<(all.length-1);i++){
                        if(typeof all[i] !== 'undefined')
                        {
                            finalObject += '"'+all[i]+'", ';
                        }
                        //console.log(all[i]);
                    }
                    finalObject += '"'+all[all.length-1]+'" ';
                    finalObject += ']';
                    finalObject = JSON.parse(finalObject);
                    
                    cache[ term ] = finalObject;
                    response( finalObject );
                });
            },
            select: function(event, ui) { 
                var exploded = ui.item.label.split(' ~ ');
                var title = exploded[0];
                $("#title").val(title);
            }
        });
    });