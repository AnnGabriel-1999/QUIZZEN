var homestead = angular.module('homestead',['ngRoute', 'homestead.controller', 'ngStorage', 'checklist-model', 'ui.bootstrap']);

homestead.factory('myService', function($rootScope,$http) {
    return {
        checkMeBaby: function() {
                $rootScope.fucker = localStorage.getItem('sa_id');
                
                getLink = "/restAPI/api/quizzes/homestead_user.php?admin_id="+ localStorage.getItem('sa_id');
                $http.get(getLink).then(function(response){
                $rootScope.user = response.data;
                console.log(response.data.user);
       });
            }
    };
});


homestead.directive('fileInput',function($parse){
	return{
		restrict:'A',
		link:function(scope,element,attribute){
			element.bind('change',function(){
				$parse(attribute.fileInput)
				.assign(scope,element[0].files)
				scope.$apply();
				document.getElementById("filezzz").innerHTML = element[0].files[0].name;
			});
		}
	}
});

homestead.directive('numbersOnly', function (){
	return {
		require: 'ngModel',
		link: function (scope, element, attr, ngModelCtrl){
			function fromUser(text){
				if (text){
					var transformedInput = text.replace(/[^0-9]/g, '');
					if (transformedInput !== text){
						ngModelCtrl.$setViewValue(transformedInput);
						ngModelCtrl.$render();
					}
					return transformedInput;
				}
				return undefined;
			}
			ngModelCtrl.$parsers.push(fromUser);
		}
	};
});

homestead.directive('disableBtn',
function() {
 return {
  restrict : 'A',
  link : function(scope, element, attrs) {
   var $el = $(element);
   var submitBtn = $el.find('button[type="submit"]');
   var _name = attrs.name;
   scope.$watch(_name + '.$valid', function(val) {
    if (val) {
     submitBtn.removeAttr('disabled');
    } else {
     submitBtn.attr('disabled', 'disabled');
    }
   });
  }
 };
}
);

homestead.config(['$routeProvider', function($routeProvider) {
    $routeProvider
    .when('/login' , {
		resolve:{
			"check": function($location,$localStorage){
					if ($localStorage.loggedIn2){
							$location.path("/home");
					}
			 }
		},
        templateUrl: 'login.html',
        controller: 'logInCtrlr'
    })

    .when('/viewCourses' , {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl: 'view-courses.html',
		controller: 'courseCtrl'
    })

	.when('/viewSections/:courseID/:prefIX/', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl: 'view-sections.html', 
		controller: 'sectionsCtrl'

	})

	.when('/viewAssigned/:sectionID/:courseID/:prefix', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl : 'view-assigned.html',
		controller : 'assignedCtrl'
	})

	.when('/changePW', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl : 'change-pw.html',
		controller: 'changePW'
	})

	.when('/viewSY', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl : 'school-years.html',
		controller: 'syCtrl'
	})

	.when('/viewAdmins', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl : 'employee-list.html',
		controller : 'empCtrl'
	})

	.when('/signup', {
		resolve:{
			"check": function($location,$localStorage){
					if ($localStorage.loggedIn2){
							$location.path("/home");
					}
			 }
		},
		templateUrl: 'signup.html',
		controller: 'heyWalaNaToGGnaTigilMoNa'
	})

    .when('/home', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl: 'request.html',
		controller: 'requestCtrl'
	})

	.when('/viewStudents/:section_id/:courseID/:prefix', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl: 'view-students.html',
		controller : 'studentCtrl'
	})

	.when('/downloadables', {
		resolve:{
			"check": function($location,$localStorage){
					if (!$localStorage.loggedIn2){
							$location.path("/login");
					}
			 }
		},
		templateUrl: 'downloadables.html',
		controller: 'downloadCtrl'
	})
	
	.when('/logout', {
		resolve:{
				 "check": function($location,$localStorage){
						 if (!$localStorage.loggedIn2){
								 $location.path("/login");
						 }
				 }
		 },
			templateUrl: 'login.html',
			controller: 'logoutCtrlr'
 	})

    .otherwise({
        redirectTo: '/home'
    })
}]);

