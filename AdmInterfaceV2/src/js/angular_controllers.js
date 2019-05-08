var theApp = angular.module('theApp',['ngRoute','ngStorage','ui.bootstrap','checklist-model','btford.socket-io']);

theApp.directive("limitTo", [function() {
    return {
        restrict: "A",
        link: function(scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);
            angular.element(elem).on("keypress", function(e) {
                if (this.value.length == limit) e.preventDefault();
            });
        }
    }
}]);

theApp.factory('myService', function($rootScope,$http) {
        return {
            checkMeBaby: function() {
                $rootScope.fucker = localStorage.getItem('user_id');
                
                getLink = "/restAPI/api/quizzes/user.php?admin_id="+ localStorage.getItem('user_id');
                $http.get(getLink).then(function(response){
                $rootScope.user = response.data;
                console.log(response.data.user);
       });
            },
            nullMeBaby : function(){
            	$rootScope.fucker = null;
            }
        };
});

theApp.factory('mySocket', function(socketFactory){
	return socketFactory({
		ioSocket: io('http://192.168.1.90:4000',{transports: ['websocket'], upgrade: false}) //iiba ibahin pre based sa IP mo.
	});
});

theApp.factory('streamQuestion', function($rootScope , $location) {
    return {
        testType: function(partType) {
            if(partType == 1){
            	$location.path('/streamMultiple');
            }else if(partType == 2){
            	$location.path('/streamTOF');
            }else if(partType == 3){
            	$location.path('/streamArrange');
            }else if(partType == 4){
            	$location.path('/streamGuess');
            }
        }
    };
});

theApp.run(function($rootScope , mySocket , $location){
	$rootScope.fucker = null;
	
	if(localStorage.getItem('active_room')){		
			mySocket.emit('reloadAdmin' , localStorage.getItem('active_room') );
			localStorage.removeItem('active_room');
			$location.path("/home");
	}
});

//INPUT VAIDATION (NUMBERS / LETTERS ONY)
theApp.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
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

theApp.directive('fileInput',function($parse){
	return{
		restrict:'A',
		link:function(scope,element,attribute){
			element.bind('change',function(){
				$parse(attribute.fileInput)
				.assign(scope,element[0].files)
				scope.$apply();
				document.getElementsByClassName("custom-file-label")[0].innerHTML = element[0].files[0].name;
				document.getElementById("filezzz").innerHTML = element[0].files[0].name;
			});
		}
	}
});

theApp.config(['$routeProvider', function($routeProvider) {

	$routeProvider

		.when('/discussion' , {
			templateUrl: 'discuss.html'
		})


		.when('/showQAgain' , {
			
			templateUrl: 'answer-now.html'
		})
		
		.when('/hostQuizz' , {
			templateUrl: 'waiting-page.html',
     		controller: 'waitingCtrlr'
		})

		.when('/streamPart' , {
			
			templateUrl: 'project-part-info.html',
			controller: 'streamPartCtrlr'
		})

		.when('/questionStats' , {
			
			templateUrl: 'question-stats.html',
			controller: 'questionStatsCtrlr'
		})

		.when('/leaderBoard' , {
			
			templateUrl: 'podium.html',
			controller: 'leaderBoardCtrlr'
		})

		.when('/quizzesTaken', {
			templateUrl: 'quizzes-taken.html',
			controller: 'meowController'
		})

		.when('/viewStudentAns', {
			templateUrl: 'view-students-answer.html',
			controller: 'meowController1'
		})

		.when('/answerDetails', {
			templateUrl: 'student-answers-details.html',
			controller: 'meowController2'
		})

		.when('/gameMode', {
			templateUrl:'mode-and-game-settings.html',
			controller: 'gameModes'
		})

		.when('/streamMultiple' , {
				
				templateUrl: 'question-multiple.html',
				controller: 'streamQuestionCtrlr'
		})

		.when('/streamTOF' , {
				resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('active_room')){
									 $location.path("/home");
							 }
					  }
			 	},
				templateUrl: 'question-tof.html',
				controller: 'streamQuestionCtrlr'
		})

		.when('/streamArrange' , {
				
				templateUrl: 'question-arrange.html',
				controller: 'streamQuestionCtrlr'
		})

		.when('/streamGuess' , {
				
				templateUrl: 'question-guess.html',
				controller: 'streamQuestionCtrlr'
		})

		.when('/quizSummary' , {
				templateUrl: 'quiz-final-summary.html',
				controller: 'leaderBoardCtrlr'
		})

		.when('/home' , {
			resolve:{
 					"check": function($location,$localStorage){
 							if ($localStorage.loggedIn){
 									$location.path("/myquizzen");
 							}
 					}
 			},
			templateUrl: 'login.html',
     		controller: 'logInCtrlr'
		})

		.when('/viewSection/:section_id' , {

			templateUrl: 'list-section-students.html',
     		controller: 'viewSectionsCtrlr'
		})
        
        .when('/pending/:empID', {
			resolve:{
				"check": function($location,$localStorage){
						if ($localStorage.loggedIn){
								$location.path("/myquizzen");
						}
				 }
			},
			templateUrl: 'pending.html',
			controller: 'pendingCtrl'
		})


        .when('/wait', {
			resolve:{
				"check": function($location,$localStorage){
						if ($localStorage.loggedIn){
								$location.path("/myquizzen");
						}
				 }
			},
			templateUrl: 'wait-admin.html',
		})


        .when('/myquizzen' , {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
			templateUrl: 'howm.html',
            controller: 'viewQuizzesCtrlr'
		})
        .when('/signup/:mnemonic', {
					resolve:{
							 "check": function($location,$localStorage){
									 if ($localStorage.loggedIn){
											 $location.path("/myquizzen");
									 }
							  }
					 },
            templateUrl: 'signup.html',
            controller: 'registerCtrlr'
        })

        .when('/createquiz',{
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
        	templateUrl:'new-quizzen.html',
        	controller: 'viewQuizzesCtrlr'
        })

        .when('/editquiz/:quiz_id', {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
        	templateUrl:'edit-quizzen.html',
        	controller: 'updateQuizCtrlr'
		})

		.when('/sectionslist', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
			templateUrl:'list-sections.html',
			controller: 'listSecCtrlr'
		})

		.when('/viewsections2/:course_id/:admin_id/:course/:prefix', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
			templateUrl:'view-sections.html',
			controller: 'viewSectionCtrlr'
		})

		.when('/viewstudents/:section_id/:sy_id/:section/:prefix', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
			templateUrl:'view-students.html',
			controller: 'viewStudentsCtrlr'
		})

		.when('/viewstudent', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
			templateUrl:'view-student-profile.html'
		//	controller: 'listSecCtrlr'
		})

		.when('/bin', {
			templateUrl: 'bin.html',
			controller: 'trashbinCtrlr'
		})

		.when('/addPart/:quiz_id', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
        	templateUrl:'add-part.html',
        	controller: 'quizParts'
		})
        
        .when('/published/:quiz_id/Segment', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
				templateUrl:'published-segments2.html',
				controller: 'readOnlyCtrlr'
		})
    
        .when('/published/:quiz_id/Freeflow', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
				templateUrl:'published-freeflow.html',
				controller: 'readOnlyCtrlr'
		})
    
		.when('/viewparts/:quiz_id/Segment', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
				templateUrl:'view-parts.html',
				controller: 'partsCtrlr'
		})
        
        .when('/viewparts/:quiz_id/Freeflow', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
				templateUrl:'view-freeflow.html',
				controller: 'partsCtrlr'
		})

		.when('/updatePart/:part_id/:totalqs', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
				templateUrl:'update-part.html',
				controller: 'updatePart'
		})

        .when('/checker/1/:quiz_id/:part_id', {

                    templateUrl:'multiple_choice.html',
                    controller: 'multipleCtrlr'

        })

        .when('/checker/2/:quiz_id/:part_id', {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
								}
					 },
                templateUrl:'true-or-false.html',
                controller: 'addCtrlr'
		})
		

        .when('/addstudent', {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
								}
					 },
                templateUrl:'add-student.html',
                controller: 'addStudentCtrlr'
		})


        .when('/updatestudent/:student_number', {

                templateUrl:'update-student.html',
                controller: 'updateStudentCtrlr'
        })

        .when('/checker/4/:quiz_id/:part_id', {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
            templateUrl:'guess_word.html',
            controller: 'guessCtrlr'
		})
		
		.when('/checker/3/:quiz_id/:part_id', {
					resolve:{
							 "check": function($location,$localStorage){
									 if (!$localStorage.loggedIn){
											 $location.path("/login");
									 }
							  }
					 },
            templateUrl:'arrange_the_sequence.html',
            controller: 'arrangeCtrlr'
		})

		.when('/empCheck', {
			resolve:{
					 "check": function($location,$localStorage){
							 if ($localStorage.loggedIn){
									 $location.path("/myquizzen");
							 }
					  }
			 },
			templateUrl: 'employee-check.html',
			controller: 'empIDChecker'
		})

		.when('/adminRequest', {
			resolve:{
				"check": function($location,$localStorage){
						if ($localStorage.loggedIn){
								$location.path("/myquizzen");
						}
				 }
			},
			templateUrl: 'employee-request.html',
			controller: 'empRequest'
		})

		.when('/viewByTag/:tag_name/:tag_id', {
		
			templateUrl: 'view-by-tag.html',
			controller: 'viewTagCtrl'

		})
		
		.when('/showWhere/:quiz_id', {
			templateUrl: 'showUp.html',
			controller: 'showUp'
	 	})
        
        .when('/sharedQuizzes', {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedIn){
									 $location.path("/login");
							 }
						}
			 },
			templateUrl:'shared-quizzes.html',
			controller: 'sharedQuizCtrlr'
		})
    
    	.when('/quizTaken/:section_id' , {
			templateUrl: 'quizzes-taken.html',
			controller: 'quizTakenCtrlr'
		})

		.when('/quizSummaryReport/:quiz_id/:section_id/:room_id' , {
			templateUrl: 'quiz-summary.html',
			controller: 'quizSummaryCtrlr'
		})

		.when('/userAnswersReport/:user_id/:room_id/:quiz_id' , {
			templateUrl: 'quiz-user-answers.html',
			controller: 'userAnswersCtrlr'
		})


		.when('/quizPassersReport/:quiz_id/:section_id/:room_id' , {
			templateUrl: 'quiz-passers.html',
			controller: 'quizPassersCtrlr'
		})

		.when('/quizFailuresReport/:quiz_id/:section_id/:room_id' , {
			templateUrl: 'quiz-failures.html',
			controller: 'quizFailuresCtrlr'
		})
        
        .when('/accountSettings', {
			templateUrl:'account-settings.html',
			controller:'accSettingsCtrl'
		})

		.when('/updateCred', {
			templateUrl:'un-pw.html',
			controller:'accSettingsCtrl'
		})

		.when('/logout', {
						resolve:{
								 "check": function($location,$localStorage){
										 if (!$localStorage.loggedIn){
												 $location.path("/login");
										 }
								 }
						 },
							templateUrl: 'login.html',
							controller: 'logoutCtrlr'
				 }).otherwise({
				redirectTo: '/home'
			})
	}]);

	theApp.controller('logoutCtrlr',function($location,$window,$localStorage,myService){
	     window.localStorage.removeItem('user_id');
	       $localStorage.loggedIn = false;
	       myService.checkMeBaby();
	    $location.path("/login");
	});

	theApp.controller('logInCtrlr', ['$scope','$http','$location', '$localStorage', '$rootScope','myService' , function($scope,$http,$location,$localStorage,$rootScope,myService){
$scope.fuckzz = function(){
    window.location = "http://localhost:7777/PLAY/student/#!/login";
}		 

$scope.inputType = 'password';
  $scope.showHideClass = 'fa fa-eye';

  $scope.showPassword = function(){
   if($scope.angPassword != null)
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
			$localStorage.loggedIn = false;

			sendData = JSON.stringify({"sent_username" : $scope.angUsername , "sent_password" : $scope.angPassword});
			link = "/restAPI/api/Hosts/login_hosts.php";

			$http.post(link,sendData).then(function(response){
				if(response.data.success){
					$localStorage.loggedIn = true;
					localStorage.setItem('user_id',response.data.session);
					myService.checkMeBaby();

					getLink = '/restAPI/api/homestead/schoolyears/get-active-sy.php';
					$http.get(getLink).then(function(response){
						localStorage.setItem('activeSY',response.data[0].schoolyear_id);
						console.log("eto yun: "+localStorage.getItem('activeSY'));
					}).catch(function(response){
						console.log(response);
					});

 					$location.path("/myquizzen");
				}else{
					$scope.error = response.data;
				}
			}).catch(function(response) {
			  	console.log(response);
			});

		};
	}]);

	theApp.controller('registerCtrlr', function($scope,$http,$location, $routeParams){
		$scope.info = $routeParams.mnemonic.split('*');
		$scope.fname = $scope.info[0];
		$scope.lname = $scope.info[1];
		$scope.empID = $scope.info[2];
		$scope.signUp = function(){
			sendData = JSON.stringify({"mirror_id" : $scope.empID, "password" : $scope.password1 ,  "username" : $scope.username ,  "confirm_pw" : $scope.password2});

			link = "/restAPI/api/Hosts/register_hosts.php";

			$http.post(link,sendData).then(function(response){
				if(response.data.success){
					swal("Good Job",response.data.success,"success");
				}else{
					$scope.error = response.data.message;
				}
			}).catch(function(response){
				console.log(response);
			});
		};

	});

