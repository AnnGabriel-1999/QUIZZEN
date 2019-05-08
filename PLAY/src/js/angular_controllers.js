//module
var ongame = angular.module('ongame',['ngRoute' , 'btford.socket-io','ngStorage']);

ongame.factory('sessionService', function(){
	return{
		set:function(key,value){
			return localStorage.setItem(key,value);
		},
		get:function(key){
			return localStorage.getItem(key);
		},
		destroy:function(key){
			return localStorage.removeItem(key);
		}
	};
});

ongame.directive("limitTo", [function() {
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

window.onbeforeunload = function(){
	return true;
}

window.onload = function(){
    if (localStorage.getItem('room_joined')) {
        localStorage.removeItem("room_joined");
        window.location = "#!/home";
    }
}

ongame.factory('mySocket', function(socketFactory){

  return socketFactory({
  	ioSocket: io('http://192.168.1.90:4000',{transports: ['websocket'], upgrade: false}) //iiba ibahin pre based sa IP mo.
  });

});

//ROUTING
ongame.config(['$routeProvider', function($routeProvider) {
	$routeProvider
	
		.when('/discuss' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'student-discuss.html'
		})

		.when('/stare' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'stare.html'
		})

		.when('/login' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if ($localStorage.loggedClient){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'login.html',
			controller: 'loginCtrlr'
		})

		.when('/register' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if ($localStorage.loggedClient){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'register-student.html',
			controller: 'regCtrlr'
		})

		.when('/home' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedClient){
									 $location.path("/login");
							 }
					  }
			 },
			templateUrl: 'home.html',
			controller: 'homeCtrlr'
		})

		.when('/choseNickname/:roomId' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!$localStorage.loggedClient){
									 $location.path("/login");
							 }
					  }
			 },
			templateUrl: 'game-pin.html',
			controller: 'choseNicknameCtrlr'
		})

		.when('/waitingStart' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'waiting-page.html',
			controller: 'waitingStartCtrlr'
		})

		.when('/countdown' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'countdown-before-answer.html',
			controller: 'countdownCtrlr'
		})

		.when('/mQuestion' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'answer-multiple-choice.html',
			controller: 'QuestionCtrlr'
		})

		.when('/aQuestion' , {
			
			templateUrl: 'answer-arrange-the-sequence.html',
			controller: 'QuestionCtrlr'
		})

		.when('/gQuestion' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'answer-guess-the-word.html',
			controller: 'QuestionCtrlr'
		})

		.when('/tQuestion' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'answer-true-false.html',
			controller: 'QuestionCtrlr'
		})
		
		.when('/wait' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'wait.html',
		})

		.when('/result' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'reveal.html'
		})

		.when('/latecomer' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'late.html',
			controller: 'lateCtrlr'
		})

		.when('/feedback' , {
			resolve:{
					 "check": function($location,$localStorage){
							 if (!localStorage.getItem('room_joined')){
									 $location.path("/home");
							 }
					  }
			 },
			templateUrl: 'feedback.html',
			controller: 'feedbackCtrlr'
		})

		.otherwise({
			redirectTo: '/home'
		})
		
}]);

//CONTROLLERS

ongame.controller('loginCtrlr', function($http,$scope,$location,sessionService,mySocket,$localStorage){

	$scope.login = function(){

			sendData = JSON.stringify({"username" : $scope.username , "password" : $scope.password});
			link = "/restAPI/api/Users/login.php";

			$http.post(link,sendData).then(function(response){
				if(response.data.error){
					$scope.errorlog = response.data.error;
				}else{
					sessionService.set('client_id',response.data.session);
					sessionService.set('client_name',response.data.name);
					$localStorage.loggedClient = true;
					$location.path("/home");
				}
			}).catch(function(response) {
			  	console.log("ERROR: "+response);
			});

		};
});

ongame.controller('regCtrlr', function($http,$scope,$location,$timeout){
	
	$scope.regStudent = function(){
		sendData = JSON.stringify({"student_id" : $scope.regStudID , "username" : $scope.regUsername , "password" : $scope.regPassword , "confirm_pw" :$scope.regRePassword});
		link = "/restAPI/api/Users/register.php";

		$http.post(link,sendData).then(function(response){
			if(response.data.success){
				$scope.succReg = true;
				$scope.errReg = false;
				$timeout(function(){
					$location.path("/login");
				} , 1500);

			}else{
				$scope.errReg = response.data.message;
			}
		}).catch(function(response){
			console.log(response);
		});

	};

});

