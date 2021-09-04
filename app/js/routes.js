
var app = angular.module("xchat", ['ngRoute']);

var activeMenu = "home";

app.config(function($routeProvider){
    $routeProvider
    .when("/", {
        templateUrl: "/views/home.html",
        controller: 'mainController',
        activeMenu: 'home'
    })

    .when("/login", {
        templateUrl: "/views/login.html",
        controller: 'mainController',
        activeMenu: 'login'
    })

    .when("/register", {
        templateUrl: "/views/register.html",
        controller: 'mainController',
        activeMenu: 'register',
    })
 
    .when("/pricing", {
        templateUrl: "/views/pricing.html",
        controller: 'mainController',
        activeMenu: 'pricing'
    })

    .when("/dashboard", {
        templateUrl: "/views/dashboard.html",
        controller: 'chatController',
    })

    .when("/chats", {
        templateUrl: "/views/chats.html",
        controller: 'chatController',
    })

    .when("/members/:chatId", {
        templateUrl: "/views/members.html",
        controller: "membersController"
    })

    .when("/faq", {
        templateUrl: "/views/faq.html",
        controller: 'mainController',
        activeMenu: 'login',
    })

    .when("/bugreport", {
        templateUrl: "/views/bugs.html",
        controller: 'mainController',
        activeMenu: 'login'
    })


    .when("/contact", {
        templateUrl: "/views/contact.html",
        controller: 'mainController',
        activeMenu: 'login'
    })
});

app.controller('mainController', ['$scope', '$route', '$http', '$window', 
            function($scope, $route, $http, $window){
    $scope.route = $route;

    $scope.logout = function(){
        $http.get('/api/auth/logout').then(function(response){
            $window.location.reload();
            $window.location.href= '/#!/';
        });
    }
}]);