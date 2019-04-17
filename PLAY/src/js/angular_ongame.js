var ongame = angular.module('ongame',['ngRoute','ngStorage','ui.bootstrap']);

ongame.config(['$routeProvider', function($routeProvider) {
    
    $routeProvider
		.when('/login' , {
			resolve:{
 					"check": function($location,$localStorage){
 							if ($localStorage.loggedIn){
 									$location.path("/home");
 							}
 					}
 			},
			templateUrl: 'student-login.html'
     		//controller: 'logInCtrlr'
		})
        .when('/gamepin' , {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
			templateUrl: 'game-pin.html'
            //controller: 'viewQuizzesCtrlr'
		})
        .when('/waitingarea' , {
                        resolve:{
                                 "check": function($location,$localStorage){
                                         if (!$localStorage.loggedIn){
                                                 $location.path("/login");
                                         }
                                  }
                         },
                templateUrl: 'waiting-page.html'
                //controller: 'viewQuizzesCtrlr'
            })
}]);