ongame.controller('homeCtrlr', function($scope , $location, $routeParams, sessionService , mySocket , $rootScope , $http , $localStorage){

	if(sessionService.get('room_joined')){
		mySocket.disconnect();
		sessionService.destroy('room_joined');
	}

	mySocket.connect();

	mySocket.emit('successStudLogin' , {id: sessionService.get('client_id').split('-')[0]});

	//pagka may nakuha syang rooms
	mySocket.emit('requestRooms' , function(rooms){
		$scope.rooms = rooms.filter(obj => {return obj.section ==  sessionService.get('client_id').split('-')[1] });
		console.log($scope.rooms);
	});
	mySocket.on('roomCreated' , function(rooms){
		$scope.rooms = rooms.filter(obj => {return obj.section ==  sessionService.get('client_id').split('-')[1] });
	});

	//pag gusto ng sumali ng room
	$scope.joinRoom = function(room_id){

		mySocket.emit('joinRoom' , 
		{room_id: room_id , nickname: sessionService.get('client_name') ,
		 id:  sessionService.get('client_id').split('-')[0] } , function(score){
			
			$rootScope.mynickname = sessionService.get('client_name');

				sessionService.set('room_joined' , room_id);
				mySocket.emit('requestQuizPaper' , room_id);
				$rootScope.score = score;
			
		});
	};

	$scope.LogOut = function(){

		sessionService.destroy('client_id');
		sessionService.destroy('client_name');
		$localStorage.loggedClient = false;
		$location.path("/login");
		
	};

	mySocket.on('quizReceived' , function(quizpaper , gamemode , gamestatus , quiz_token){
        
        console.log("received");

		console.log(quizpaper);
		console.log(gamemode);
		console.log(gamestatus);
		console.log(quiz_token);
		
		$rootScope.myParts = quizpaper;
		$rootScope.mySettings = gamemode;
		$rootScope.quiz_token = quiz_token;

		if(gamemode.combo){
			$rootScope.streak = 0;
		}

		//kapag di pag nagstastart
		if(gamestatus.show == 0 || gamestatus.show == 2){
			$location.path("/waitingStart");
		}

		//kapag start na tapos nasa questions
		else if(gamestatus.show == 1){
			mySocket.emit('requestQuizPartandQuestion' , sessionService.get('room_joined'));
		}else if(gamestatus.show == 3){
			$location.path("/discuss");
		}else if(gamestatus.show == 4){
			$location.path("/stare");
		}


		mySocket.on('newQuestion' , function(questionData){
			
			//dito na paghihiwalay hiwalayin pre
			$rootScope.currentQ = questionData.current_question;
			$rootScope.currentP = questionData.current_part;
			$rootScope.part = $rootScope.myParts[questionData.current_part];
			$rootScope.speed = 0;
			$rootScope.state = false;

			//multiple
			if($rootScope.myParts[questionData.current_part].type_id == '1'){
				$location.path("/mQuestion");
			}

			//true
			if($rootScope.myParts[questionData.current_part].type_id == '2'){
				$location.path("/tQuestion");
			}

			//arrange
			if($rootScope.myParts[questionData.current_part].type_id == '3'){
				$location.path("/aQuestion");
			}

			//guess
			if($rootScope.myParts[questionData.current_part].type_id == '4'){
				$location.path("/gQuestion");
			}

		});

	});

});

// ongame.controller('choseNicknameCtrlr', function($scope , $routeParams , $location , mySocket , sessionService , $rootScope){
	
// 	$scope.joinRoom = function(){
// 		mySocket.emit('joinRoom' , {room_id:$routeParams.roomId , nickname: $scope.nickname , id:  sessionService.get('client_id').split('-')[0] } , function(nicknamegood){
			
// 			$rootScope.mynickname = $scope.nickname;
			
// 			if(nicknamegood){
// 				sessionService.set('room_joined' , $routeParams.roomId);
// 				mySocket.emit('requestQuizPaper' , $routeParams.roomId );
// 			}else{
// 				$scope.nicknameError = true;
// 			}
// 		});
// 	};

// 	mySocket.on('quizReceived' , function(quizpaper , gamemode , gamestatus , quiz_token){

