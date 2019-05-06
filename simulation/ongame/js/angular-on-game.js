var ongame = angular.module('ongame', ['ngRoute', 'btford.socket-io']);

// ongame.config(['$routeProvider', function($routeProvider) {
//     $routeProvider
//     .when('/game' , {
        
//         templateUrl: 'index.html',
//         controller: 'waitingCtrl'
//     })

// }]);

ongame.controller('waitingCtrl', function($scope, $http, mySocket){

    // $scope.sendMe = function(){
    //     mySocket.emit('chat', {
    //         message: "hello po sir"
    //     });
    // };

    mySocket.on('connect',function(){
    	mySocket.emit('hostEnt','Chris');
    });

    $scope.sendMe = function(){
    	mySocket.emit('hostQ',{qName: $scope.qName});
    }

});