theApp.controller('addSectionCtrlr',function($scope,$http,myService){
    myService.checkMeBaby();
	$scope.courseId= "1";
	//get courses
	getLink = '/restAPI/api/Hosts/list_courses.php';
	$http.get(getLink).then(function(response){
		$scope.reply = response.data.courses;
	}).catch(function(response){
		console.log(response);
	});


	//post data
	$scope.addSection = function(){

		sendData = JSON.stringify({"course" : $scope.courseId , "section" : $scope.section});
		link = '/restAPI/api/Hosts/Sections/add_section.php'
		$http.post(link,sendData).then(function(response){
			if(response.data.message =="Section successfully added."){
				swal("Good Job", "Section Successfully Added","success");
			}else{
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});

	}
});



theApp.controller('updateStudentCtrlr' , function($scope,$http,$routeParams,myService){
    myService.checkMeBaby();
	//eto yung para sa select element nilalagyan lang nila yon ng laman

	getLink = '/restAPI/api/Hosts/list_courses.php'
	$http.get(getLink).then(function(response){

		$scope.replySections = response.data.sections;
		$scope.replyCourses = response.data.courses;
		console.log(response.data.courses);
	}).catch(function(response){
		console.log(response);
	});

	getLink2 = '/restAPI/api/Hosts/manageStudent/read_single.php?stud_id='+$routeParams.student_number;
	$http.get(getLink2).then(function(response){
		console.log("eto");
		console.log(response.data);
		$scope.courseId = response.data[0].course_id;
		$scope.sectionId = response.data[0].section_id;
		$scope.currentStudId = response.data[0].student_id;
		$scope.fname =  response.data[0].fname;
		$scope.mname = response.data[0].mname;
		$scope.lname = response.data[0].lname;

	}).catch(function(response){
	});

	$scope.saveStud = function(){

		sendData = JSON.stringify({"currentStudId" : $scope.currentStudId, "courseId" : $scope.courseId, "sectionId" : $scope.sectionId, "fname" : $scope.fname , "mname" : $scope.mname , "lname" : $scope.lname , "student_id" : $routeParams.student_number });
		link = '/restAPI/api/Hosts/manageStudent/update.php';
		$http.post(link,sendData).then(function(response){
			if(response.data.success){
				swal("Good Job",response.data.success,"success");
			}else{
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response.data);
		});
	}


});

theApp.controller('listCtrlr', function($scope,$http,myService){
    myService.checkMeBaby();
	getLink = '/restAPI/api/Hosts/list_courses.php';

	$http.get(getLink).then(function(response){
		$scope.students = response.data.names.students;
	}).catch(function(response){
		console.log(response);
	});

});

theApp.controller('listSecCtrlr', function($scope, $http, $route, $location,myService){
    myService.checkMeBaby();
    var myEl = angular.element( document.querySelector( '#quizzes' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#sections' ) );
	myEl.addClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#report' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#share' ) );
	myEl.removeClass('hs-side-selected');
    var myEl = angular.element( document.querySelector( '#bin' ) );
	myEl.removeClass('hs-side-selected');
    
	$scope.page = 1;

		
	$scope.pageChanged = function() {
	var startPos = ($scope.page - 1) * 3;
	//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	console.log($scope.page);
	};
	$scope.userID = localStorage.getItem('user_id');

	getLink = '/restAPI/api/Hosts/get_handled_courses.php?admin_id='+ localStorage.getItem('user_id');
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = response.data;
		}else{
			$scope.handledCourses = response.data;
			console.log(response.data);
		}
	}).catch(function(response){
	
	  console.log("x");
	
	});
});


theApp.controller('viewStudentsCtrlr', function($http , $scope , $routeParams , $location,myService){
    myService.checkMeBaby();
	$scope.printOfficial = function(){
		window.open('../../../restAPI/api/Hosts/printOfficialList.php?id='+$routeParams.section_id+"&admin_id="+localStorage.getItem('user_id'));
	}

	$scope.getQuizTaken = function(){
		$location.path("/quizTaken/"+$routeParams.section_id);
	}

	$scope.section = $routeParams.section;
	$scope.prefix = $routeParams.prefix;
	$scope.page = 1;
	$scope.pageChanged = function() {
		var startPos = ($scope.page - 1) * 3;
		//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
		console.log($scope.page);
	};
	
	getLink = '/restAPI/api/Hosts/get_handled_students.php?section_id='+$routeParams.section_id + '&schoolyear_id='+$routeParams.sy_id;
	$http.get(getLink).then(function(response){
		if(response.data.error){
			$scope.error = response.data;
		}else{
			$scope.handledStudents = response.data;
		}
	}).catch(function(response){
	  console.log("x");
	});
});

theApp.controller('viewSectionCtrlr', function($http , $scope , $routeParams,myService){
    myService.checkMeBaby();
	$scope.course  = $routeParams.course;
	$scope.prefix = $routeParams.prefix;
	getLink = '/restAPI/api/Hosts/get_handled_sections.php?admin_id='+$routeParams.admin_id + '&course_id='+$routeParams.course_id;
	$http.get(getLink).then(function(response){
		if(response.data.message){

		}else{
			$scope.handledSections = response.data;
			console.log(response.data);
		}
	}).catch(function(response){
	
	  console.log("x");
	
	});

	$scope.page = 1;

		
	$scope.pageChanged = function() {
	var startPos = ($scope.page - 1) * 3;
	//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	console.log($scope.page);
	};

});

