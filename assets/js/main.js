$(document).ready(function () {
  var connection = new WebSocket("ws://localhost:8080");
  connection.onopen = (e) => {
    console.log("Connection established!");
  };

  connection.onmessage = function (e) {
    // console.log(e.data, "here i am");

    const data = JSON.parse(e.data);
    let rowClass = "";
    let backgroundClass = "";

    if (data.from === "Me") {
      rowClass = "row justify-content-start";
      backgroundClass = "text-dark alert-light";
    } else {
      rowClass = "row justify-content-end";
      backgroundClass = "alert-success";
    }

    const html = `<div class='${rowClass}'>
            <div class='col-sm-10'>
              <div class='shadow-sm alert ${backgroundClass}'>
                <b>${data.from} - </b>${data.message} <br />
                <div class='text-right'>
                    <small><i>${data.created_at}</i> </small>
                </div>
              </div>
            </div>
      </div>`;
    $("#messages_area").append(html);
  };

  $("#chat-form").parsley();

  $("#chat-form").on("submit", (e) => {
    e.preventDefault();

    if ($("#chat-form").parsley().isValid()) {
      const userId = $("#user-id").val();
      const message = $("#message").val();

      const data = {
        user_id: userId,
        message: message,
      };

      connection.send(JSON.stringify(data));
      $("#message").val("");
      $("#messages_area").scrollTop($("#messages_area")[0].scrollHeight);
    }
  });
});