angular.module('homestead.controller',[])
	.controller('logInCtrlr', ['$scope','$http','$location', '$localStorage',function($scope,$http,$location,$localStorage,$rootScope){
		$scope.inputType = 'password';
  $scope.showHideClass = 'fa fa-eye';

  $scope.showPassword = function(){
   if($scope.password != null)
   {
    if($scope.inputType == 'password')
    {
     $scope.inputType = 'text';
     $scope.showHideClass = 'fa fa-eye-slash';
    }
    else
    {
     $scope.inputType = 'password';
     $scope.showHideClass = 'fa fa-eye';
    }
   }
  }
  
        $scope.logIn = function(){
			$localStorage.loggedIn2 = false;

			sendData = JSON.stringify({"sa_username" : $scope.username , "sa_password" : $scope.password});
			link = "/restAPI/api/Homestead/login.php";

			$http.post(link,sendData).then(function(response){
				if(response.data.success){
					$localStorage.loggedIn2 = true;
					localStorage.setItem('sa_id',response.data.session);
 					$location.path("/home");
		}else{
			$scope.error = response.data;
		}
			}).catch(function(response) {
			  	console.log(response);
			});

		};
	}]);

homestead.controller('requestCtrl',function($scope,$http, $location, $route,myService){
    myService.checkMeBaby();
	$scope.requests = [];
	$scope.currentPage = 1;
	$scope.pageSize = 4;

	getLink = '/restAPI/api/homestead/schoolyears/get-active-sy.php';
	$http.get(getLink).then(function(response){
		localStorage.setItem('activeSY',response.data[0].schoolyear_id);
		console.log("eto yun: "+localStorage.getItem('activeSY'));
	}).catch(function(response){
		console.log(response);
	});

	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.removeClass('hs-side-selected');  
	var myEl = angular.element( document.querySelector( '#downloables' ) );
	myEl.removeClass('hs-side-selected');   
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.addClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.removeClass('hs-side-selected');


	getLink = '/restAPI/api/Homestead/Admins/view-requests.php?status=pending';
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = 1;
		}else{
			$scope.requests = response.data;
			$scope.length = $scope.requests.length;
			console.log($scope.requests);
			$scope.sd = 1;
		}
		console.log($scope.requests);
	}).catch(function(response){
		console.log(response);
	});


	$scope.page = 1;

	  
	  $scope.pageChanged = function() {
		var startPos = ($scope.page - 1) * 3;
		//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
		console.log($scope.page);
	  };
  

	$scope.showMessage = function(msg) {
	
		$scope.putangina = msg;
	}

	$scope.processRequest = function($reqID, $action){
		
		sendData = JSON.stringify({"request_id" : $reqID, "action" : $action});
		link = '/restAPI/api/Homestead/Admins/process-admin-request.php';
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				if($action=="GRANT"){
					swal("Request accepted.", "", "success");
				}else if($action=="DENY"){
					swal("Request denied.", "", "success");
				}else if ($action == "DELETE"){
					swal("Request deleted.", "", "success");
				}
				$route.reload();
			}
		}).catch(function(response){
			console.log(response);
		});
	};

	$scope.getListRequest = function() {
		getLink = '/restAPI/api/Homestead/Admins/view-requests.php?status=granted';
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.error2 = 1;
			}else{
				$scope.requests = response.data;
				$scope.length = $scope.requests.length;
				$scope.sd2 = 1;
			}
		console.log($scope.requests);	
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.getListRequest2 = function() {
		getLink = '/restAPI/api/Homestead/Admins/view-requests.php?status=denied';
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.error3 = 1;
			}else{
				$scope.requests = response.data;
				$scope.length = $scope.requests.length;
				$scope.sd3 = 1;
			}
		console.log($scope.requests);	
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.clear = function(){
		$location.path('/home');
	}

});