// 		$rootScope.myParts = quizpaper;
// 		$rootScope.mySettings = gamemode;
// 		$rootScope.score = 0;
// 		$rootScope.quiz_token = quiz_token;

// 		if(gamemode.combo){
// 			$rootScope.streak = 0;
// 			alert("asdasdasdasdasdasd");
// 		}

// 		//kapag di pag nagstastart
// 		if(gamestatus.show == 0 || gamestatus.show == 2){
// 			$location.path("/waitingStart");
// 		}

// 		//kapag start na tapos nasa questions
// 		else if(gamestatus.show == 1){
// 			mySocket.emit('requestQuizPartandQuestion' , $routeParams.roomId );
// 		}else if(gamestatus.show == 3){
// 			$location.path("/discuss");
// 		}else if(gamestatus.show == 4){
// 			$location.path("/stare");
// 		}


// 		mySocket.on('newQuestion' , function(questionData){
			
// 			//dito na paghihiwalay hiwalayin pre
// 			$rootScope.currentQ = questionData.current_question;
// 			$rootScope.currentP = questionData.current_part;
// 			$rootScope.part = $rootScope.myParts[questionData.current_part];
// 			$rootScope.speed = 0;
// 			$rootScope.state = false;

// 			//multiple
// 			if($rootScope.myParts[questionData.current_part].type_id == '1'){
// 				$location.path("/mQuestion");
// 			}

// 			//true
// 			if($rootScope.myParts[questionData.current_part].type_id == '2'){
// 				$location.path("/tQuestion");
// 			}

// 			//arrange
// 			if($rootScope.myParts[questionData.current_part].type_id == '3'){
// 				$location.path("/aQuestion");
// 			}

// 			//guess
// 			if($rootScope.myParts[questionData.current_part].type_id == '4'){
// 				$location.path("/gQuestion");
// 			}

// 		});


// 	});
	
// });


ongame.controller('waitingStartCtrlr', function(mySocket , $scope , $location , sessionService){
	//pagka hindi late

	mySocket.on('quizStarted' , function(){
		$location.path("/countdown");
	});

	mySocket.on('discussFriend', function(){
		$location.path("/discuss");
	});

	mySocket.on('stareFriend' , function(){
		$location.path("/stare");
	});

	mySocket.on('hostDc' , function(){
		$location.path("/home");
	});

	mySocket.on('kicked' , function(){

		mySocket.disconnect();
		sessionService.destroy('room_joined');
		$location.path("/home");

	});

});

ongame.controller('countdownCtrlr', function($scope , $rootScope , mySocket , $location , $interval){

	$rootScope.countScope = 5;

	partInt = $interval(function(){
		$rootScope.countScope -= 1;
		if($rootScope.countScope == 0){
			$rootScope.countScope = "GO!";
			$interval.cancel(partInt); 
		}
	},1000);

	mySocket.on('quizStarted' , function(){
		$location.path("/countdown");
	});

	mySocket.on('discussFriend', function(){
		$location.path("/discuss");
	});

	mySocket.on('stareFriend' , function(){
		$location.path("/stare");
	});

	mySocket.on('hostDc' , function(){
		$location.path("/home");
	});

	//when the socket receives a question
	mySocket.on('newQuestion' , function(questionData){
		
		//dito na paghihiwalay hiwalayin pre
		$rootScope.currentQ = questionData.current_question;
		$rootScope.currentP = questionData.current_part;
		$rootScope.speed = 0;
		$rootScope.state = false;

		//multiple
		if($rootScope.myParts[questionData.current_part].type_id == '1'){
			$location.path("/mQuestion");
		}

		//true
		if($rootScope.myParts[questionData.current_part].type_id == '2'){
			$location.path("/tQuestion");
		}

		//arrange
		if($rootScope.myParts[questionData.current_part].type_id == '3'){
			$location.path("/aQuestion");
		}

		//guess
		if($rootScope.myParts[questionData.current_part].type_id == '4'){
			$location.path("/gQuestion");
		}

	});

});


