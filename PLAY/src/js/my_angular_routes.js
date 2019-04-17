ongame.config(['$routeProvider', function($routeProvider) {

$routeProvider
		.when('/home' , {
			templateUrl: 'login.html'
		})

		.otherwise{
			redirectTo: '/home'
		}
}