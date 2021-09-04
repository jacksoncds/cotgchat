
// -------------------------------------------- //
//               Members Controller             //
// -------------------------------------------- //
app.controller('membersController', function($routeParams){
    console.log($routeParams);
});

// -------------------------------------------- //
//              Register Controller             //
// -------------------------------------------- //

app.controller('registerController', ['$scope', '$http', function($scope, $http){

    $scope.register = function(){

        var formData = {"email":$scope.email, "password": $scope.password};

        $http({
        method: 'POST',
        url: '/api/auth/register',
        data: JSON.stringify(formData)
        
        }).then(function(response){
            console.log(response);
        });
    }

}]);

// -------------------------------------------- //
//                 Login Controller             //
// -------------------------------------------- //
app.controller('loginController', ['$scope', '$http', '$window', 
                function($scope, $http, $window){

    $scope.login = function(){

        var formData = {"email":$scope.email, "password": $scope.password};

        $http({
        method: 'POST',
        url: '/api/auth/login',
        data: JSON.stringify(formData)
        
        }).then(function(response){
            if(response.data.status == 0){
                $window.location.reload();
                $window.location.href = "/#!/";
            }
        });
    }

    //Redirect home if logged in when route to login page.
    $http.get('api/member/get').then(function(response){
        if(response.data.code == 0){
            $window.location.href= '/#!/';
        }
    });

}]);

// -------------------------------------------- //
//                  FAQ Controller              //
// -------------------------------------------- //
app.controller('faqController', ['$scope', '$http', function($scope, $http){

        $http({
        method: 'GET',
        url: '/api/faq',
        }).then(function(response){
            $scope.data = response.data;
        });
}]);

// -------------------------------------------- //
//                  Chat Controller             //
// -------------------------------------------- //
app.controller('chatController', ['$scope', '$http', function($scope, $http){
    $scope.getChats = function(){
        $http({
            method: 'GET',
            url: '/api/chat/get',
        }).then(function(response){
            $scope.chats = response.data;
        });
    }

    $scope.getChats();
}]);

app.controller('ichatController', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope){

    var connenction = new WebSocket('ws://localhost:8888');

    $scope.chatEntries = [];
    $scope.chatStatuses = [];
    $scope.chatCommands = [];

    connenction.onopen = function(e) {
        settings.get();
        settings.createChats();

        $scope.authenticate($scope.chatId);

        $scope.chatStatuses.push("Connected to chat #:" + $scope.chatId);
    };

    connenction.onmessage = function(e) {
        $scope.chatEntries.push(JSON.parse(e.data));
        $rootScope.$digest();
    }

    $scope.chatId = 0;
    
    $scope.send = function(msg){
        connenction.send(msg);
        $scope.msgInput = "";
    }

    $scope.getChats = function(){
        $http({
            method: 'GET',
            url: '/api/chat/get',
        }).then(function(response){
            $scope.chats = response.data;
        });
    }

    $scope.getChats();

    $scope.authenticate = function(chatid) {
        settings.get();
        for(var i = 0; i < settings.data.chats.length; i++){
            console.log(chatid);
            if(settings.data.chats[i].chatid == chatid){
                            
                var auth = settings.data.chats[i];
                auth.name = settings.data.username;
                console.log("auth \n");
                console.log(JSON.stringify(auth));
                connenction.send("!@auth" + JSON.stringify(auth));
            }
        }
    }

    $scope.createChat = function(){

        var formData = {
            "name": $scope.name, "alliances":$scope.alliances, 
            "world": $scope.world, "timezone": $scope.timezone,
            "price": $scope.plan, "textcolor": $scope.textcolor
        };

        $http({
            method: 'POST',
            url: '/api/chat/create',
            data: JSON.stringify(formData)
        }).then(function(response){
            console.log(response);
        });
    };

}]);