ongame.controller('QuestionCtrlr', function($scope,$rootScope,$location,mySocket,sessionService , $http){

	mySocket.on('hostDc' , function(){
		$location.path("/home");
	});

	mySocket.on('quizStarted' , function(){
		$location.path("/countdown");
	});

	mySocket.on('discussFriend', function(){
		$location.path("/discuss");
	});

	mySocket.on('stareFriend' , function(){
		$location.path("/stare");
	});

	$scope.question = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].question;
	$scope.answer = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].rightAnswer;

	function shuffle(array) {

	  var currentIndex = array.length, temporaryValue, randomIndex;
	  var choicefuck = ['a','b','c','d'];
	  var tempValue2 ;
	  $rootScope.arrangeAnswer = "";
	  while (0 !== currentIndex) {

	    randomIndex = Math.floor(Math.random() * currentIndex);

	    currentIndex -= 1;
	    
	    temporaryValue = array[currentIndex];
	    array[currentIndex] = array[randomIndex];
	    array[randomIndex] = temporaryValue;

	    tempValue2 = choicefuck[currentIndex];
	  	choicefuck[currentIndex] = choicefuck[randomIndex];
	    choicefuck[randomIndex] = tempValue2;
	  }

	  for (var i = 0; i <= choicefuck.length-1; i++) {
	  	$rootScope.arrangeAnswer += choicefuck[i];
	  }

	  console.log($rootScope.arrangeAnswer);

	  return array;
	}

	if($rootScope.myParts[$rootScope.currentP].type_id == '1'){		
		if($rootScope.mySettings.randC){
			var choicebank = [];
			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice1);
			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice2);
			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice3);
			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice4);
			choicebank = shuffle(choicebank);
			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice1 = choicebank[0];
			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice2 = choicebank[1];
			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice3 = choicebank[2];
			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice4 = choicebank[3];

		}
	}

	if($rootScope.myParts[$rootScope.currentP].type_id == '3'){
		var choicebank = [];
		var newChoice = [];
		choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice1);
		choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice2);
		choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice3);
		choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice4);

		newChoice = shuffle(choicebank);

		$scope.arrange1 = newChoice[0];
		$scope.arrange2 = newChoice[1];
		$scope.arrange3 = newChoice[2];
		$scope.arrange4 = newChoice[3];
	}

	$scope.choices = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ];

	//pang oras lang sa magsasagot
	var startTime = Date.now();
	$scope.calculateAnsweringtime = function(){
	    var elapsedTime = Date.now() - startTime;
    	return (elapsedTime / 1000).toFixed(3);	
	}

	$scope.guessAnswer = "";

	$scope.fillAnswer = function(answer){
		$scope.accumulatedPoint = 0;
		$rootScope.answer = answer;
		$rootScope.speed = $scope.calculateAnsweringtime();

		if(!$rootScope.mySettings.caseSensitive && $rootScope.myParts[$rootScope.currentP].type_id == '4'){
			$scope.answer = $scope.answer.toLowerCase();
			answer = answer.toLowerCase();
		}

		if(!$rootScope.mySettings.caseSensitive && $rootScope.myParts[$rootScope.currentP].type_id == '3'){
			$scope.answer = $rootScope.arrangeAnswer;
			answer = answer.toLowerCase();
		}

		if($scope.answer == answer){
			$rootScope.state = true;
		}else{
			$rootScope.state = false;
		}

		//compute mo yung score nya base sa game settings
		if($rootScope.state == true){
			//pagka combo yung mode ng game
			$scope.accumulatedPoint += 1;
			if($rootScope.mySettings.combo){
				$rootScope.streak += 1;
                if($rootScope.streak>1){

                    $scope.accumulatedPoint += $rootScope.streak-1;
                }
			}
		}else{ //pagka naka combo tapos namali
			if($rootScope.mySettings.combo){
				$rootScope.streak = 0;
			}
		}

		mySocket.emit("studentAnswered" , {
			roomId: sessionService.get('room_joined') , 
			correct: $rootScope.state , 
			userId: sessionService.get('client_id').split('-')[0] , 
			duration: $rootScope.speed,
			status: $rootScope.state,
			point: $scope.accumulatedPoint });

		$location.path("/wait");
	};

	mySocket.once('questionEnded', function(clients){

		if($rootScope.answer == null){ $rootScope.answer = "NONE"; }

		sendData = JSON.stringify({
	        "answer" : $rootScope.answer , 
	        "question_id" : $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].question_id , 
	        "user_id" : localStorage.getItem('client_id') , 
	        "status": $rootScope.state ,
	        "student_section_id" : sessionService.get('client_id').split('-')[1], 
	        "time" : $rootScope.speed,
	        "quiz_id" : $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].quiz_id,
	    	"room_id" : sessionService.get('room_joined') ,
	    	"quiz_token" : $rootScope.quiz_token});

		$http.post('/restAPI/api/Quizzes/record_answer.php' , sendData);

		$rootScope.place = clients.findIndex(x => x.id === sessionService.get('client_id').split('-')[0])+1;
		$rootScope.score = clients[clients.findIndex(x => x.id === sessionService.get('client_id').split('-')[0])].points;

		$location.path("/result");
	});
	
	mySocket.on('quizEnded' , function(){
		$location.path("/feedback");
	});

});

