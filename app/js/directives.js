
app.directive("parseEpoch", function(){
    return {
        scope: {

        },

        link: function(scope, element, attrs){
            
            var dateTime = new Date(attrs.parseEpoch * 1000); 
            element.replaceWith(dateTime.getFormattedTime());

            console.log(dateTime.getFormattedTime());
        },
    };
});