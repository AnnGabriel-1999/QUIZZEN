ongame.factory('mySocket', function (socketFactory) {
  return socketFactory({
    ioSocket: io.connect('http://localhost:8080')
  });
})