ongame.controller('feedbackCtrlr', function($http , $location , $scope , $rootScope , sessionService , mySocket){

	mySocket.on('quizEnded' , function(){
		$location.path("/feedback");
	});

	$scope.sendFeedBack = function(rate){
		$scope.rated = true;

		sendData = JSON.stringify({
			"feedback_score": rate , 
			"user_id" : sessionService.get('client_id').split('-')[0], 
			"section_id" : sessionService.get('client_id').split('-')[1] , 
			"quiz_id":  $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].quiz_id
		});

		$http.post('/restAPI/api/Quizzes/submit_feedback.php' , sendData);

		$scope.goHome = function(){
			$location.path("/home");
			mySocket.emit('clientDoneQuiz' , sessionService.get('client_id').split('-')[0]);
		};
	}
});

// ongame.controller('NicknameCtrlr', function($scope , mySocket , $rootScope , $location , $routeParams , sessionService){
	
// 	$scope.joinRoomFinal = function(){
// 		mySocket.emit('clientG', {nickname : $scope.nickName , roomid : $routeParams.roomId , id: $rootScope.myUserId});
// 	}

// 	mySocket.on('goodNickname',function(quizpaper , settings , late){

// 		sessionService.set('room_joined' , sessionService.get('chosenRoom'));
// 		$rootScope.myParts = quizpaper;
// 		$rootScope.mySettings = settings;
// 		if(late){
// 			$location.path('/latecomer');
// 		}else{
// 			$location.path('/waiting');
// 		}
// 	});

// 	mySocket.on('badNickname',function(error){
// 		$scope.errorNickname = error;
// 	});

// 	mySocket.on('adminReload' , function(){
// 		alert("admin interupted the quiz");
// 		$location.path("/home");
// 	});

// });

// ongame.controller('waitingCtrlr', function(mySocket , $location , $rootScope){
// 	//pag nagstart na ng quiz pre
// 	mySocket.on('startQuizClient',function(parts){
// 		$location.path('/countdown');
// 	});

// 	mySocket.on('adminReload' , function(){
// 		alert("admin interupted the quiz");
// 		$location.path("/home");
// 	});

// 	//pagnakatanngap ng question
// 	mySocket.on('question' , function(question){
// 		//dito na paghihiwalay hiwalayin pre
// 		$rootScope.currentQ = question.currQ;
// 		$rootScope.currentP = question.currP;
// 		$rootScope.speed = 0;
// 		$rootScope.state = false;

// 		//multiple
// 		if($rootScope.myParts[question.currP].type_id == '1'){
// 			$location.path("/mQuestion");
// 		}

// 		//true
// 		if($rootScope.myParts[question.currP].type_id == '2'){
// 			$location.path("/tQuestion");
// 		}

// 		//arrange
// 		if($rootScope.myParts[question.currP].type_id == '3'){
// 			$location.path("/aQuestion");
// 		}

// 		//guess
// 		if($rootScope.myParts[question.currP].type_id == '4'){
// 			$location.path("/gQuestion");
// 		}
// 	});	
// });

// ongame.controller('QuestionCtrlr', function($scope,$rootScope,$location,mySocket,sessionService , $http){

// 	mySocket.on('adminReload' , function(){
// 		alert("admin interupted the quiz");
// 		$location.path("/home");
// 	});

// 	$scope.question = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].question;
// 	$scope.answer = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].rightAnswer;

// 	function shuffle(array) {
// 	  var currentIndex = array.length, temporaryValue, randomIndex;

// 	  // While there remain elements to shuffle...
// 	  while (0 !== currentIndex) {

