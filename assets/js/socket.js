$(document).ready(function () {
  var connection = new WebSocket("ws://localhost:8080");
  connection.onopen = (e) => {
    console.log("here i am");
    console.log("Connection established!");
  };

  connection.onmessage = function (e) {
    console.log(e.data);
  };
});