homestead.controller('courseCtrl', function($scope, $http, $route,myService){
    myService.checkMeBaby();
    
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#downloadables' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.addClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.removeClass('hs-side-selected');

	getLink = "/restAPI/api/homestead/courses/view-courses.php";
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error1 = 1;
		}else{
			$scope.courses = response.data;
		}
		
	}).catch(function(response){
		console.log(response); 
	});

	
	$scope.page = 1;  
	$scope.pageChanged = function() {
		var startPos = ($scope.page - 1) * 3;
		//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
		console.log($scope.page);
	};
  

	$scope.addCourse = function(){

		if($scope.courseName){
			$scope.words = $scope.courseName.split(' ');
			$scope.prefix = "";
			angular.forEach($scope.words, function(value, key) {
				$scope.prefix += value.substr(0, 1);
			});

			sendData = JSON.stringify({"course_name" : $scope.courseName, "course_prefix" : $scope.prefix});
			link = "/restAPI/api/homestead/courses/add-course.php";
			$http.post(link, sendData).then(function(response){
				console.log(response.data.error);
				if(response.data.success){
					swal("Course Added!", "Course name : " + $scope.courseName, "success");
					$('#newCourse-modal').modal('show').modal('hide');
					$route.reload();
				}else if(response.data.error == undefined){
					$scope.error = $scope.courseName+" already exists.";
				}
				console.log(response.data);
			}).catch(function(response){
				console.log(response);
			});
		}else{
			$scope.error = "All fields are required.";
		}

		
	}

	$scope.getCourseName = function(course_id){
		getLink = "/restAPI/api/homestead/courses/get-course.php?courseID="+course_id;
		$http.get(getLink).then(function(response){
			$scope.upCName = response.data[0].course;
			$scope.courseID = course_id;
			console.log($scope.upCName);
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.updateCourse = function(course_id){
		if($scope.upCName){
			$scope.words = $scope.upCName.split(' ');
			$scope.prefix = "";
			angular.forEach($scope.words, function(value, key) {
				$scope.prefix += value.substr(0, 1);
			});

			sendData = JSON.stringify({"courseID" : $scope.courseID, "course_name" : $scope.upCName,  "course_prefix" : $scope.prefix});
			link = "/restAPI/api/homestead/courses/edit-course.php";
			$http.post(link, sendData).then(function(response){
				console.log(response.data);
				if(response.data.success){
					swal("Course Updated!", "New course name : " + $scope.upCName, "success");
					$('#updateCourse-modal').modal('show').modal('hide');
					$route.reload();
				}
			}).catch(function(response){
				console.log(response);
			});
		}else{
			$scope.error = "All fields are required.";
		}
		
	}

});

homestead.controller('sectionsCtrl', function($scope, $http, $routeParams, $route,myService){
	myService.checkMeBaby();

	$scope.prefix = $routeParams.prefIX;
	$scope.courseID = $routeParams.courseID;
	getLink = "/restAPI/api/homestead/sections/view-sections-by-course.php?course_id="+$routeParams.courseID+"&&syrid="+localStorage.getItem('activeSY');
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error1 = 1;
		}else{
			$scope.Sections = response.data;
			console.log(response.data);
			$scope.secFilter =  1;
			$scope.secFilter3 =  false;
		}
	}).catch(function(response){
		console.log(response);
	});

	getLink = "/restAPI/api/homestead/courses/get-course.php?courseID="+$routeParams.courseID;
	$http.get(getLink).then(function(response){
		$scope.course = response.data[0].course;
	}).catch(function(response){
		console.log(response);
	});

	$scope.ordinalSuffix = function(i) {
		var j = i % 10,
			k = i % 100;
		if (j == 1 && k != 11) {
			return i + "st";
		}
		if (j == 2 && k != 12) {
			return i + "nd";
		}
		if (j == 3 && k != 13) {
			return i + "rd";
		}
		return i + "th";
	}

	$scope.filterSectionView = function(yrlevel){
		if(yrlevel == ""){
			getLink = "/restAPI/api/homestead/sections/view-sections-by-course.php?course_id="+$routeParams.courseID+"&&syrid="+localStorage.getItem('activeSY');
		}else{
			getLink = "/restAPI/api/homestead/sections/view-sections-by-year-level.php?course_id="+$routeParams.courseID+"&&year_level="+yrlevel+"&&syrid="+localStorage.getItem('activeSY');
		}

		$scope.ordinal = $scope.ordinalSuffix(yrlevel);
		
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.msg = "No section";
				$scope.Sections = null;
				$scope.secFilter3 =  tr;
			}else{
				$scope.Sections = response.data;
				$scope.msg = null;
			}
		}).catch(function(response){
			console.log(response);
		});
	};

	$scope.passSecData = function (secID,courseID, secName){
		$scope.id=secID;
		$scope.courseID = courseID;
		$scope.sectionName = secName;

		getLink= '/restAPI/api/Homestead/sections/get-single-section.php?secID='+ $scope.id;
		$http.get(getLink).then(function(response){
			console.log(response.data);
		//	$scope.sectionName = response.data.section;
			//$scope.level = response.data.year_level;
			if(response.data.year_level == 1){
				$scope.year_level = "1";
			}else if(response.data.year_level == 2){
				$scope.year_level = "2";
			}else if(response.data.year_level == 3){
				$scope.year_level = "3";
			}else{
				$scope.year_level = "4";
			}
		});
	}

	$scope.updateSection = function(secID){

		if($scope.section_name==undefined){
			$scope.section_name = "";
		}

		sendData = JSON.stringify({"section_name" : $scope.section_name, "year_level" : $scope.year_level, "section_id" : secID, "courseID" : $scope.courseID });
		link = "/restAPI/api/homestead/sections/edit-section.php";
		$http.post(link, sendData).then(function(response){
			console.log(response.data.exists);
			if(response.data.exists){
				$scope.error = "You already have " + $scope.sectionName + " in your database."; 
	
			}else if(response.data.success){
				swal("Section Updated!", "New section name : " + $scope.sectionName, "success");
				$('editWhat-modal').modal('show').modal('hide');
				$route.reload();
			}else if(response.data.message){
				//console.log(response.data.data[0].message + "Aasdasd");
				$scope.error = "All fields are required.";
	
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.page = 1;

	  
	$scope.pageChanged = function() {
	  var startPos = ($scope.page - 1) * 3;
	  //$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	  console.log($scope.page);
	};

	$scope.getVacant = function(section_id){
		$scope.sectionID = section_id;
		getLink = "/restAPI/api/homestead/admins/view-vacant-admins.php?section_id="+section_id+"&&syrid="+localStorage.getItem('activeSY');
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.msg = "No vacant professor";
				$scope.profs = null;
			}else{
				$scope.profs = response.data;
				$scope.adminIDS = [];
				$scope.adminIDS  = $scope.profs.map(function(value) {
					return value.admin_id;
				  });
			
				$scope.msg = null;
			}
		}).catch(function(response){
			console.log(response);
		}); 
	}

	$scope.testValue = "";
	$scope.id = [];

	$scope.imChanged = function(admID){
		if($scope.id.indexOf(admID) !== -1) {
			$scope.id.pop(admID);
		}else{
			$scope.id.push(admID);
		}
		$scope.testValue = $scope.id.join(',');	
		console.log("eto current"+$scope.testValue);															
	}

	$scope.assignProf = function(){
		if($scope.testValue === ""){
			$scope.assignError = "Select a professor to assign first.";
		}else{
			sendData = JSON.stringify({"admin_ids" : $scope.testValue, "section_id" : $scope.sectionID, "schoolyear_id" : localStorage.getItem('activeSY')});
			link = "/restAPI/api/homestead/admins/assign-admin-to-section.php";
			$http.post(link, sendData).then(function(response){
				console.log(response.data);
				if(response.data.message){
					swal("Prof successfully assigned.", "", "success");
					$('#assignProf-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
				}
			}).catch(function(response){
				console.log(response);
			});
		}
		
	}


	$scope.addSection = function(){
		if($scope.sectionName && $scope.filter){

			if($scope.sectionName ==  undefined){
				$scope.secError = "All fields are required.";
			}

			sendData = JSON.stringify({"courseID" : $routeParams.courseID, "section_name" : $scope.sectionName, "year_level" : $scope.filter});
			link = "/restAPI/api/homestead/sections/add-section.php";
			$http.post(link, sendData).then(function(response){
			
				if(response.data.success){
					swal("Section Added!", "Section name : " + $scope.sectionName, "success");
					$('#newSection-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
				}
				console.log(response.data);

				if(response.data.message == "Existing na po"){
					$scope.secError = $scope.sectionName + " already exists.";
				}
			}).catch(function(response){
				console.log(response);
			});
		}else{
			$scope.error = "All fields are required";
		}
	}
});

homestead.controller('assignedCtrl', function($scope, $http, $routeParams, $route,myService){
    myService.checkMeBaby();
	$scope.sectionID = $routeParams.sectionID;
	$scope.courseID = $routeParams.courseID;
	$scope.prefix = $routeParams.prefix;
	getLink = "/restAPI/api/homestead/admins/view-assigned-admins.php?section_id="+$scope.sectionID+"&&syrid="+localStorage.getItem('activeSY');
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg = "No assigned professor";
			$scope.profs = null;
		}else{
			$scope.profs = response.data;
			console.log($scope.profs);
			$scope.msg = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	getLink = "/restAPI/api/homestead/sections/get-section.php?section_id="+$scope.sectionID;
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg = "No RESULT";
			$scope.profs = null;
		}else{
			$scope.section = response.data[0].section;
			$scope.msg = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.setID = function(hID){
		$scope.hID = hID;
	}

	$scope.unAssign = function(){
		getLink = "/restAPI/api/homestead/admins/unassign.php?hID="+$scope.hID;
		$http.get(getLink).then(function(response){
			if(response.data.success){
				$('#unassignProf-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
				swal("Unassigned!", "", "success");
				
			}
		}).catch(function(response){
			console.log(response);
		});
	}


});



homestead.controller('syCtrl', function ($scope, $http,$location, $route,myService){
    myService.checkMeBaby();
    
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#downloadables' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.addClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.removeClass('hs-side-selected');
	

	getLink = "/restAPI/api/homestead/schoolyears/get-all-sy.php";
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg1 = "No active schoolyear";
			$scope.error = 1;
			console.log($scope.msg1);
			$scope.sys = null;
		}else{
			$scope.sys = response.data;
			console.log($scope.sys);
			$scope.msg1 = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.page = 1;

	  
	$scope.pageChanged = function() {
	  var startPos = ($scope.page - 1) * 3;
	  //$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	  console.log($scope.page);
	};
	
	getLink = "/restAPI/api/homestead/schoolyears/get-active-sy.php";
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg = "No active schoolyear";
			console.log($scope.msg);
			$scope.profs = null;
		}else{
			$scope.sy = response.data;
			if($scope.sy[0].semester == "2") {
				$scope.sy[0].semester = "2nd Semester of";
			}else if ($scope.sy[0].semester == "1") {
				$scope.sy[0].semester = "1st Semester of";
			}else{
				$scope.sy[0].semester = "Midyear of"
			}
			console.log($scope.sy);
			$scope.msg = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.getSYID = function (schoolyear_id){
		getLink= '/restAPI/api/Homestead/schoolyears/get-single-schoolyear.php?schID='+ schoolyear_id;
		$http.get(getLink).then(function(response){
			console.log(response.data);
			$scope.start = response.data.start;
			$scope.end = response.data.end;
			$scope.schoolyear_id = response.data.schoolyear_id;
		});
	}
	$scope.updateSY = function(start, end, schoolyear_id){

		if($scope.start === undefined){
			$scope.syError = "All fields are required.";
			
		}else{
			$scope.start = start;
			$scope.end = end;
			$scope.year = schoolyear_id;
			
			$scope.updatedSY = $scope.start + "-" + $scope.end;
			console.log($scope.updatedSY);
			sendData = JSON.stringify({"schID" : $scope.year , "schoolYear" : $scope.updatedSY });
			 link = '/restAPI/api/Homestead/schoolyears/update-school-year.php';
			$http.post(link,sendData).then(function(response){
			 if(response.data.success){
				 swal("Good Job!", response.data.success, "success");
				$('#editSY-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
			 }else{
				 $scope.error = response.data;
			 }
			}).catch(function(response){
				console.log(response);
			});
		}	
	};

	$scope.addSY = function(){
		if($scope.start === undefined){
			$scope.syError = "All fields are required.";
			//swal("All fields are required.","", "error");
		}else{
			$scope.newSY = $scope.start + "-" + $scope.end;
			sendData = JSON.stringify({"schoolyear" : $scope.newSY});
			link = "/restAPI/api/homestead/SchoolYears/add-school-year.php";
			$http.post(link, sendData).then(function(response){
				console.log( $scope.start + "-" + $scope.end);
				if(response.data.success){
					swal("School year added!", response.data.success, "success");
					$('newSY-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
				}else if (respose.data.message){
					console.log(response.data.message);
					// $scope.error = "School Year already Added";
				}
			}).catch(function(response){
				console.log(response.data);
				swal("This school year's already in the database. You can activate it in school years section.","", "warning");
					$('newSY-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			});
		}
	}

	$scope.passSYData = function(schoolyear, semester, id){
		$scope.schoolyear = schoolyear;
		$scope.semester = semester;
		$scope.schoolyear_id = id;
	}

	$scope.activateSY = function(schoolyear_id){
		getLink = "/restAPI/api/homestead/schoolyears/set-school-year.php?yrid="+$scope.schoolyear_id;
		$http.get(getLink).then(function(response){
			localStorage.setItem('activeSY',schoolyear_id);
			swal("School year activated.", "", "success");
			$('activate-modal').modal('show').modal('hide');
			$route.reload();

		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.deactivateSY = function(schoolyear_id){
		getLink = "/restAPI/api/homestead/schoolyears/deactivate-school-year.php?yrid="+$scope.schoolyear_id;
		$http.get(getLink).then(function(response){
			swal("School year deactivated.", "", "success");
			$('deactivate-modal').modal('show').modal('hide');
			$route.reload();

		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.addEnd = function(){
		$scope.end = $scope.start*1 + 1;
	}
	$scope.addEndUpdate = function(){
		$scope.end = $scope.start*1 + 1;
	}
});

homestead.controller('studentCtrl', function($scope, $http, $location, $routeParams, $route,myService){
    myService.checkMeBaby();
	$scope.prefix = $routeParams.prefix;
	getLink = "/restAPI/api/homestead/Students/view-students.php?section_id="+$routeParams.section_id+"&&syrid="+localStorage.getItem('activeSY');
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg1 = "No students";
			console.log($scope.msg1);
			$scope.studs = null;
		}else{
			$scope.studs = response.data;
			$scope.msg1 = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	getLink = "/restAPI/api/homestead/sections/get-section.php?section_id="+$routeParams.section_id;
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.msg = "No RESULT";
			console.log($scope.msg);
			$scope.profs = null;
		}else{
			$scope.section = response.data[0].section;
			$scope.msg = null;
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.addStudent = function() {
        if($scope.mname == undefined){
            $scope.mname = "";
        }
		sendData = JSON.stringify({	"student_id" : $scope.studID, 
									"section_id" : $routeParams.section_id,
									"course_id" : $routeParams.courseID,
									"fname" : $scope.fname,
									"mname" : $scope.mname,
									"lname" : $scope.lname,
									"schoolyear_id" : localStorage.getItem('activeSY')});
		link = "/restAPI/api/homestead/students/upload_student.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				console.log('Success');
                swal("Yey",response.data.success,"success");
				$('#newStudent-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
			}else{
				swal("Ooops",response.data.error,"error");
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('students',$scope.files[0]);
		fd.append('courseID',$routeParams.courseID);
		fd.append('section_id',$routeParams.section_id);
		fd.append('schoolyear_id',localStorage.getItem('activeSY'));

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			if(response.data.success){
				$scope.success = response.data.success;
				$scope.error = false;
				$scope.trouble = false;
            }else if(response.data.error){
            	$scope.error = response.data.error;
            }else{
            	$scope.trouble = response.data;
            }
		}).catch(function(response){
			console.log(response.data);
		});

	}


	$scope.clearResponse = function(){
		$scope.csverror = null;
		$scope.csvsuccess = null;
	}

	$scope.getStudentData = function(student_id) {
		getLink = "/restAPI/api/homestead/courses/view-courses.php";
		$http.get(getLink).then(function(response){
			$scope.courses = response.data;
			console.log($scope.courses);
		}).catch(function(response){
			console.log(response);
		});

		getLink = "/restAPI/api/homestead/sections/view-sections-by-course.php?course_id="+$routeParams.courseID+"&&syrid="+localStorage.getItem('activeSY');
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.error1 = 1;
			}else{
				$scope.Sections = response.data;
				$scope.courseID = $routeParams.courseID;
				console.log(response.data);
			}
			console.log(response.data);
		}).catch(function(response){
			console.log(response);
		});


		getLink = "/restAPI/api/homestead/students/get-single-student.php?student_id="+student_id;
		$http.get(getLink).then(function(response){
			if(response.data.error){
				$scope.msg2 = "No student found!!!";
				$scope.stud = null;
			}else{
				$scope.stud = response.data;
				$scope.fname = $scope.stud[0].fname;
				$scope.mname = $scope.stud[0].mname;
				$scope.lname = $scope.stud[0].lname;
				$scope.studID = $scope.stud[0].student_id;
				$scope.oldID =  $scope.stud[0].student_id;
				$scope.courseID = $scope.stud[0].courseID;
				$scope.sectionID = $scope.stud[0].sec_id;
				console.log($scope.stud);
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.refreshSec = function(courseID) {
		getLink = "/restAPI/api/homestead/sections/view-sections-by-course.php?course_id="+courseID+"&&syrid="+localStorage.getItem('activeSY');
		$http.get(getLink).then(function(response){
			$scope.Sections = response.data;
			console.log(response.data);
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.updateStudent = function() {
		sendData = JSON.stringify({	"new_id" : $scope.studID,
									"student_id" : $scope.oldID,
									"fname" : $scope.fname,
									"mname" : $scope.mname,
									"lname" : $scope.lname,
									"section_id" : $scope.sectionID});
		link = "/restAPI/api/homestead/students/edit-student.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				swal("Student successfully updated!", "Nice one", "success");
				$('#updateStudent-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
			}else{
				console.log(response.data);
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.page = 1;

	  
	$scope.pageChanged = function() {
	  var startPos = ($scope.page - 1) * 3;
	  //$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	  console.log($scope.page);
	};

});

homestead.controller('empCtrl', function($scope, $http, $routeParams, $route, $location,myService) {
    myService.checkMeBaby();
	$scope.currentPage = 1;
	$scope.pageSize = 4;

	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#downloadables' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.addClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.removeClass('hs-side-selected');

	getLink = "/restAPI/api/homestead/admins/view-registered-admins.php";
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = 1;
		}else{
			$scope.admins = response.data;
			console.log($scope.admins);
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.getEmpID = function (employee_id) {
			$scope.getHandledCourses(employee_id);
			getLink = "/restAPI/api/homestead/admins/get-single-employee.php?empID=" + employee_id;
			$http.get(getLink).then(function (response) {
						console.log(response.data);
						$scope.emps = response.data;
						$scope.empID = response.data.handle[0].emp_id;
						$scope.empName = response.data.handle[0].emp_name;
						$scope.section = response.data.handle[0].section;
						$scope.sec_id = response.data.handle[0].sec_id;
			});

			
	}

	$scope.page = 1;

	  
	$scope.pageChanged = function() {
	  var startPos = ($scope.page - 1) * 3;
	  //$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	  console.log($scope.page);
	};

	$scope.getInstrData = function(employee_id) {
		getLink = "/restAPI/api/homestead/admins/get-emp-details.php?empID=" + employee_id;
		$http.get(getLink).then(function (response) {
			$scope.empID = response.data.data[0].empID;
			$scope.fname = response.data.data[0].fname;
			$scope.mname = response.data.data[0].mname;
			$scope.lname = response.data.data[0].lname;
		});
	}


	$scope.editAdmin = function(oldID){
        if($scope.mname == undefined){
            $scope.mname = "";
        }
		sendData = JSON.stringify({ "oldID" : oldID,
									"empID" : $scope.empID,
									"fname" : $scope.fname,
									"mname" : $scope.mname,
									"lname" : $scope.lname});
		link = "/restAPI/api/homestead/admins/edit-admin.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				swal("Instructor successfully updated!", "", "success");
				$('#editInstr-modal').modal('show').modal('hide');
				$route.reload();

			}else{
				console.log(response.data);
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.getHandledCourses = function(empID){
		getLink = "/restAPI/api/homestead/admins/get-handled-courses.php?empID=" + empID;
		$http.get(getLink).then(function (response) {
			$scope.coursesHandled = response.data;
			console.log($scope.coursesHandled);
		});
	}


	// $scope.asdasd = function(){
	// 	   $scope.uploadCSV = function(){
   	// 	var fd = new FormData();
	// 	fd.append('admins',$scope.files[0]);

	// 	$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
	// 		transfromRequest:angular.identity,
	// 		headers:{'Content-Type':undefined}
	// 	}).then(function(response){
	// 			console.log(response);
	// 		}).catch(function(response){
	// 			console.log(response.data);
	// 		});
	//    };
	// }

	$scope.addAdmin = function(){
        if($scope.mname == undefined){
            $scope.mname = "";
        }
		sendData = JSON.stringify({	"empID" : $scope.empID,
									"fname" : $scope.fname,
									"mname" : $scope.mname,
									"lname" : $scope.lname});
		link = "/restAPI/api/homestead/admins/add-admin.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
                swal("Good Job",response.data.success,"success");
				$('#employeeNew-modal').modal('show').modal('hide');
				$route.reload();
				//$location.path('/viewAdmins');
			}else{
				console.log(response.data);
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('admins',$scope.files[0]);
		

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
            if(response.data.success){

				$scope.success = response.data.success;
				$scope.error = false;
				$scope.trouble = false;
            }else if(response.data.error){
            	$scope.error = response.data.error;
            }else{
            	$scope.trouble = response.data;
            }
		}).catch(function(response){
			console.log(response.data);
		});
	};

	$scope.clearResponse = function(){
		$scope.csverror = null;
		$scope.csvsuccess = null;
	}

		
		});

homestead.controller('logoutCtrlr',function($location,$window,$localStorage,myService){
	window.localStorage.removeItem('sa_id');
	  $localStorage.loggedIn2 = false;
	  myService.checkMeBaby();
   $location.path("/login");
});

homestead.controller('downloadCtrl', function($location,myService){
    myService.checkMeBaby();
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#downloadables' ) );
	myEl.addClass('hs-side-selected');
});


homestead.controller('changePW', function($scope, $http, $route,myService) {
    myService.checkMeBaby();
	var myEl = angular.element( document.querySelector( '#requests' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#COURSES' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#admins' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#calendar' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#downloadables' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#cog' ) );
	myEl.addClass('hs-side-selected');

	$scope.saveCred = function(){
		sendData = JSON.stringify({"password" : $scope.password, "newpw" : $scope.newpw, "confpw" : $scope.confpw});
		$link = '/restAPI/api/Homestead/update-unpw.php';
		$http.post($link, sendData).then(function(response){
			console.log(response.data);
			if(response.data.success){
				swal("Password updated!", "", "success");
				$route.reload();
			}else if(response.data.dontmatch){
				swal("Passwords dont match!", "", "error");
				$route.reload();
			}else if (response.data.wrongpw){
				swal("Wrong password!", "", "error");
			}
		}).catch(function(response){
			console.log(response.data);
		});
	}
});