// 	    // Pick a remaining element...
// 	    randomIndex = Math.floor(Math.random() * currentIndex);
// 	    currentIndex -= 1;

// 	    // And swap it with the current element.
// 	    temporaryValue = array[currentIndex];
// 	    array[currentIndex] = array[randomIndex];
// 	    array[randomIndex] = temporaryValue;
// 	  }

// 	  return array;
// 	}

// 	if($rootScope.myParts[$rootScope.currentP].type_id == '1'){
		
// 		if($rootScope.mySettings.randC){

// 			var choicebank = [];

// 			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice1);
// 			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice2);
// 			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice3);
// 			choicebank.push($rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice4);

// 			choicebank = shuffle(choicebank);

// 			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice1 = choicebank[0];
// 			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice2 = choicebank[1];
// 			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice3 = choicebank[2];
// 			$rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].choice4 = choicebank[3];

// 		}
// 	}

// 	$scope.choices = $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ];

// 	//pang oras lang sa magsasagot
// 	var startTime = Date.now();
// 	$scope.calculateAnsweringtime = function(){
// 	    var elapsedTime = Date.now() - startTime;
//     	return (elapsedTime / 1000).toFixed(3);	
// 	}

// 	$scope.guessAnswer = "";

// 	$scope.fillAnswer = function(answer){
// 		$rootScope.answer = answer;
// 		$rootScope.speed = $scope.calculateAnsweringtime();

// 		if($scope.answer == answer){
// 			$rootScope.state = true;
// 		}else{
// 			$rootScope.state = false;
// 		}

// 		mySocket.emit("iFinished" , {roomId: sessionService.get('room_joined') , correct: $rootScope.state , userId:$rootScope.myUserId , duration: $rootScope.speed , mode: $rootScope.mySettings });

// 		$location.path("/wait");
// 	};

// 	mySocket.once('endquestion' , function(players){
		
// 		sendData = JSON.stringify({
//         "answer" : $rootScope.answer , 
//         "question_id" : $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].question_id , 
//         "user_id" : localStorage.getItem('client_id') , 
//         "status": $rootScope.state ,
//         "student_section_id" : sessionService.get('client_id').split('-')[1], 
//         "time" : $rootScope.speed,
//         "quiz_id" : $rootScope.myParts[$rootScope.currentP][0][$rootScope.currentQ].quiz_id,
//     	"room_id" : sessionService.get('room_joined') });

// 		$http.post('/restAPI/api/Quizzes/record_answer.php' , sendData);

// 		var sortedStudents = players.sort(function(a, b) {
//     		return b.score - a.score;
// 		});

// 		for(var i = 0; i < sortedStudents.length; i += 1) {
// 	        if(sortedStudents[i]['id'] == sessionService.get('client_id').split('-')[0]) {
// 	            $rootScope.myPlace = i+1;
// 	            $rootScope.myScore = sortedStudents[i]['score'];
// 	        }
//     	}

// 		$location.path('/result');

	
// 	});

// 	mySocket.once('taposnatayo' , function(){
// 		//DIDISCONNECT KA NGAYON SA ROOM PRE
// 		mySocket.disconnect();
// 		$location.path("/feedback");
// 	});


// });

// ongame.controller('lateCtrlr', function(mySocket , $location ){
	
// 	mySocket.on('question' , function(question){
// 		//dito na paghihiwalay hiwalayin pre
// 		$rootScope.currentQ = question.currQ;
// 		$rootScope.currentP = question.currP;
// 		$rootScope.speed = 0;
// 		$rootScope.state = false;

// 		//multiple
// 		if($rootScope.myParts[question.currP].type_id == '1'){
// 			$location.path("/mQuestion");
// 		}

// 		//true
// 		if($rootScope.myParts[question.currP].type_id == '2'){
// 			$location.path("/tQuestion");
// 		}

// 		//arrange
// 		if($rootScope.myParts[question.currP].type_id == '3'){
// 			$location.path("/aQuestion");
// 		}

// 		//guess
// 		if($rootScope.myParts[question.currP].type_id == '4'){
// 			$location.path("/gQuestion");
// 		}
// 	});	

// 	mySocket.once('taposnatayo' , function(){
// 		//DIDISCONNECT KA NGAYON SA ROOM PRE
// 		mySocket.disconnect();
// 		$location.path("/feedback");
// 	});
// });
