
$(document).ready(function(){
    //code here
    var token = localStorage.getItem('token');
    var role = localStorage.getItem('role');
    var team = localStorage.getItem('team');
    // console.log('Team',team);
    
    $('#team').click(function(){

        if(team == "false"){
            location.href = "TeamDefault.html";
        }else{
            location.href = "Team.html";
        }
      
    })
    if($('#team-page').length) {
          
          var token = localStorage.getItem("token");

          if(role == 3) {
            $('#edit-btn').show();
        } else {
            $('#edit-btn').hide();
        }
    
          $.ajax({
            url: "../teams.php?id=" + team,
            type: "GET",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (team) {
                console.log(team)
              
              if (team) {
                $('#pfp').find('img').attr('src', team.image)
                $('#team-name').text(team.name)
                $('#region').text(team.region)
                $('#text').text(team.description)
                $('#created').text(team.created)
                $('#createdby').text(team.cutag)
              }
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
    }
    if($('#game-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

        function gameItem(g, i) {
          var r = `  <div
          style="
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            margin-top: 5rem;
          "
        >`;
          var item = `<div class="game" style="display: flex; flex-direction: column">
            <img class="source" src="${g.image}" />
            <span
              style="
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 1.8rem;
                text-align: center;
                color: #c5c6c8;
              "
              >${g.name}</span
            >
          </div>`;
          if ((i + 1) % 4 === 0 || i === 0) {
            var closeDiv = i !== 0 ? "</div>" : "";
            return closeDiv + r + item;
          }

          return item;
        }

        var token = localStorage.getItem("token");
        console.log(token);
        $.ajax({
          url: "../games.php",
          type: "POST",
          data: JSON.stringify({ list: true }),
          headers: {
            "content-type": "application/json",
            Authorization: "Bearer " + token,
          },
          success: function (games) {
            if (games && games.length) {
              var list = games
                .map((g, i) => {
                  return gameItem(g, i);
                })
                .join("");
              $("#games-list").empty().append(list);
            }
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
        return false;
    }
    if($('#event-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
      if(role <= 2) {
        $('#host-btn').show();
        $('#manage-events').show();
    } else {
      $('#manage-events').hide();
        $('#host-btn').hide();
    }
   
      function eventItem(e, i) {
        var item = ` <a class="event-click" data-id="${e.event_id}" data-lastdate="${e.last_date_of_registration}" href="#" id="event-banner-${i}"><div class="event">
        <div style="width: 45rem; height: 5rem"></div>
        <div
          class="eventxt"
          style="
            background-image: url(${e.image});
            background-repeat: no-repeat;
            background-size: cover;
          "
        >
          <span style="display: inline; font-size: 1.8rem">${e.event_name}</span>
          
          <span style="display: block; font-size: 1.5rem; margin-left: 3rem"
            >${e.event_start}</span
          >
        </div>
      </div> </a>`;
      return item;
    }
    $(document).on('click','.event-click', function(e){
      e.preventDefault();
      function currentDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
    
        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;
    
        return [year, month, day].join('-');
    }
      var today = currentDate();
      var lastdate = $(this).attr('data-lastdate');
          if(lastdate<today){
            location.href = "MatchBR.html"
          }else{
            location.href = "EventRegister.html"
          }

  });

      

      var token = localStorage.getItem("token");

      $.ajax({
        url: "../events.php",
        type: "POST",
        data: JSON.stringify({ list: true }),
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (events) {
          if (events && events.length) {
            var list = events
              .map((e, i) => {
                return eventItem(e, i);
               })
                .join("");
            $("#events-list").empty().append(list);
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
        },
      });   
    }
    
    if($('#career-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
       
      $.ajax({
        url: "../events.php",
        type: "POST",
        data:{ archive:'true'},
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (archives) {
          alert("Success");
        },
        error: function () {
          alert("An error ocurred.Please try again");
        },
      });
      return false;

    }
          
  });


