//SERVER SIDE
var express = require('express');
var app = express();
var port = 4000;

var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(port, function(){
    console.log("listening on *: " + port);
});

var nicknames = [];
var rooms = [];

io.sockets.on('connection', function(socket){

    //para malaman ni server na may pumasok na host sa kanya
    socket.on('hostEnt',function(quizDetails){

        //check yung array kung meron na doon tapos kung wala pa isave
        var found = false;
        for (var i = 0; i <= rooms.length - 1; i++) {
            if(rooms[i].name == quizDetails.name){
                found = true;
            }
        }
        //pag di nakita sa array ipasok mo na
        if(!found){
            rooms.push(quizDetails);
            nicknames.push({name: quizDetails.name , nickname : [] , finished : 0});
        }

        //sasabihin sa lahat ng socket na may bagong quiz.
        socket.broadcast.emit('rooms', rooms);

        //gawa ng room
        if(socket.join(quizDetails.name)){
            console.log("HOST  >>> Host " + socket.id + "created a room named " +quizDetails.name);
        }
    });

    //para malaman ni server na may client na pumasok sa kanya}
    socket.on('clEnt',function(clN){
    	console.log("CLIENT >>> Client named " + clN + " entered.");

        //sesend ko sa bagong pasok na client yung array nung room
        io.to(socket.id).emit('rooms', rooms);
    });

    //pagka nagsend na ng nickname si user
    socket.on('clientG',function(credentials){

    	//tetest kung may ganon ng nickname
        var foundRec = false;
        var foundN = false;
        var foundIndex;
        var foundUndex;

        for (var k = 0; k <= nicknames.length-1; k++) {
            if(nicknames[k].name == credentials.roomid){
                foundIndex = k;
                for (var n = 0; n <= nicknames[k].nickname.length-1; n++) {
                    if(nicknames[k].nickname[n].nick == credentials.nickname){
                        foundN = true;
                    }

                    if(nicknames[k].nickname[n].id == credentials.id){
                        foundRec = true;
                        foundUndex = n;
                    }
                }
            }
        }

        if(!foundN){
	       
           //pag may record
           if(foundRec){
                nicknames[foundIndex].nickname[foundUndex].nick = credentials.nickname;
                nicknames[foundIndex].nickname[foundUndex].active = true;
           }else{
                nicknames[foundIndex].nickname.push({nick: credentials.nickname, id: credentials.id, state: false , score: 0 , duration: 0 , streak: 0 , active: true});
           }

            //sasali sya ng room
            socket.join(credentials.roomid);

            //bibigay sa student yung quiz paper nya
    		var quizpaper;
    		var started;
    		var currentp;
    		var currentq;
            var settings;
            var where;

            for (var g = 0; g <= rooms.length-1; g++) {
            	if(rooms[g].name == credentials.roomid){
            		quizpaper = rooms[g].part_assets;
            		started = rooms[g].started;
            		currentp = rooms[g].partnow;
            		currentq = rooms[g].quesnow;
                    settings = rooms[g].game_mode;
                    where = rooms[g].where;
            	}
            }

            //pagka nagsimula na pero nasa questions
            if(started && where == 'NQ'){

                console.log("dito ako");

                socket.emit('goodNickname', quizpaper , settings);
                socket.emit('question', {roomId : credentials.roomid, currP: currentp , currQ : currentq});

            }else if(started && where == 'EQ'){ // pang nagsimula na pero nasa podium
                socket.emit('goodNickname', quizpaper , settings , true);
            }else{ // pag di ka late
                
                //sasabihin nya sa host ng room na yon na cummonect sya
                socket.to(credentials.roomid).emit('Iconnected' , credentials.nickname);
                socket.emit('goodNickname', quizpaper , settings , false);
            }
        }else{
            socket.emit('badNickname' , "Im sorry. "+credentials.nickname+" is already taken. ");
        }

    });

    //para makasignal si host na g na sa lahat ng studyante na nasa room nya
    socket.on('startQuiz',function(message){
    	if(socket.to(message.qID).emit('startQuizClient', "start na daw")){

            console.log("HOST >>> Host ordered all clients of " +message.qID+ " to start the quiz.");
            
            //sisignal lang na started na pre
            for(var x=0; x<=rooms.length-1; x++){
            	if(rooms[x].name == message.qID){
            		rooms[x].started = true;
                    rooms[x].where = "SQ";
            	}
            }

    	}
    });


    //ADMIN EVENTS
    socket.on('newQuestion' , function(question){

    	//update ng current question sa room id para sa mga late gara talaga ng mga late e
    	for(var x=0; x<=rooms.length-1; x++){
        	if(rooms[x].name == question.roomId){
        		rooms[x].partnow = question.currP;
        		rooms[x].quesnow = question.currQ;
                rooms[x].where = 'NQ';
        	}
        }

        //reset data ng room
        for (var f = 0; f <= nicknames.length - 1; f++) {
            if(nicknames[f].name == question.roomId){
                nicknames[f].finished = 0;
                for (var i = 0; i < nicknames[f].nickname[i].length; i++) {
                    nicknames[f].nickname[i].state = false;
                }
            }
        }
        socket.to(question.roomId).emit('question', question);
    });

    socket.on('endQuestion' , function(room , callback){
        //pagsabay ng pagsabing tapos na yung tapos kasama yung mga room

        for (var i = 0; i <=nicknames.length-1; i++) {
            if(nicknames[i].name == room){
                callback(nicknames[i].nickname);
                socket.to(room).emit('endquestion', nicknames[i].nickname); //sasabihin ng host sa lahat ng client nya na tapos na
                break;
            }
        }

        for(var x=0; x<=rooms.length-1; x++){
            if(rooms[x].name == room){
                rooms[x].where = 'EQ';
            }
        }
    });

    //CLIENT EVENTS
    socket.on('iFinished' , function(details){
        for (var f = 0; f <= nicknames.length - 1; f++) {
            if(nicknames[f].name == details.roomId){
                //plus 1 sa mga natapos
                nicknames[f].finished +=1;

                //update mo kung tama ba sya o mali
                for(var v=0; v<=nicknames[f].nickname.length-1; v++){
                    if(nicknames[f].nickname[v].id == details.userId){
                        
                        //update yung status ng mga studyante
                        nicknames[f].nickname[v].state = details.correct; //kung tama ba sya o mali
                        nicknames[f].nickname[v].duration = details.duration; // kung gaano katagal sinagutan 

                        //KUNG TAMA KA PRE PLUS ONE KA SAKIN
                        if(details.correct){

                            if(details.mode.fast){
                                //pag sya nauna buong points iuuwi nya!
                                if(nicknames[f].finished == 1){
                                    nicknames[f].nickname[v].score += nicknames[f].nickname.length;
                                }else{
                                    nicknames[f].nickname[v].score += nicknames[f].finished - 1;
                                }
                            }else if(details.mode.combo){
                                nicknames[f].nickname[v].score +=1;
                                nicknames[f].nickname[v].streak +=1;

                                if(nicknames[f].nickname[v].streak>1){
                                    nicknames[f].nickname[v].score += nicknames[f].nickname[v].streak-1;
                                }
                            }else{
                                nicknames[f].nickname[v].score += 1;
                            }
                        }else{
                            nicknames[f].nickname[v].streak = 0;
                        }
                    }
                }

                //tignan kung ilan na active tapos g na
                var activeCounter = 0;
                for(var c=0; c<=nicknames[f].nickname.length-1; c++){
                    if(nicknames[f].nickname[c].active){
                        activeCounter++;
                    }
                }
                console.log("finsh"+nicknames[f].finished);
                console.log("actv"+activeCounter);
                //pagtapos na lahat sasabihin ng huling natapos na studyante kay prof na tapos na sila 
                if(nicknames[f].finished == activeCounter){
                    socket.to(details.roomId).emit('allFinished' , 'hey all clients are finished');
                }

            }
        }
    });

    socket.on('endQuiz' , function(roomid){

        //DEDELETE NYA YUNG MGA ROOM NA GINAWA NYA SAKA YUNG NICKNAMES
        for (var i = 0; i <=nicknames.length-1; i++) {
            if(nicknames[i].name == roomid){
                nicknames.splice(i);
            }
        }

        for(var x=0; x<=rooms.length-1; x++){
            if(rooms[x].name == roomid){
                rooms.splice(x);
            }
        }

        socket.to(roomid).emit('taposnatayo', "tatatata"); //sasabihin ng host sa lahat ng client nya na tapos na
        socket.disconnect();
    });

    socket.on('reloadClient' , function(room , client_id){
       for (var i = 0; i <= nicknames.length-1; i++) {

           if(nicknames[i].name == room){
                //gagawin lang na false yung active nya para di na sya pagpasehan sa pagtapos ng quiz
                for (var j = 0; j <= nicknames[i].nickname.length-1; j++) {
                    if(nicknames[i].nickname[j].id == client_id){
                        nicknames[i].nickname[j].active = false;
                    }
                }
           }
       }

    });

    socket.on('reloadAdmin' , function(room){
        socket.to(room).emit('adminReload', "aaaaaaaa");
    });

});