theApp.controller('viewSectionsCtrlr', function($scope,$http,$routeParams,myService){
    myService.checkMeBaby();
	$scope.duplicateStudents = [];

	$scope.currentPage = 1;
	$scope.pageSize = 10;

	getLink = "/restAPI/api/Hosts/Sections/list_section_students.php?section_id="+$routeParams.section_id;
	$http.get(getLink).then(function(response){

		if(response.data.message){
			$scope.noStudents = response.data;
		}else{
			console.log(response.data);
			$scope.students = response.data.data;
		}
	}).catch(function(response){
	  console.log(response);
	});



	$scope.getData = function (){
		//GET COURSES AND SECTIONS
		getLink = '/restAPI/api/Hosts/list_courses.php'
		$http.get(getLink).then(function(response){
			$scope.replySections = response.data.sections;
			$scope.replyCourses = response.data.courses;

		}).catch(function(response){
			console.log(response);
		});
	};

	//POST DATA
	$scope.addStudent = function(){
		postLink = "/restAPI/api/Hosts/manageStudent/upload_student.php";
		sendData = JSON.stringify({"student_id" : $scope.studid , "section_id" : $scope.sectionId , "course_id" : $scope.courseId  , "fname" : $scope.fname ,  "mname" : $scope.mname ,  "lname" : $scope.lname });

		$http.post(postLink,sendData).then(function(response){
			if(response.data.success){
				swal("Yey",response.data.success,"success");
			}else{
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('students',$scope.files[0]);

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			if(response.data.success){

			}else if(response.data.error){

			}else{
				$scope.errors = response.data;
				$scope.duplicate = response.data.duplicate;
				$scope.nonConvert = response.data.nonConvert;
				$scope.wrongLen = response.data.wrongLen;
				$scope.missingData = response.data.missingData;
				$scope.enter = response.data.enter;
				console.log(response.data);
				$scope.duplicateStudents = response.data;

			}
		}).catch(function(response){
			console.log(response.data);
		});

	}

});
theApp.controller('trashbinCtrlr', function ($scope,$route,$http,myService){
    myService.checkMeBaby();
     var myEl = angular.element( document.querySelector( '#quizzes' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#sections' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#report' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#share' ) );
	myEl.removeClass('hs-side-selected');
    var myEl = angular.element( document.querySelector( '#bin' ) );
	myEl.addClass('hs-side-selected');
    
     getLink = "/restAPI/api/quizzes/read_trash.php?admin_id="+ localStorage.getItem('user_id');
   $http.get(getLink).then(function(response){
       if(response.data.message){
		   $scope.error = response.data.message;
		   console.log($scope.error);
       }else{
			console.log(response.data);
			$scope.trashInfo = response.data;
       }
   });
    
    $scope.restoreQuiz = function(quizID){
        $scope.quizID = quizID;
    }
    
    $scope.finalRestore = function(quizID){
   getLink = "/restAPI/api/quizzes/untrash-quiz.php?quizID="+quizID;
		 $http.get(getLink).then(function(response){
			 if(response.data.message){
			 swal("Quiz", "Removed to Trash", "success");
             $('#restore-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
			 }else{
              swal("Ooops", "Something went wrong", "error");
             $('#restore-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
			 }
			 console.log(response.data);
		 });
    }
    
    $scope.finalDeleteQuiz = function(quizID){
		getLink = "/restAPI/api/quizzes/delete-quiz.php?quizID="+quizID;
		 $http.get(getLink).then(function(response){
			 if(response.data.success){
             swal("Quiz", "Successfully Deleted", "success");
             $('#delete-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
			 }else{
             swal("Ooops", "Something went wrong", "error");
             $('#delete-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
			 }
			 console.log(response.data);
		 });
	 }
})
theApp.controller('viewQuizzesCtrlr', function($scope, $http , $routeParams ,$location,$route, $window, myService){
    myService.checkMeBaby();
    
    $scope.quizoverlap = 0;

    getLink = "/restAPI/api/QUIZZES/count-deldays.php?adminID="+ localStorage.getItem('user_id');
	$http.get(getLink).then(function(response){
		if(response.data.success){
			console.log('na-delete');
		}else{
			console.log(response.data);
		}
	}).catch(function(response){
		console.log(response);
	});

	$scope.takerChecker = function(sectionID){
		
		localStorage.setItem('currentSection' , sectionID);
		
		getLink = "/restAPI/api/QUIZZES/quiz-taker-checker.php?secID="+ sectionID + "&&sy=" + localStorage.getItem('activeSY')+"&quiz_id="+localStorage.getItem('currentQuiz');
		$http.get(getLink).then(function(response){
			if(response.data.success){
				$scope.selectSection(sectionID);
			}else{
				$scope.quizoverlap = sectionID;
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.clearPreviousQuiz = function(section_id){
		sendData = JSON.stringify({"room_id" : localStorage.getItem('currentQuiz') + localStorage.getItem('currentSection') ,
									"section_id" : section_id });

	 	link = '/restAPI/api/Quizzes/overwrite-prev-rec.php';

		$http.post(link,sendData).then(function(response){
			$scope.selectSection(section_id);
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.clearOverlap = function(){
		$scope.quizoverlap = 0;
	}

	$scope.selectSection = function(selectedSec){
		$location.path('/gameMode');
	}

    var myEl = angular.element( document.querySelector( '#quizzes' ) );
	myEl.addClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#sections' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#report' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#share' ) );
	myEl.removeClass('hs-side-selected');
    var myEl = angular.element( document.querySelector( '#bin' ) );
	myEl.removeClass('hs-side-selected');
    
	$scope.currentPage = 1;
	$scope.pageSize = 3;

	$scope.searchHandled = function(currentQuiz){
		localStorage.setItem('currentQuiz' , currentQuiz);
		getLink = "/restAPI/api/hosts/get_all_handled_sections.php?admin_id="+ localStorage.getItem('user_id');
		$http.get(getLink).then(function(response){
			if(response.data.error){
				$scope.error = response.data;
			}else{
				$scope.allHandled = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	};

   $scope.quizTitle = 'MyQuizzenNo.0';
   getLink = "/restAPI/api/quizzes/read_quiz.php?admin_id="+ localStorage.getItem('user_id');
   $http.get(getLink).then(function(response){
       if(response.data.message){
		//    $scope.error = response.data.message;
		   $scope.error = response.data.message;
		   console.log($scope.error);
       }else{
			$scope.quizInfo = response.data;
			$scope.quizTitle = 'MyQuizzenNo.'+(response.data.length+1);
       }
   });
$scope.page = 1;

	  
	$scope.pageChanged = function() {
	var startPos = ($scope.page - 1) * 3;
	//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	};


	$scope.getQuizData = function (quizID) {
			$scope.quizID = quizID;
			getLink = "/restAPI/api/Quizzes/readsingle_quiz.php?quizID="+ quizID;
			$http.get(getLink).then(function(response){
				$scope.quizTitle = response.data.quiz_title;
				$scope.quizDesc = response.data.description;
				$scope.quiz_id = response.data.quiz_id;
				$scope.max_id = response.data.MaxID;
				$scope.totality = response.data.partsTotal;
				$scope.part_id = response.data.MaxPart;
				$scope.quizOwner = response.data.quizOwner;
			});
	}

//INSERT LAHAT NUNG NA GET NA DATA TO ANOTHER ADMIN
$scope.shareQuiz = function(quiz_id,id,max_id,totality,part_id,quizOwner,shareAbility) {
$scope.shareAbility = shareAbility;
$scope.quizID = quiz_id;
$scope.admin_id = id;
$scope.max = max_id
$scope.totalParts = totality;
$scope.MaxPart = part_id;
$scope.sharer = quizOwner;
getLink = "/restAPI/api/Quizzes/shareQuizzz.php?quizID="+ $scope.quizID + "&admID=" + $scope.admin_id  + "&MaxID=" + $scope.max + "&totalParts=" + $scope.totalParts + "&MaxPart=" + $scope.MaxPart + "&owner=" + $scope.sharer + "&capability=" + $scope.shareAbility;
$http.get(getLink).then(function(response){
swal("Good job!",  "Quiz Shared Successfully!", "success");
$('#shareQuiz-modal').modal('hide');
$('body').removeClass('modal-open');
$('.modal-backdrop').remove();
$route.reload();
});
};

$scope.adminList = function () {
getLink = "/restAPI/api/Quizzes/shareQuiz.php?admin_id=" + localStorage.getItem('user_id');
$http.get(getLink).then(function(response){
 $scope.fullname = response.data;
});
}

    $scope.updateQuiz = function(quizID, quizTitle, quizDesc){
        $scope.quizID = quizID;
        $scope.quizTitle = quizTitle;
        $scope.quizDesc = quizDesc;
	link = '/restAPI/api/Quizzes/update_quiz.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('quizID',$scope.quizID);
    fd.append('quizTitle',$scope.quizTitle);
    fd.append('description',$scope.quizDesc);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data.success){
			swal("Good job!", "Quiz Updated Successfully!", "success");
             $('#editQuiz-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
            console.log("fgf");
		}else{
			$scope.error = response.data;
		}
	}).catch(function(response){
		console.log(response);
	});
    }

   $scope.addNewQuiz = function (){
        if($scope.typeNewQuiz == 'Segment'){
        $scope.checkDefault = false;
        $scope.checkDefault1 = true;
        $scope.typeNewQuiz = 'Segment';
       }else{
           $scope.checkDefault = true;
           $scope.typeNewQuiz = 'Freeflow';
       }
		//DATA PAG SEGMENT YUNG QUIZ NA PWEDE MARAMING PARTS
		$scope.tags = angular.element('#addQ-hidden-input').val();
		var fd = new FormData();
		if($scope.files){
			fd.append('file',$scope.files[0]);
		}
		fd.append('quizTitle',$scope.quizTitle);
		fd.append('description',$scope.quizDesc);
		fd.append('part_type',$scope.typeNewQuiz);
		fd.append('tags',$scope.tags);
        if($scope.passingRate == undefined){
            $scope.passingRate = 50;
            fd.append('passingrate',$scope.passingRate);
        }else{
        fd.append('passingrate',$scope.passingRate);
        }
        
		fd.append('admin_id',localStorage.getItem("user_id"));
		link = '/restAPI/api/Quizzes/add_quiz.php';
 
		$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}})

		.then(function(response){

			if(response.data.success){
                swal("Good job!", "Segment Created Successfully!", "success");
				$('#newQuiz-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
				if ($scope.typeNewQuiz == 'Freeflow'){
					sendData = JSON.stringify({"type_name" : $scope.typeName , "duration" : $scope.duration});
					link = '/restAPI/api/Quizzes/setType.php';
					$http.post(link,sendData).then(function(response){
	                swal("Good job!", "Freeflow Created Successfully!", "success");
					$('#newQuiz-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
					}).catch(function(response){
					});
        		}

			}else{
				$scope.error1 = response.data.message;
				console.log(response.data);
			}
		})

		.catch(function(response){
			console.log(response);
		});

	};

    $scope.deleteQuizForever = function(quizID){
		getLink = "/restAPI/api/quizzes/trash-quiz.php?quizID="+quizID;
		 $http.get(getLink).then(function(response){
			 if(response.data.message){ 
              		swal("Quiz","Successfully Moved to Trash", "success");
					$('#deleteQuiz-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			 }else{
					swal("Ooops","Something went wrong", "error");
                 	swal("Quiz","Successfully Moved to Trash", "success");
					$('#deleteQuiz-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			 }
		 });
	 }
      $scope.passTagID = function(tagID) {
	  $scope.tagID = tagID;
}
    $scope.passQuizID = function(quizID) {
		$scope.quizID = quizID;
	}
    
    $scope.deleteTag = function(){
	link = '/restAPI/api/Quizzes/drop-tag.php';
 
	var fd = new FormData();
	fd.append('tag_id',$scope.tagID);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		if(response.data.success){
					swal("Hey","Tag Successfully Deleted!", "success");
					$('#deleteTag-modal').modal('hide');
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
        
    getLink = "/restAPI/api/quizzes/view-tags.php?admin_id="+localStorage.getItem('user_id');
	$http.get(getLink).then(function(response){
		 if(response.data){
			 $scope.folders = response.data;
			 $scope.tagIDS  = $scope.folders.map(function(value) {
				 return value.tag_id;
			   });
		 }
	 });
 
	 
	 $scope.testValue = "";
	 $scope.id = [];
 
	 
	 $scope.addMeToList = function(tagID){
		 if($scope.id.indexOf(tagID) !== -1) {
			 $scope.id.pop(tagID);
		 }else{
			 $scope.id.push(tagID);
		 }
		 $scope.testValue = $scope.id.join(',');
	 }
 
	 $scope.addTagzzz = function(){
		 getLink = "/restAPI/api/quizzes/add-to-coll-option.php?quizID="+$scope.quizID + "&tags="+ $scope.testValue;
		 $http.get(getLink).then(function(response){
			 if(response.data.success){
                 	swal("Good Job", "Successfully Added","success");
					$('#addtoCollection-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			 }else{
				 swal ("Ooops", "Something went wrong", "error");
				 $('#addtoCollection-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			 }
			 console.log(response.data);
		 });
	 }  
});

theApp.controller('empRequest', function($scope, $http,$location,myService){
    myService.checkMeBaby();
	$scope.openRequest = function(){
		
		if($scope.message == undefined){
			$scope.message = null;
		}

		if($scope.mname == undefined){
			$scope.mname = null;
		}
		sendData = JSON.stringify({"empID" : $scope.empID, "fname" : $scope.fname, "mname" : $scope.mname, "lname" : $scope.lname, "message" : $scope.message, "status" : 'pending'});
		link = "/restAPI/api/homestead/Admins/request-homestead.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				$location.path('/wait');
				$scope.empID = null;
			}else if (response.data.pending){
				$location.path('/pending/'+$scope.empID);
				$scope.empID = null;
			}else{
				console.log(response.data);
			}
		}).catch(function(response){
			console.log(response);
		});
	}
});

theApp.controller('partsCtrlr', function($scope,$http,$route, $routeParams,$location,myService){
    myService.checkMeBaby();
	$scope.currentPage = 1;
	$scope.pageSize = 4;

	$scope.quiz_id = $routeParams.quiz_id;
	getLink = "/restAPI/api/quizzes/viewQuizPart.php?quiz_id="+ $routeParams.quiz_id;

	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = response.data.message;
		}else{
			$scope.quizTitle= response.data[0].QuizTitle;
			$scope.description= response.data[0].Description;
			$scope.filepath = response.data[0].Filepath;
			$scope.parts = response.data;
            $scope.totalQs= response.data[0].TotalQs;
            $scope.Duration= response.data[0].Duration;
            $scope.totalParts= response.data[0].totalParts;
            $scope.capability = response.data[0].capability;
             $scope.tagName = response.data[0].tagName;
		}

	});

	// HERE WILL RISE THE FREEFLOW QUIZ SOMETHING
	getLink2 = "/restAPI/api/quizzes/fetchFreeFlow.php?quiz_id="+ $routeParams.quiz_id;
	$http.get(getLink2).then(function(response){
		$scope.freeFlow = response.data[0];
	}).catch(function(response){
		console.log(response);
	});

	$scope.set_color = function(TypeID) {

    	if(TypeID == 1)
        	return {
				"width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px", "background-color": "#FEDFC8","color": "rgba(176,96,0,.72)"
			}

    	else if(TypeID == 2)
        	return {
				"background-color": "#E9D2FD", "width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(104,29,168,.72)"
			};
				
		else if(TypeID == 3)
		    return {
				"background-color": "#CBF0F8", "width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(0,123,131,.72)"
			};
		else
			return {
				"background-color": "#FEEFC3","width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(227,116,0,.72)"
			};
	};

$scope.checkQuestions = function(TotalQs) {
 if ( TotalQs == 0 ) { // your question said "more than one element"
   return true;
  }
  else {
   return false;
  }
};

$scope.transferQuestionData = function(QuestionID, QuizID, PartID, Question, Answer) {

		$scope.QuestionID = QuestionID;
		$scope.QuizID = QuizID;
		$scope.PartID = PartID;
		$scope.question = Question;
		$scope.answer = Answer;
		getLink = "/restAPI/api/quizzes/view_questions.php?part_id="+ PartID;
		$http.get(getLink).then(function (response) {
				$scope.questions = response.data;
		}).catch(function (response) {
				
		});
}

$scope.transferMultipleData = function(QuestionID,QuizID, PartID, Question, Answer,a,b,c,d) {
        $scope.QuizID = QuizID;
        $scope.PartID = PartID;
		$scope.QuestionID = QuestionID;
		$scope.question = Question;
        $scope.answer = Answer;
		$scope.choice1 = a;
        $scope.choice2 = b;
        $scope.choice3 = c;
        $scope.choice4 = d;
		getLink = "/restAPI/api/quizzes/view_questions.php?part_id="+ PartID;
		$http.get(getLink).then(function (response) {
				$scope.questions = response.data;
		}).catch(function (response) {
				console.log(response);
		});
}

$scope.updateTrueorFalse = function(QuestionID, QuizID, PartID, question, answer){
	link = '/restAPI/api/Quizzes/updatequestion.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('quizID',QuizID);
    fd.append('partID',PartID);
    fd.append('question_id',QuestionID);
    fd.append('new_question',question);
    fd.append('correct',answer);
     
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data.success){
			swal("Question!", "Updated Successfully!", "success");
             $('#multipleChoicemodal_2').modal('hide');
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

$scope.updateGuessWord = function(QuestionID, QuizID, PartID, question, answer){
	link = '/restAPI/api/Quizzes/updatequestion.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('quizID',QuizID);
    fd.append('partID',PartID);
    fd.append('question_id',QuestionID);
    fd.append('new_question',question);
    fd.append('correct',answer);
     
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data.success){
			swal("Question!", "Updated Successfully!", "success");
             $('#multipleChoicemodal_4').modal('hide');
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

$scope.updateMultiple = function(QuestionID, question, answer,a,b,c,d){
	link = '/restAPI/api/Quizzes/updateMultiple.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('question_id',QuestionID);
    fd.append('question',question);
    fd.append('correct',answer);
    fd.append('d',d);
    fd.append('a',a);
    fd.append('b',b);
    fd.append('c',c);
     
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data){
			swal("Question!", "Updated Successfully!", "success");
             $('#multipleChoicemodal_4').modal('hide');
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

$scope.updateArrange = function(QuestionID, question,a,b,c,d){
	link = '/restAPI/api/Quizzes/updateArrange.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('question_id',QuestionID);
    fd.append('question',question);
    fd.append('d',d);
    fd.append('a',a);
    fd.append('b',b);
    fd.append('c',c);
     
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data.success){
			swal("Question!", "Updated Successfully!", "success");
             $('#multipleChoicemodal_3').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
		}else{
			$scope.error = response.data;
		}
	}).catch(function(response){
        swal("Question!", "Updated Successfully!", "success");
             $('#multipleChoicemodal_4').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
		console.log(response);
	});
    }
/*$scope.updateArrange = function(QuestionID, question, answer,a,b,c,d) {
		sendData = JSON.stringify({"question_id": QuestionID,"question": question, "correct": answer, "a":a,"b":b,"c":c,"d":d});
		link = '/restAPI/api/Quizzes/updateArrange.php';
		$http.post(link,sendData).then(function(response){
           swal("Question", "Successfully Updated","success");
                $route.reload();  
                 $('#multipleChoicemodal_3').modal('show').modal('hide');
 }).catch(function(response){
                swal("Question", "Successfully Updated","success");
                $route.reload();  
                 $('#multipleChoicemodal_3').modal('show').modal('hide');
				console.log(response);
 });
}*/
$scope.passPartID = function (partID){
    $scope.part_id = partID;
}
$scope.passQuesID = function (QuestionID){
    $scope.question_id = QuestionID;
}
 $scope.deletePart = function(){
	link = '/restAPI/api/Quizzes/drop-part.php';
 
	var fd = new FormData();
	fd.append('part_id',$scope.part_id);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		
		if(response.data.success){
					swal("Part", "Successfully Deleted","success");
					$('#deleteSegment-modal').modal('hide');
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
 $scope.deleteQuestion = function(){
	link = '/restAPI/api/Quizzes/drop-question.php';
 
	var fd = new FormData();
	fd.append('question_id',$scope.question_id);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		
		if(response.data.success){
            		swal("Question","Successfully Deleted","success");
					$('#deleteSegmentQues-modal').modal('hide');
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
$scope.view = function(PartID){

	$scope.PartID = PartID;

	getLink = "/restAPI/api/quizzes/view_questions.php?part_id="+ $scope.PartID;

	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = response.data.message;
			
		}else{
			$scope.questions = response.data;
		}
	});
};

	$scope.addPart = function(){
		sendData = JSON.stringify({"type_name" : $scope.typeName, "quizID" : $routeParams.quiz_id, "part_title" : $scope.partTitle, "duration" : $scope.duration});
		link = '/restAPI/api/Quizzes/addQuizPart.php';
		$http.post(link,sendData).then(function(response){
			if(response.data.success){
              		swal("Part","Successfully Added","success");
					$('#newPart-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}else if(response.data.error){
               swal("Ooops","Something Went Wrong","error");
					$('#newPart-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}
		}).catch(function(response){
			console.log(response);
		});
	   };

	$scope.printAnwerKey = function(){
		window.open('../../../restAPI/api/Hosts/printPDF.php?id='+$routeParams.quiz_id+"&admin_id="+localStorage.getItem('user_id'));
	}
    $scope.printAnwerKeyFreeflow = function(){
		window.open('../../../restAPI/api/Hosts/printPDFFreeflow.php?id='+$routeParams.quiz_id+"&admin_id="+localStorage.getItem('user_id'));
	}
    $scope.showAnswer = function(a,b,c,d,Answer){
        $scope.a=a; $scope.b=b; $scope.c=c;$scope.d=d; $scope.answer = Answer;
        if ($scope.a == $scope.answer){
            $scope.showIcon1 = 'far fa-check-circle text-qznsuccess';
            $scope.showIcon2 = 'far fa-times-circle text-danger';
            $scope.showIcon3 = 'far fa-times-circle text-danger';
            $scope.showIcon4 = 'far fa-times-circle text-danger';
        }else if ($scope.b == $scope.answer){
            $scope.showIcon1 = 'far fa-times-circle text-danger';
            $scope.showIcon2 = 'far fa-check-circle text-qznsuccess';
            $scope.showIcon3 = 'far fa-times-circle text-danger';
            $scope.showIcon4 = 'far fa-times-circle text-danger';
        }else if ($scope.c == $scope.answer){
            $scope.showIcon1 = 'far fa-times-circle text-danger';
            $scope.showIcon2 = 'far fa-times-circle text-danger';
            $scope.showIcon3 = 'far fa-check-circle text-qznsuccess';
            $scope.showIcon4 = 'far fa-times-circle text-danger';
        }else if ($scope.d == $scope.answer){
            $scope.showIcon1 = 'far fa-times-circle text-danger';
            $scope.showIcon2 = 'far fa-times-circle text-danger';
            $scope.showIcon3 = 'far fa-times-circle text-danger';
            $scope.showIcon4 = 'far fa-check-circle text-qznsuccess';
        }   
    }
});
//readOnly
theApp.controller('readOnlyCtrlr', function($scope,$http,$route, $routeParams,myService){
    myService.checkMeBaby();
    $scope.set_color = function(TypeID) {

    	if(TypeID == 1)
        	return {
				"width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px", "background-color": "#FEDFC8","color": "rgba(176,96,0,.72)"
			}

    	else if(TypeID == 2)
        	return {
				"background-color": "#E9D2FD", "width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(104,29,168,.72)"
			};

		else if(TypeID == 3)
		    return {
				"background-color": "#CBF0F8", "width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(0,123,131,.72)"
			};
		else
			return {
				"background-color": "#FEEFC3","width": "auto", "display": "inline-block", "padding": "2px 10px", "border-radius": "50px","color": "rgba(227,116,0,.72)"
			};
	};

$scope.checkQuestions = function(TotalQs) {
 if ( TotalQs == 0 ) { // your question said "more than one element"
   return true;
  }
  else {
   return false;
  }
};
	$scope.quiz_id = $routeParams.quiz_id;
	getLink = "/restAPI/api/quizzes/viewQuizPart.php?quiz_id="+ $routeParams.quiz_id;

	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = response.data.message;
		}else{
			
			$scope.quizTitle= response.data[0].QuizTitle;
            $scope.description= response.data[0].Description;
            $scope.PartID= response.data.PartID;
			$scope.parts = response.data;
        }
	$scope.view = function(PartID,TypeID){
        $scope.TypeID = TypeID;
		$scope.PartID = PartID;

        if ($scope.TypeID == 1){
        $scope.validate_1 = true;
        $scope.validate_2 = false;
        $scope.validate_3 = false;
        $scope.validate_4 = false;
        }
        else if ($scope.TypeID == 2){
        $scope.validate_1 = false;
        $scope.validate_2 = true;
        $scope.validate_3 = false;
        $scope.validate_4 = false;
        }
        else if ($scope.TypeID == 4){
        $scope.validate_1 = false;
        $scope.validate_2 = false;
        $scope.validate_3 = false;
        $scope.validate_4 = true;
        }
        else{
        $scope.validate_1 = false;
        $scope.validate_2 = false;
        $scope.validate_3 = true;
        $scope.validate_4 = false;
        }
		getLink = "/restAPI/api/quizzes/view_questions.php?part_id="+ $scope.PartID;
          
        
		$http.get(getLink).then(function(response){
			if(response.data.message){
				$scope.error = response.data.message;
			}else{
				
				$scope.questions = response.data;
			}
		});
        };
  });
});
theApp.controller('createQuizCtrlr', function($scope,$http,$location,myService){
    myService.checkMeBaby();
    $scope.quizTitle = 'MyQuizzenNo.0';
	getLink = "/restAPI/api/quizzes/read_quiz.php?admin_id="+ localStorage.getItem('user_id');
   $http.get(getLink).then(function(response){
       if(response.data.message){
		   $scope.error = response.data.message;
       }else{
		$scope.quizInfo = response.data;
		$scope.quizTitle = 'MyQuizzenNo.'+(response.data.length+1);
       }
   });

});

theApp.controller('updatePart', function($scope, $http, $routeParams, $location,myService){
    myService.checkMeBaby();
	getLink = "/restAPI/api/Quizzes/single-part.php?partID="+ $routeParams.part_id;

	if ($routeParams.totalqs > 0){
		$scope.total = $routeParams.totalqs;
	}
	$http.get(getLink).then(function(response){
		
		$scope.part_title = response.data.part_title;
		$scope.duration = response.data.duration;
		$scope.type = response.data.type;
	});

	$scope.updatePart = function(){
		sendData = JSON.stringify({"new_part_title" : $scope.part_title, "type_name" : $scope.type, "duration" : $scope.duration, "part_id" : $routeParams.part_id});
		link = '/restAPI/api/Quizzes/editQuizPart.php';
		$http.post(link,sendData).then(function(response){
			
			if(response.data.success){
					swal("Part","Successfully Updated","success");
					/*$('#newPart-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();*/
					$route.reload();
			}else{
					swal("Oops","somethis went wrong","error");
					/*$('#newPart-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();*/
					$route.reload();
			}
		}).catch(function(response){
			console.log(response);
		});
	};
});

theApp.controller('multipleCtrlr', function($scope,$http,$route,sessionService,$routeParams,$location,myService){
   
    myService.checkMeBaby();

	$scope.multipleQuestion = function(){
        //CHECK DUPLICATION
    if($scope.choice1 == $scope.choice2 || $scope.choice1 == $scope.choice3  || $scope.choice1 == $scope.choice4){
             $scope.error = $scope.choice1+" repeated to your choices";
             return;
    }else if($scope.choice2 == $scope.choice1 || $scope.choice2 == $scope.choice3  || $scope.choice2 == $scope.choice4){
             $scope.error = $scope.choice2+" repeated to your choices";
             return;
    }else if($scope.choice3 == $scope.choice1 || $scope.choice3 == $scope.choice2  || $scope.choice3 == $scope.choice4){
             $scope.error = $scope.choice3+" repeated to your choices";
             return;
    }else if($scope.choice4 == $scope.choice1 || $scope.choice4 == $scope.choice2  || $scope.choice4 == $scope.choice3){
             $scope.error = $scope.choice4+" repeated to your choices";
             return;
    }
         //SET THE CORRECT ANSWER
    if ($scope.answer1 == 'one'){
        $scope.answer = $scope.choice1;
    }else if ($scope.answer2 == 'two'){
        $scope.answer = $scope.choice2;
    }else if ($scope.answer3 == 'three'){
        $scope.answer = $scope.choice3;
    }else{
        $scope.answer = $scope.choice4;
        //eto yung default answer si D sa radio btn
    }
       // alert($scope.answer);
		var fd = new FormData();
		if($scope.files){
			fd.append('file',$scope.files[0]);
		}
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);
		fd.append('question',$scope.question);
		fd.append('correct',$scope.answer);
		fd.append('a',$scope.choice1);
		fd.append('b',$scope.choice2);
		fd.append('c',$scope.choice3);
		fd.append('d',$scope.choice4);

	link = "/restAPI/api/Quizzes/multiple_choice.php";
    	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
			if(response.data.success){
				$scope.question = "";
				$scope.answer = "";
				$scope.choice1 = "";
				$scope.choice2 = "";
				$scope.choice3 = "";
				$scope.choice4 = "";
                $scope.file = "";
                $scope.error = "";
				swal('Success' , 'Question successfully uploaded.' , 'success');
				$route.reload();
			}else{
				swal('Error' , 'Question failed to upload.' , 'error');
				$route.reload();
			}
	}).catch(function(response){
		console.log(response);
	});

   };

	$scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('multiple',$scope.files[0]);
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			if(response.data.error){
				$scope.clearResponse();
				$scope.csverror = response.data;
                swal('Error' , 'Question failed to upload.' , 'error');
                $('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}else{
				$scope.clearResponse();
				$scope.csvsuccess = response.data;
                swal('Success' , 'Question successfully uploaded.' , 'success');
				$('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
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

theApp.controller('addCtrlr', function($scope,$http,sessionService,$routeParams,$location,myService){
    myService.checkMeBaby();

	$scope.addQuestion = function(){

		var fd = new FormData();
		if($scope.files){
			fd.append('file',$scope.files[0]);
		}
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);
		fd.append('question',$scope.question);
		fd.append('correct',$scope.answer);

	link = "/restAPI/api/Quizzes/true_or_false.php";
    	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		if(response.data.success){
			$scope.question = "";
			$scope.answer = "";
			swal('Success' , 'Question successfully uploaded.' , 'success');
			$route.reload();
		}else{
			swal('Error' , 'Question failed to upload.' , 'error');
			$route.reload();
		}
	}).catch(function(response){
		console.log(response);
	});

   };

   	$scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('TorF',$scope.files[0]);
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			$scope.clearResponse();
			if(response.data.error){	
				$scope.csverror = response.data;
				swal('Error' , 'Question failed to upload.' , 'error');
			$('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}else{
				$scope.csvsuccess = response.data;
				swal('Success' , 'Question successfully uploaded.' , 'success');
				$('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
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

theApp.controller('guessCtrlr', function($scope,$http,sessionService,$routeParams,$location,myService){
    
    myService.checkMeBaby();

	$scope.guessQuestion = function(){

		var fd = new FormData();
		if($scope.files){
			fd.append('file',$scope.files[0]);
		}
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);
		fd.append('question',$scope.question);
		fd.append('correct',$scope.answer);

	link = "/restAPI/api/Quizzes/guess_the_word.php";
    	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
			if(response.data.success){
					$scope.question = "";
					$scope.answer = "";
					swal('Success' , 'Question successfully uploaded.' , 'success');
					$route.reload();
			}else{
				swal('Error' , 'Question failed to upload.' , 'error');
				$route.reload();
			}
	}).catch(function(response){
		console.log(response);
	});

   };

   $scope.uploadCSV = function(){
   		var fd = new FormData();
		fd.append('GTW',$scope.files[0]);
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			$scope.clearResponse();
			if(response.data.error){
				$scope.csverror = response.data;
				swal('Error' , 'Question failed to upload.' , 'error');
			$('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}else{
				$scope.csvsuccess = response.data;
				swal('Success' , 'Question successfully uploaded.' , 'success');
				$('#importCSV-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
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

theApp.controller('arrangeCtrlr', function($scope,$http,sessionService,$routeParams,$location,myService,$route){
    
    myService.checkMeBaby();

	$scope.arrangeQuestion = function(){

		var fd = new FormData();
		if($scope.files){
			fd.append('file',$scope.files[0]);
		}
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);
		fd.append('question',$scope.question);
		fd.append('a',$scope.choice1);
		fd.append('b',$scope.choice2);
		fd.append('c',$scope.choice3);
		fd.append('d',$scope.choice4);

	link = "/restAPI/api/Quizzes/arrange_the_sequence.php";
	
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		swal('Success' , 'Question successfully uploaded.' , 'success');
        $route.reload();
	}).catch(function(response){
		swal('Ooops!' , 'Adding Question Failed' , 'error');
        $route.reload();
	});

   };

   $scope.uploadCSV = function(){
		var fd = new FormData();
		fd.append('arrange',$scope.files[0]);
		fd.append('quiz_id',$routeParams.quiz_id);
		fd.append('part_id',$routeParams.part_id);

		$http.post('/restAPI/api/Quizzes/csv_multiple_choice.php',fd,{
			transfromRequest:angular.identity,
			headers:{'Content-Type':undefined}
		}).then(function(response){
			console.log(response.data);
		}).catch(function(response){
			console.log(response.data);
		});
	};
});

theApp.controller('empIDChecker' , function($scope, $http, $location){
	$scope.checkID = function(){
		sendData = JSON.stringify({"empID": $scope.empID});
		link = "/restAPI/api/Homestead/Admins/empIDChecker.php";
		$http.post(link, sendData).then(function(response){
			if(!response.data.error){
				if(response.data.pending){
					$location.path('/pending/'+$scope.empID);
				}else if(response.data.empData[0].status == "true"){
					swal("Ooops","This employee id is already used!","error");
				}else{
					$scope.fname = response.data.empData[0].fname;
					$scope.lname = response.data.empData[0].lname;
					$scope.empID = response.data.empData[0].id;
					
					$location.path('/signup/'+$scope.fname+'*'+$scope.lname+'*'+$scope.empID);
				}
				
			}else{
				swal("Ooops",response.data.error,"error");
                $scope.empID = "";
			}
		}).catch(function(response){
			console.log(response);
		});
	};
});

theApp.controller('empRequest', function($scope, $http){
	$scope.openRequest = function(){
		
		if($scope.message == undefined){
			$scope.message = null;
		}

		if($scope.mname == undefined){
			$scope.mname = null;
		}

		sendData = JSON.stringify({"empID" : $scope.empID, "fname" : $scope.fname, "mname" : $scope.mname, "lname" : $scope.lname, "message" : $scope.message, "status" : "pending"});
		link = "/restAPI/api/homestead/Admins/request-homestead.php";
		$http.post(link, sendData).then(function(response){
			if(response.data.success){
				swal("Request Sent", "Request Successfully Created", "success");
                $scope.empID = "";
                $scope.fname = "";
                $scope.mname = "";
                $scope.lname = "";
                $scope.message = "";
                $scope.error = "";
			}else if (response.data.message){
				$scope.error = response.data.message;
			}else{
				$scope.error = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	}
});

	theApp.controller('viewTagCtrl', function($scope,$route, $http, $routeParams, $location,myService){

    myService.checkMeBaby();

    $scope.quizoverlap = 0;

	$scope.takerChecker = function(sectionID){
		
		localStorage.setItem('currentSection' , sectionID);
		
		getLink = "/restAPI/api/QUIZZES/quiz-taker-checker.php?secID="+ sectionID + "&&sy=" + localStorage.getItem('activeSY')+"&quiz_id="+localStorage.getItem('currentQuiz');
		$http.get(getLink).then(function(response){
			if(response.data.success){
				$scope.selectSection(sectionID);
			}else{
				$scope.quizoverlap = sectionID;
			}
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.clearPreviousQuiz = function(section_id){
		sendData = JSON.stringify({"room_id" : localStorage.getItem('currentQuiz') + localStorage.getItem('currentSection') ,
									"section_id" : section_id });

	 	link = '/restAPI/api/Quizzes/overwrite-prev-rec.php';

		$http.post(link,sendData).then(function(response){
			$scope.selectSection(section_id);
		}).catch(function(response){
			console.log(response);
		});
	}

	$scope.clearOverlap = function(){
		$scope.quizoverlap = 0;
	}

	$scope.selectSection = function(selectedSec){
		$location.path('/gameMode');
	}

    $scope.searchHandled = function(currentQuiz){
		localStorage.setItem('currentQuiz' , currentQuiz);
		getLink = "/restAPI/api/hosts/get_all_handled_sections.php?admin_id="+ localStorage.getItem('user_id');
		$http.get(getLink).then(function(response){
			if(response.data.error){
				$scope.error = response.data;
			}else{
				$scope.allHandled = response.data;
			}
		}).catch(function(response){
			console.log(response);
		});
	};

	$scope.tagName = $routeParams.tag_name;
    $scope.tagID = $routeParams.tag_id;
	getLink = '/restAPI/api/quizzes/filter_quiz_by_tag.php?admin_id=' +localStorage.getItem("user_id") + '&tag_id=' + $routeParams.tag_id  ;
	$http.get(getLink).then(function(response){
		$scope.quizTagInfo = response.data.data;
        $scope.tagname = response.data.data.TagName;
		
	}).catch(function(response){
		console.log(response);
	});
    
    getLink = "/restAPI/api/quizzes/view-tags.php?admin_id="+localStorage.getItem('user_id');
   $http.get(getLink).then(function(response){
		if(response.data){
			
			$scope.folders = response.data;
			$scope.tagIDS  = $scope.folders.map(function(value) {
				return value.tag_id;
			  });
			
		}
	});
   $scope.quizTitle = 'MyQuizzenNo.0';
   getLink = "/restAPI/api/quizzes/read_quiz.php?admin_id="+ localStorage.getItem('user_id') + '&tag_id=' + $routeParams.tag_id;
   $http.get(getLink).then(function(response){
       if(response.data.message){
		   $scope.error = response.data.message;
		   
       }else{
       	
		$scope.quizInfo = response.data;
        $scope.tagName = $routeParams.tag_name;
		$scope.quizTitle = 'MyQuizzenNo.'+(response.data.length+1);
       }
   });
        
     $scope.updateQuiz = function(quizID, quizTitle, quizDesc){
        $scope.quizID = quizID;
        $scope.quizTitle = quizTitle;
        $scope.quizDesc = quizDesc;
	link = '/restAPI/api/Quizzes/update_quiz.php';
 
	var fd = new FormData();
   if($scope.files){
			fd.append('file',$scope.files[0]);
		}
	fd.append('quizID',$scope.quizID);
    fd.append('quizTitle',$scope.quizTitle);
    fd.append('description',$scope.quizDesc);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		console.log(response.data);
		if(response.data.success){
			swal("Good job!", "Quiz Updated Successfully!", "success");
             $('#editQuiz-modal').modal('hide');
             $('body').removeClass('modal-open');
             $('.modal-backdrop').remove();
             $route.reload();
            console.log("fgf");
		}else{
			$scope.error = response.data;
		}
	}).catch(function(response){
		console.log(response);
	});
    }
        
         $scope.passQuizID = function(quizID) {
		$scope.quizID = quizID;
	}
          $scope.testValue = "";
	 $scope.id = [];
         
        $scope.addMeToList = function(tagID){
		if($scope.id.indexOf(tagID) !== -1) {
			$scope.id.pop(tagID);
		}else{
			$scope.id.push(tagID);
		}
		$scope.testValue = $scope.id.join(',');
		
	}

	$scope.addTagzzz = function(){
		getLink = "/restAPI/api/quizzes/add-to-coll-option.php?quizID="+$scope.quizID + "&tags="+ $scope.testValue;
		$http.get(getLink).then(function(response){
			if(response.data.success){
				swal("Good Job", "Successfully Added","success");
					$('#addtoCollection-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}else{
				swal("Ooops","Something went wrong","error");
					$('#addtoCollection-modal').modal('hide');
					$('body').removeClass('modal-open');
					$('.modal-backdrop').remove();
					$route.reload();
			}
			console.log(response.data);
		});
	}
    
    $scope.removeTag = function(tagID, quizID, operation){
		getLink = '/restAPI/api/quizzes/remove-tag.php?tagID=' +tagID + '&quizID=' + quizID + '&operation=' + operation;
		$http.get(getLink).then(function(response){
			if(response.data.success){
				swal("Quiz","Successfully remove","success");
				$location.path('/myquizzen');
			}else{
				swal("Ooops","Something went wrong","error");
			}
		}).catch(function(response){
			console.log(response);
		});
	}

     $scope.addNewQuiz = function (){
         if($scope.typeNewQuiz == 'Segment'){
         $scope.checkDefault = false;
         $scope.checkDefault1 = true;
         $scope.typeNewQuiz = 'Segment';
        }else{
            $scope.checkDefault = true;
            $scope.typeNewQuiz = 'Freeflow';
        }
 		//DATA PAG SEGMENT YUNG QUIZ NA PWEDE MARAMING PARTS
 		$scope.tags = angular.element('#addQ-hidden-input').val();
        
 		var fd = new FormData();
 		if($scope.files){
 			fd.append('file',$scope.files[0]);
 		}
 		fd.append('quizTitle',$scope.quizTitle);
 		fd.append('description',$scope.quizDesc);
 		fd.append('part_type',$scope.typeNewQuiz);
 		fd.append('tags',$scope.tags);
         if($scope.passingRate == undefined){
            $scope.passingRate = 50;
            fd.append('passingrate',$scope.passingRate);
        }else{
        fd.append('passingrate',$scope.passingRate);
        }
 		fd.append('admin_id',localStorage.getItem("user_id"));
 		link = '/restAPI/api/Quizzes/add_quizByTag.php?tag_id='+ $routeParams.tag_id ;

 		$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}})

 		.then(function(response){

 			if(response.data.success){
                swal("Good job!", "Segment Created Successfully!", "success");
				$('#newQuiz-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
 				if ($scope.typeNewQuiz == 'Freeflow'){
 					sendData = JSON.stringify({"type_name" : $scope.typeName , "duration" : $scope.duration});
 					link = '/restAPI/api/Quizzes/setType.php';
 					$http.post(link,sendData).then(function(response){
 						swal("Good job!", "Freeflow Created Successfully!", "success");
				$('#newQuiz-modal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
				$route.reload();
 					}).catch(function(response){
 					});
         		}

 			}else{
 				$scope.error1 = response.data.message;
 				
 			}
 		})

 		.catch(function(response){
 			console.log(response);
 		});

 	};

  $scope.getQuizData = function (quizID) {
		$scope.quizID = quizID;
getLink = "/restAPI/api/Quizzes/readsingle_quiz.php?quizID="+ quizID;
$http.get(getLink).then(function(response){
	
	$scope.quizTitle = response.data.quiz_title;
	$scope.quizDesc = response.data.description;
	$scope.quiz_id = response.data.quiz_id;
	$scope.max_id = response.data.MaxID;
	$scope.totality = response.data.partsTotal;
	$scope.part_id = response.data.MaxPart;
    $scope.quizOwner = response.data.quizOwner;
	
	
});
}

//INSERT LAHAT NUNG NA GET NA DATA TO ANOTHER ADMIN
$scope.shareQuiz = function(quiz_id,id,max_id,totality,part_id,quizOwner,shareAbility) {
$scope.shareAbility = shareAbility;
$scope.quizID = quiz_id;
$scope.admin_id = id;
$scope.max = max_id
$scope.totalParts = totality;
$scope.MaxPart = part_id;
$scope.sharer = quizOwner;
getLink = "/restAPI/api/Quizzes/shareQuizzz.php?quizID="+ $scope.quizID + "&admID=" + $scope.admin_id  + "&MaxID=" + $scope.max + "&totalParts=" + $scope.totalParts + "&MaxPart=" + $scope.MaxPart + "&owner=" + $scope.sharer + "&capability=" + $scope.shareAbility;
$http.get(getLink).then(function(response){

swal("Good job!",  "Quiz Shared Successfully!", "success");
$('#shareQuiz-modal').modal('hide');
$('body').removeClass('modal-open');
$('.modal-backdrop').remove();
$route.reload();
});
};

  $scope.adminList = function () {
  getLink = "/restAPI/api/Quizzes/shareQuiz.php?admin_id=" + localStorage.getItem('user_id');
  $http.get(getLink).then(function(response){
   
   $scope.fullname = response.data;
  });
  }

	$scope.streamQuiz = function($section_id){
		$postData = JSON.stringify({"quiz_id" : $routeParams.quiz_id , "admin_id" : localStorage.getItem('user_id' ) , "section_id" : $section_id });
		$link = '/restAPI/api/Quizzes/up_quiz.php';
		$http.post($link,$postData).then(function(response){
			console.log(response.data);
		}).catch(function(){

		});
	}
    
    $scope.deletequizzes = function(){
	link = '/restAPI/api/Quizzes/drop-quizzes.php';
 
	var fd = new FormData();
	fd.append('quiz_id',$scope.quizID);
	$http.post(link,fd,{transfromRequest:angular.identity,headers:{'Content-Type':undefined}}).then(function(response){
		
		if(response.data.success){
			swal ("Quiz", "Deleted Successfully","success");
$('#deleteQuiz-modal').modal('hide');
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
});

theApp.controller('showUp',function($scope,$http,$routeParams,myService){
    myService.checkMeBaby();
	$http.get('/restAPI/api/Sections/readHandledSection.php?adminId='+localStorage.getItem("user_id")).then(function(){
		$scope.sectionsHandled = response.data;
	}).catch(function(response){
		console.log(response);
	});
});

theApp.controller('pendingCtrl', function($scope, $location, $routeParams){
	$scope.empID = $routeParams.empID;
    
});

theApp.controller('sharedQuizCtrlr', function($scope, $http, $route, $location,myService){
    myService.checkMeBaby();
    var myEl = angular.element( document.querySelector( '#quizzes' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#sections' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#report' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#share' ) );
	myEl.addClass('hs-side-selected');
    var myEl = angular.element( document.querySelector( '#bin' ) );
	myEl.removeClass('hs-side-selected');
    
	getLink = '/restAPI/api/Quizzes/sharedQuizzes.php?adminId='+ localStorage.getItem('user_id');
	$http.get(getLink).then(function(response){
		if(response.data.message){
			$scope.error = response.data;
		}else{
			$scope.shares = response.data;
			
		}
	}).catch(function(response){
		$scope.error = response.data;
	})

	$scope.page = 1;

		
	$scope.pageChanged = function() {
	var startPos = ($scope.page - 1) * 3;
	//$scope.displayItems = $scope.totalItems.slice(startPos, startPos + 3);
	};
});

theApp.controller('accSettingsCtrl', function($scope, $http,myService){
    myService.checkMeBaby();
    var myEl = angular.element( document.querySelector( '#quizzes' ) );
	myEl.removeClass('hs-side-selected');
	var myEl = angular.element( document.querySelector( '#sections' ) );
	myEl.removeClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#report' ) );
	myEl.addClass('hs-side-selected'); 
	var myEl = angular.element( document.querySelector( '#share' ) );
	myEl.removeClass('hs-side-selected');
    var myEl = angular.element( document.querySelector( '#bin' ) );
	myEl.removeClass('hs-side-selected');
    
	getLink = "/restAPI/api/hosts/get-host-data.php?empID="+ localStorage.getItem('user_id');

	$http.get(getLink).then(function(response){
		if(response.data.error){
			alert("wala pang quiz");
		}else{
			console.log(response.data);
			$scope.fname = response.data[0].fname;
			$scope.mname = response.data[0].mname;
			$scope.lname = response.data[0].lname;
			$scope.username = response.data[0].username;
			$scope.oldPw = response.data[0].password;
			$scope.empID = response.data[0].empID;
		}
	});

	$scope.saveHost = function(){
		sendData = JSON.stringify({"fname" : $scope.fname, "mname" : $scope.mname, "lname" : $scope.lname, "empID" : $scope.empID});
		$link = '/restAPI/api/Hosts/account-settings.php';
		$http.post($link, sendData).then(function(response){
            swal("Good Job","Data Successfully Updated","success");
			console.log(response.data);
		}).catch(function(){

		});
	};
	
	$scope.saveCred = function(){
	
		sendData = JSON.stringify({"password" : $scope.password, "newpw" : $scope.newpw, "confpw" : $scope.confpw, "admin_id" : localStorage.getItem('user_id')});
		$link = '/restAPI/api/Hosts/update-unpw.php';
		$http.post($link, sendData).then(function(response){
			if(response.data.success){
				swal("Password updated!", "", "success");
			}else if(response.data.dontmatch){
				swal("Passwords dont match!", "", "error");
			}else if (response.data.wrongpw){
				swal("Wrong password!", "", "error");
			}
		}).catch(function(){

		});
	}
});
/*--REPORTS--*/
theApp.controller('quizTakenCtrlr', function($http , $scope , $routeParams , $location,myService){
    myService.checkMeBaby();
	getData = "/restAPI/api/Hosts/get-quiz-taken.php?section_id="+$routeParams.section_id;
	$http.get(getData).then(function(response){
		$scope.quizTaken = response.data;
        $scope.section = response.data[0].section;
		console.log(response.data);
	}).catch(function(response){
		console.log(response);
	});

	//PRINT MGA TAKEN QUIZ NG SECTION
	$scope.printQuizTaken = function(){
		window.open('../../../restAPI/api/Hosts/printQuizTakenPDF.php?id='+$routeParams.section_id+"&admin_id="+localStorage.getItem('user_id'));
	}

	//VIEW QUIZ SUMMARY
	$scope.quizSummary = function(quiz_id,room_id){
		$location.path("quizSummaryReport/"+quiz_id+"/"+$routeParams.section_id+"/"+room_id);
	}

	//VIEW QUIZ PASSERS
	$scope.quizPassers = function(quiz_id,room_id){
		$location.path("quizPassersReport/"+quiz_id+"/"+$routeParams.section_id+"/"+room_id);
	}

	//VIEW QUIZ FAILURES
	$scope.quizFailures = function(quiz_id,room_id){
		$location.path("quizFailuresReport/"+quiz_id+"/"+$routeParams.section_id+"/"+room_id);
	}

	//VIEW STUDENTS ANSWERS
	$scope.quizAnswers = function(quiz_id,room_id){
		$location.path("quizFailuresReport/"+quiz_id+"/"+$routeParams.section_id+"/"+room_id);
	}
});


theApp.controller('quizSummaryCtrlr', function($http , $scope , $location , $routeParams,myService){
    myService.checkMeBaby();
	//gets the fucking header man
	getData = "/restAPI/api/Hosts/get-quiz-header.php?quiz_id="+$routeParams.quiz_id +"&section_id="+$routeParams.section_id;
	$http.get(getData).then(function(response){
		$scope.header = response.data[0];
	}).catch(function(response){
		console.log(response);
	});
	
	//gets the true mothafucking shit
	getData2 = "/restAPI/api/Hosts/get-quiz-summary.php?room_id="+$routeParams.room_id;
	$http.get(getData2).then(function(response){
		$scope.studentTakers = response.data;
	}).catch(function(response){
		console.log(response);
	});

	//gets the nakatama nakamali per tanong
	getData3 = "/restAPI/api/Hosts/get-quiz-question-results.php?quiz_id="+$routeParams.quiz_id+"&room_id="+$routeParams.room_id;
	$http.get(getData3).then(function(response){
		$scope.questionResults = response.data;
	}).catch(function(response){
		console.log(response);
	});

	$scope.printQuizSummary = function(){
		window.open('../../../restAPI/api/Hosts/printQuizSummary.php?quiz_id='+$routeParams.quiz_id+"&section_id="+$routeParams.section_id+"&room_id="+$routeParams.room_id+"&admin_id="+localStorage.getItem('user_id'));
	};

	$scope.getAnswers = function(user_id){
		$location.path("/userAnswersReport/"+user_id+"/"+$routeParams.room_id+"/"+$routeParams.quiz_id);
	}	

});

theApp.controller('userAnswersCtrlr', function($http , $scope , $location , $routeParams,myService){
	myService.checkMeBaby();
	getData = "/restAPI/api/Hosts/get-quiz-answeree.php?room_id="+$routeParams.room_id+"&user_id="+$routeParams.user_id;
	$http.get(getData).then(function(response){
		$scope.answeree = response.data[0];
	}).catch(function(response){
		console.log(response);
	});

	//gets the true mothafucking shit
	getData2 = "/restAPI/api/Hosts/get-quiz-user-answers.php?room_id="+$routeParams.room_id+"&user_id="+$routeParams.user_id+"&quiz_id="+$routeParams.quiz_id;
	$http.get(getData2).then(function(response){
		$scope.userAnswers = response.data;
	}).catch(function(response){
		console.log(response);
	});

	$scope.printUserAnswers = function(){
		window.open('../../../restAPI/api/Hosts/printUserAnswer.php?quiz_id='+$routeParams.quiz_id+"&room_id="+$routeParams.room_id+"&user_id="+$routeParams.user_id+"&admin_id="+localStorage.getItem('user_id'));
	};

});

theApp.controller('quizPassersCtrlr', function($http , $scope , $location , $routeParams,myService){
    myService.checkMeBaby();
	getData = "/restAPI/api/Hosts/get-quiz-header.php?quiz_id="+$routeParams.quiz_id +"&section_id="+$routeParams.section_id;
	$http.get(getData).then(function(response){
		$scope.header = response.data[0];
	}).catch(function(response){
		console.log(response);
	});

	//gets the passers
	getData2 = "/restAPI/api/Hosts/get-quiz-passers.php?room_id="+$routeParams.room_id;
	$http.get(getData2).then(function(response){
		$scope.passers = response.data;
	}).catch(function(response){
		console.log(response);
	});

	$scope.printQuizPassers = function(){
		window.open('../../../restAPI/api/Hosts/printQuizPassers.php?quiz_id='+$routeParams.quiz_id+"&section_id="+$routeParams.section_id+"&room_id="+$routeParams.room_id+"&admin_id="+localStorage.getItem('user_id'));
	};
	
});

theApp.controller('quizFailuresCtrlr', function($http , $scope , $location , $routeParams,myService){
    myService.checkMeBaby();
	getData = "/restAPI/api/Hosts/get-quiz-header.php?quiz_id="+$routeParams.quiz_id +"&section_id="+$routeParams.section_id;
	$http.get(getData).then(function(response){
		$scope.header = response.data[0];
	}).catch(function(response){
		console.log(response);
	});

	//gets the passers
	getData2 = "/restAPI/api/Hosts/get-quiz-failures.php?room_id="+$routeParams.room_id;
	$http.get(getData2).then(function(response){
		$scope.failures = response.data;
	}).catch(function(response){
		console.log(response);
	});

	$scope.printQuizFailures = function(){
		window.open('../../../restAPI/api/Hosts/printQuizFailures.php?quiz_id='+$routeParams.quiz_id+"&section_id="+$routeParams.section_id+"&room_id="+$routeParams.room_id+"&admin_id="+localStorage.getItem('user_id'));
	};
	
});





window.onbeforeunload = function(){
	return true;
}

window.onload = function(){
    if(localStorage.getItem('active_room')){
		localStorage.removeItem('active_room');
		window.location = "#!/myquizzen";
	}
}





theApp.controller('gameModes', function($routeParams , $scope , $http , $rootScope , $location , myService,$route){

	myService.nullMeBaby();
	//DEFAULT SETTINGS PARE
	$rootScope.game_mode = {"combo": false, "caseSensitive" : false, "randQ" : false, "randC" : false, "autoMove" : false};

	//IPLAPLAY NA PRE
	$scope.play = function(gamemode){
		$rootScope.game_mode.how = gamemode;
		console.log($rootScope.game_mode);
		$location.path('/hostQuizz');
	}
				$('#playQuiz-modal').modal('hide');
$('body').removeClass('modal-open');
$('.modal-backdrop').remove();
});

theApp.controller('waitingCtrlr', function($scope,$http,$location,mySocket,$rootScope,$routeParams,sessionService){

	localStorage.setItem('active_room', localStorage.getItem('currentQuiz') + localStorage.getItem('currentSection')); // pangset ng quizid as session

	$scope.kickClient = function(id){
		mySocket.emit('kickClient' , id);
	};


	getLink = "/restAPI/api/Quizzes/read_all_quiz_assests.php?quizID="+ localStorage.getItem('currentQuiz');
	$http.get(getLink).then(function(response){
		
		$rootScope.quizAssets = response.data.quiz[0];
		$rootScope.partAssets = response.data.parts;
		$rootScope.currentPart = 0;
		$rootScope.currentQuestion = 0;
		$rootScope.currentShow = 0;
		$rootScope.quiz_token = (new Date).getTime();

		function shuffle(array) {
		  var currentIndex = array.length, temporaryValue, randomIndex;
		  while (0 !== currentIndex) {
		    randomIndex = Math.floor(Math.random() * currentIndex);
		    currentIndex -= 1;
		    temporaryValue = array[currentIndex];
		    array[currentIndex] = array[randomIndex];
		    array[randomIndex] = temporaryValue;
		  }
		  return array;
		}

		//SETTINGS: RANDOM QUESTIONS
		if($rootScope.game_mode.randQ){
			for (var i = 0; i <= $rootScope.partAssets.length-1; i++) {
				if($rootScope.partAssets[i][0].length > 0){
					$rootScope.partAssets[i][0] = shuffle($rootScope.partAssets[i][0]);
				}
			}
		}

		mySocket.emit('adminCreateRoom' ,{
			room_id: localStorage.getItem('active_room'),
			section: localStorage.getItem('currentSection'),
			quiz_title: $rootScope.quizAssets.quiz_title,
			admin_id: localStorage.getItem('user_id')
		});

	}).catch(function(response){
		console.log(response);
	});

	mySocket.on('requestQuizPaper' , function(asd){
		mySocket.emit('giveQuizPaper' , asd, 
										$rootScope.partAssets , 
										$rootScope.game_mode , 
										{part: $rootScope.currentPart , question: $rootScope.currentQuestion , show: $rootScope.currentShow},
										$rootScope.quiz_token );
	});

	$scope.joinedStudents = [];
	mySocket.on('studentJoined' , function(newNickname){
		$scope.joinedStudents = newNickname;
	});

	mySocket.on('LeftRoom' , function(nickname){
		for (var i = 0; i <= $scope.joinedStudents.length-1; i++) {
			if($scope.joinedStudents[i].nickname == nickname){
				$scope.joinedStudents.splice(i,1);
			}
		}
	});

	$scope.startQuiz = function(){
		
		//RECORD DATA TO DATABASE
		sendData = JSON.stringify({"section_id" : localStorage.getItem('currentSection') ,
								 "admin_id" : localStorage.getItem('user_id') , 
								 "quiz_id" : localStorage.getItem('currentQuiz'),
								 "room_id": localStorage.getItem('active_room'),
								 "quiz_token" : $rootScope.quiz_token});

		$http.post('/restAPI/api/Quizzes/record_quiz.php' , sendData).then(function(response){
			
			for (var p = 0; p <=$rootScope.partAssets.length-1; p++) {
				//pag may quiz pre doon ka na mag start
					if($rootScope.partAssets[p][0].length >= 0){
						$rootScope.currentPart = p;
						$rootScope.currentQuestion = 0;
						break;
					}

			}

			$location.path('/streamPart');

		}).catch(function(response){
			console.log(response);
		});

	};

});


theApp.controller('streamPartCtrlr', function($scope , $rootScope , $timeout ,streamQuestion , mySocket){
	$scope.localPart = $rootScope.partAssets[$rootScope.currentPart];

	mySocket.emit('startQuiz' , localStorage.getItem('active_room'));

	$timeout(function(){
		streamQuestion.testType($rootScope.partAssets[$rootScope.currentPart].type_id);
	},5000);
});

theApp.controller('streamQuestionCtrlr', function(mySocket,$rootScope,$scope,$http ,$timeout , $location , $interval){
	
	$rootScope.countScope = $rootScope.partAssets[$rootScope.currentPart].duration;

	var partInt = $interval(function(){
		$rootScope.countScope -= 1;
		if($rootScope.countScope == 0){
			$rootScope.countScope = "TIME OUT!";
			$interval.cancel(partInt); 
		}
	},1000);

	$rootScope.localQuestion = $rootScope.partAssets[$rootScope.currentPart][0][$rootScope.currentQuestion].question;
	$scope.choices = $rootScope.partAssets[$rootScope.currentPart][0][$rootScope.currentQuestion];
	$rootScope.imahe = $rootScope.partAssets[$rootScope.currentPart][0][$rootScope.currentQuestion].filepath;

	$rootScope.doneStudentslen = 0;

	if($rootScope.game_mode.how == 'T'){
		
		mySocket.emit('StareQuestion' ,  localStorage.getItem('active_room') );
		$rootScope.currentShow = 4;	

		//lipat ng view tapos emit message
		showTimeout = $timeout(function(){

			$location.path("/discussion");
			mySocket.emit('Discuss' , localStorage.getItem('active_room') );
			$rootScope.currentShow = 3;	

			$timeout(function(){

				$rootScope.currentShow = 1;
				//emit na ng question bata
				mySocket.emit('giveQuestion' , 
					{room_id : localStorage.getItem('active_room') , 
					current_part: $rootScope.currentPart , 
					current_question : $rootScope.currentQuestion});

					$location.path("/showQAgain");

					endQTimeout = $timeout(function(){

						$rootScope.currentShow = 2;

					  mySocket.emit('endQuestion' , localStorage.getItem('active_room') , function(myPlayers){

					  	$rootScope.myStudents = myPlayers;
						$rootScope.correctStudents = 0;
						$rootScope.wrongStudents = 0;

						for(var o=0; o <= myPlayers.length-1; o++){

							if(myPlayers[o].status == true){
								$rootScope.correctStudents += 1;
							}else{
								$rootScope.wrongStudents += 1;
							}
						}
					  });

					  $location.path("/questionStats");

					}, $rootScope.partAssets[$rootScope.currentPart].duration * 10000);

			},10000);

		}, 10000);


	}else{

		$rootScope.currentShow = 1;	

		mySocket.emit('giveQuestion' , 
			{room_id : localStorage.getItem('active_room') , 
			current_part: $rootScope.currentPart , 
			current_question : $rootScope.currentQuestion});


		//time out para sa duration ng question
		endQTimeout = $timeout(function(){

			$rootScope.currentShow = 2;

		  mySocket.emit('endQuestion' , localStorage.getItem('active_room') , function(myPlayers){

		  	$interval.cancel(partInt); 

		  	$rootScope.myStudents = myPlayers;
			$rootScope.correctStudents = 0;
			$rootScope.wrongStudents = 0;

			for(var o=0; o <= myPlayers.length-1; o++){

				if(myPlayers[o].status == true){
					$rootScope.correctStudents += 1;
				}else{
					$rootScope.wrongStudents += 1;
				}
			}
		  });

		  $location.path("/questionStats");

		}, $rootScope.partAssets[$rootScope.currentPart].duration * 1000);

	}
	//pagka maaga natapos mga talipandas
	mySocket.on('allFinished' , function(){

		$timeout.cancel(endQTimeout);
		$interval.cancel(partInt);
		$rootScope.currentShow = 2;

		mySocket.emit('endQuestion' , localStorage.getItem('active_room') , function(myPlayers){
	  	$rootScope.myStudents = myPlayers;
		$rootScope.correctStudents = 0;
		$rootScope.wrongStudents = 0;

		for(var o=0; o <= myPlayers.length-1; o++){

			if(myPlayers[o].status == true){
				$rootScope.correctStudents += 1;
			}else{
				$rootScope.wrongStudents += 1;
			}
		}
	  });

		$location.path("/questionStats");

	});

	//pagka may humingi ng part saka question kasi late sila
		mySocket.on('requestQuizPnQ', function(socketid){
			mySocket.emit('giveDirectionLate' , {lateid: socketid , 
												room_id : localStorage.getItem('active_room') , 
												current_part: $rootScope.currentPart , 
												current_question : $rootScope.currentQuestion});
		});

		mySocket.on('studentAnswered' , function(doneshit){
			$rootScope.doneStudentslen = doneshit;
		});
});

theApp.controller('questionStatsCtrlr', function($scope, $rootScope , $timeout , $location){
	
	$scope.answer = $rootScope.partAssets[$rootScope.currentPart][0][$rootScope.currentQuestion].rightAnswer;
	$timeout(function(){
		$location.path("/leaderBoard"); 
	},5000);

	if($rootScope.partAssets[$rootScope.currentPart].type_id == 3){
		$scope.oonga = true;
	}
	$scope.choices = $rootScope.partAssets[$rootScope.currentPart][0][$rootScope.currentQuestion];

});

theApp.controller('leaderBoardCtrlr', function($scope , $location , $rootScope , mySocket , streamQuestion , sessionService , $timeout){

	$rootScope.tops = $rootScope.myStudents.sort(function(a, b) {
    	return b.score - a.score;
	});

	$scope.next = function(){

		if($rootScope.currentQuestion < $rootScope.partAssets[$rootScope.currentPart][0].length -1){
		
			$rootScope.currentQuestion += 1;
			streamQuestion.testType($rootScope.partAssets[$rootScope.currentPart].type_id);
		
		}else if($rootScope.currentQuestion == $rootScope.partAssets[$rootScope.currentPart][0].length -1){
			if($rootScope.currentPart < $rootScope.partAssets.length-1){
				for (var i = $rootScope.currentPart+1; i <= $rootScope.partAssets.length-1; i++) {
					var foundNewPart = false;
					if($rootScope.partAssets[i][0].length > 0){
						$rootScope.currentPart = i;
						$rootScope.currentQuestion = 0;
						foundNewPart = true;
						$location.path('/streamPart');
						break;
					}

					if(!foundNewPart){
						mySocket.emit('endQuiz' , localStorage.getItem('active_room'));
						localStorage.removeItem("currentQuiz");
						localStorage.removeItem("currentSection");
						localStorage.removeItem('active_room');
						$location.path("/quizSummary");
					}
				}
			}else{
				mySocket.emit('endQuiz' , localStorage.getItem('active_room'));
				localStorage.removeItem("currentQuiz");
				localStorage.removeItem("currentSection");
				localStorage.removeItem('active_room');
				$location.path("/quizSummary");
			}
		}
	}

	if($rootScope.game_mode.autoMove){	
		$timeout(function(){
			$scope.next();
		},5000); 
	}

	$scope.returnHome = function(){
		$location.path("/myquizzen");
	};
	
});
