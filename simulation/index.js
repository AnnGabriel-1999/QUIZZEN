//SERVER SIDE
var express = require('express');
var app = express();
var port = 4000;

var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(port, function(){
    console.log("listening on *: " + port);
});

var users = [];
var admins = [];
var rooms = [];

io.on('connection', function(socket){

//** START STUDENT SOCKET EVENTS **//

    //Emits when user successful login
    socket.on('successStudLogin' , function(studData){
        
        //pagka may record na update mo nalang yung socket.id nya pag wala record ka bago
        if(users.findIndex(x => x.id === studData.id) < 0){
            studData.socketid = socket.id;
            users.push(studData);
            console.log("Student "+studData.id+" Connected.");
        }else if(users.findIndex(x => x.id === studData.id) >= 0){
            users[users.findIndex(x => x.id === studData.id)].socketid = socket.id;
        }
    });

    socket.on('requestRooms', function(callback){
        callback(rooms);
    });

    socket.on('joinRoom' , function( studData , callback){
        //if good nickname
        roomClients = users.filter(obj => {return obj.room_joined == studData.room_id});

        if(roomClients.findIndex(x => x.nickname === studData.nickname) < 0){

            users[users.findIndex(x => x.id === studData.id)].nickname = studData.nickname;
            users[users.findIndex(x => x.id === studData.id)].room_joined = studData.room_id;
            socket.join(studData.room_id);

            //sasabihin ngayon kay admin sa room na kumonek sya
            io.to(admins[admins.findIndex(x => x.room_hosted === studData.room_id)].socketid).emit('studentJoined' , users.filter(obj => {return obj.room_joined == studData.room_id }));
            callback(true);
        }else{
            callback(false);
            console.log("sorry");
        }
    });

    socket.on('requestQuizPaper' , function(room_id){
        //speak to the host of the quiz
       io.to(admins[admins.findIndex(x => x.room_hosted === room_id)].socketid).emit('requestQuizPaper' , socket.id);
    });

    //pagka late pumasok magtatanong sa host kung anong part at tanong na sila
    socket.on('requestQuizPartandQuestion' , function(room_id){
        io.to(admins[admins.findIndex(x => x.room_hosted === room_id)].socketid).emit('requestQuizPnQ' , socket.id);
    });

    socket.on('studentAnswered' , function(student_data , callback){
        //lalagayan ng score yung studyante
        users[users.findIndex(x => x.id === student_data.userId)].points = student_data.point;
        users[users.findIndex(x => x.id === student_data.userId)].status = student_data.status;
        users[users.findIndex(x => x.id === student_data.userId)].done = true;

        //check if tapos na lahat ng client
        MyClients = users.filter(obj => {return obj.room_joined == student_data.roomId});
        DoneClients =  MyClients.filter(obj => {return obj.done == true });

        //shout to host that you answered
        io.to(admins[admins.findIndex(x => x.room_hosted === student_data.roomId)].socketid).emit('studentAnswered' , DoneClients.length);

        MyClients = MyClients.sort(function(a, b) {return b.points - a.points;});
        
        if(MyClients.length == DoneClients.length){
            io.to(admins[admins.findIndex(x => x.room_hosted === student_data.roomId)].socketid).emit('allFinished' , true);
        }
    });

    socket.on('clientDoneQuiz' , function(userId){
        //clear students records
        users[users.findIndex(x => x.id === userId)].room_joined = null;
        users[users.findIndex(x => x.id === userId)].points = null;
        users[users.findIndex(x => x.id === userId)].done = null;
        users[users.findIndex(x => x.id === userId)].nickname = null;
    });
//** END STUDENT SOCKET EVENTS **//


//** START ADMIN SOCKET EVENTS **//
    socket.on('adminCreateRoom' , function(adminData){


        // adminRecord = {id: adminData.admin_id , room_hosted: adminData.room_id, socketid: socket.id};
        //     console.log("Admin "+adminRecord.id+" Connected.");
        //     admins.push(adminRecord);

        if(admins.findIndex(x => x.admin_id === adminData.admin_id) < 0){
            adminRecord = {id: adminData.admin_id , room_hosted: adminData.room_id, socketid: socket.id};
            console.log("Admin "+adminRecord.id+" Connected.");
            admins.push(adminRecord);
        }else if(users.findIndex(x => x.admin_id === adminData.admin_id) >= 0){
            admins[admins.findIndex(x => x.admin_id === adminData.admin_id)].socketid = socket.id;
        }

        if(rooms.findIndex(x => x.id === adminData.room_id) < 0){
            roomRecord = {id: adminData.room_id , title: adminData.quiz_title , section: adminData.section};
            socket.join(adminData.room_id);
            console.log("A room named "+adminData.quiz_title+" was created.");
            rooms.push(roomRecord);
            socket.broadcast.emit('roomCreated', rooms);
        }

    });

    socket.on('giveQuizPaper' , function(socketid , quizpaper , game_mode, game_status , quiz_token){
        io.to(socketid).emit('quizReceived', quizpaper , game_mode , game_status , quiz_token);
    });

    socket.on('startQuiz' , function(room_id){
        socket.to(room_id).emit('quizStarted', "Hi folk the quiz has just started");
    });

    socket.on('giveQuestion' , function(questionData){
        socket.to(questionData.room_id).emit('newQuestion', questionData);

        //refresh mo yung done attrib ng bawat isa.
        for(var i = 0; i <= users.length-1; i++){
            if(users[i].room_joined == questionData.room_id){
                users[i].done = false;
                users[i].status = false;
            }
        }

    });

    socket.on('endQuestion' , function(room_id , callback){
        clients = users.filter(obj => {return obj.room_joined == room_id} ).sort(function(a, b) {return b.points - a.points;});
        callback(clients);

        //shouts to all clients that the question is ended
        socket.to(room_id).emit('questionEnded', clients);
    });

    //bibigyan nya yung tanong na currently pinapasagutan pre
    socket.on('giveDirectionLate' , function(lateData){
        io.to(lateData.lateid).emit('newQuestion', {current_question: lateData.current_question , current_part: lateData.current_part });
    });

    socket.on('StareQuestion' , function(room_id){
        socket.to(room_id).emit('stareFriend', true);
    });

    socket.on('Discuss' , function(room_id){
        socket.to(room_id).emit('discussFriend', true);
    });

    socket.on('endQuiz' , function(room_id){
        
        //says to all sockets that room is now gone coz quiz done
        socket.broadcast.emit('roomCreated', rooms);

        //shout to all clients that the quiz is ended
        socket.to(room_id).emit('quizEnded', true); 

        //remove the room from the rooms array
        rooms.splice( rooms.findIndex(x => x.id === room_id) , 1);

        dcHostClients = users.filter(obj => {return obj.room_joined == room_id});
        for(var i=0; i<=dcHostClients.length-1; i++){
            dcHostClients[i].room_joined = null;
            dcHostClients[i].points = null;
            dcHostClients[i].done = null;
            dcHostClients[i].nickname = null;
        }

        console.log(users);

        console.log("Quiz on room "+room_id+" ended.");
    });

    socket.on('kickClient' , function(stud_id){
        io.to(users[users.findIndex(x => x.id === stud_id)].socketid).emit('kicked' , true);
    });
//** END ADMIN SOCKET EVENTS **//


//** DISCONNECT EVENTS **//

    //single disconnect event check now if admin or user
    socket.on('disconnect' , function(){

        if(users.findIndex(x => x.socketid === socket.id) > -1){

            disconnectedStudent = users[users.findIndex(x => x.socketid === socket.id)];
            //pagka kasali sya sa room
            if(disconnectedStudent.room_joined != null){
                socket.to(admins[admins.findIndex(x => x.room_hosted === disconnectedStudent.room_joined)].socketid).emit('LeftRoom' , disconnectedStudent.nickname);
                disconnectedStudent.nickname = null;
                disconnectedStudent.points = null;
                disconnectedStudent.done = null;
                disconnectedStudent.nickname = null;
                disconnectedStudent.room_joined = null;
                console.log("cleared");            
            }

            console.log("Student " + disconnectedStudent.id + " disconnected.");

        }else if(admins.findIndex(x => x.socketid === socket.id) > -1){
            
            //gets the dc host object
            dcHost = admins[admins.findIndex(x => x.socketid === socket.id)];
            //removes the room handled by that prof
            rooms.splice( rooms.findIndex(x => x.id === dcHost.room_hosted) , 1);
            admins.splice( admins.findIndex(x => x.id === dcHost.room_hosted) , 1);

            //lilinisin yung record nung mga studyanteng hawak nya
            dcHostClients = users.filter(obj => {return obj.room_joined == dcHost.room_hosted});
            for(var i=0; i<=dcHostClients.length-1; i++){
                dcHostClients[i].room_joined = null;
                dcHostClients[i].points = null;
                dcHostClients[i].done = null;
                dcHostClients[i].nickname = null;
            }

            socket.to(dcHost.room_hosted).emit('hostDc' , true);

            //update
            socket.broadcast.emit('roomCreated', rooms);
            console.log("Professor " + dcHost.id + " disconnected.");

        }

    });


});