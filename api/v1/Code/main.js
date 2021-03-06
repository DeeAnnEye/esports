
$(document).ready(function(){
    //code here
    var token = localStorage.getItem('token');
    var role = localStorage.getItem('role');
    var team = localStorage.getItem('team');
    var teamname = localStorage.getItem("teamname");
    var evtId = null
    // console.log('Team',team);
    
    $('#team').click(function(){

        if(team == "false"){
            location.href = "TeamDefault.html";
        }else{
            location.href = "Team.html";
        }
      
    })
    if($('#team-page').length) {
          
          // var token = localStorage.getItem("token");
          if (!token) {
            location.href = "Welcome.html";
          }

          $('#team-content').hide();

          if(role <= 3) {
            $('#edit-btn').show();
        } else {
            $('#edit-btn').hide();
        }
        if(role == 3){
       var tblhead = `  <tr>
       <th class="tblhead">PLAYER</th>
       <th class="tblhead">JOINED</th>
       <th class="tblhead">TYPE</th>
       <th class="tblhead">MANAGE</th>
     </tr>`;
     $('#player-table thead').append(tblhead);
    }else{
      var tblhead = `  <tr>
       <th class="tblhead">PLAYER</th>
       <th class="tblhead">JOINED</th>
       <th class="tblhead">TYPE</th>
     </tr>`;
     $('#player-table thead').append(tblhead); 
    }

        function playerItem(p, i) {

        var joinedDate = p.joined; 
        var jDate=joinedDate.split(' ')[0];
        var role = localStorage.getItem('role');
        if(role == 3){
          var tbldata = `
          <td class="tbltxt">${p.usertag}</td>
          <td class="tbltxt">${jDate}</td>
          <td class="tbltxt">${p.ptype}</td>
          <td><button data-id="${p.player_id}" id="remove-btn" class="btn btn-outline-danger rbtn">REMOVE</button></td>
          `;
         
          $('#player-table tbody').append('<tr>'+tbldata+'</tr>');
        }
        else{

          var tbldata = `
          <td class="tbltxt">${p.usertag}</td>
          <td class="tbltxt">${jDate}</td>
          <td class="tbltxt">${p.ptype}</td>
          `;
        
          $('#player-table tbody').append('<tr>'+tbldata+'</tr>');
        }
        }
        function careerItem(c, i) {
  
          var tbldata = ` <tr>
          <td class="tbltxt">${c.event_name}</td>
          <td class="tbltxt">${c.event_start}</td>
          <td>
            <button
              class="btn btn-outline-info"
              style="padding: 0.4rem; margin-left: 1.5rem"
            >
              VIEW
            </button>
          </td>
        </tr>`;
    
          $('#career-table tbody').append('<tr>'+tbldata+'</tr>');
        }

        if(role != 3){
        $(".rbtn").prop('hidden',true);
        $("#add-btn").prop('hidden',true);
        }
       

        $("#edit-btn").click(function(e){

          $("#team-name").prop('contentEditable',true);
          $("#region").prop('contentEditable',true);
          $("#description").prop('contentEditable',true);

          var profileimg = `<div class="upld">
          <div class="input-group mb-3">
            <div class="custom-file">
              <input
                type="file"
                class="custom-file-input"
                id="image-file-1"
              />
              <label class="custom-file-label" for="inputGroupFile02"
                >Change Team Image</label
              >
            </div>
            <div class="input-group-append">
              <span class="input-group-text" id="image-upload">Upload</span>
            </div>
          </div>
        </div>`;

        $("#updt-image").empty().append(profileimg);

          $("#edit-btn").hide();

          var updt = `
          <button id="update-btn" class="btn btn-outline-light">UPDATE</button>
       `;

        $("#updt-btn").empty().append(updt);       

        })
        var rId = 1234;

        function doUpload(file) {
          var userid = localStorage.getItem("userid");
          var that = this;
          var formData = new FormData();

          // add assoc key values, this will be posts values
          formData.append("name", file, file.name);
          formData.append("rId", rId);
          formData.append("userid", userid);

          // formData.append("upload_file", true);

          $.ajax({
            type: "POST",
            url: "../events.php",
            headers: {
              Authorization: "Bearer " + token,
            },
            xhr: function () {
              var myXhr = $.ajaxSettings.xhr();
              if (myXhr.upload) {
                myXhr.upload.addEventListener(
                  "progress",
                  function () {},
                  false
                );
              }
              return myXhr;
            },
            success: function (data) {
              // alert("Image Uploaded");
            },
            error: function (error) {
              // handle error
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
          });
        }
        $(document)
          .on("click", "#image-upload", function (e) {
            e.preventDefault();
            var file = $("#image-file-1")[0].files[0];
            // console.log(file)
            doUpload(file);
            
          }).on('click','#update-btn',function(e){

            var userid = localStorage.getItem("userid");

          var team_name = $("#team-name").text();
          var region = $("#region").text();
          var description = $("#description").text();
          var teamImage = $("#image-file-1").val();
          if (teamImage != "") {
            var formData = new FormData();
            formData.append("update", "true");
            formData.append("name", team_name);
            formData.append("region", region);
            formData.append("description", description);
            formData.append("modifiedby", userid);
            formData.append(
              "image",
              "../assets/uploads/" +
                userid +
                "/" +
                rId +
                "/" +
                teamImage.replace("C:\\fakepath\\", "")
            );
          }else{
            var timage = localStorage.getItem("teamimage");
            // console.log(timage);
            
            var formData = new FormData();
            formData.append("update", "true");
            formData.append("name", team_name);
            formData.append("region", region);
            formData.append("description", description);
            formData.append("modifiedby", userid);
            formData.append("image",timage);          
          }
          var team = localStorage.getItem('team');
          $.ajax({
            url: "../teams.php?id=" + team ,
            type: "POST",
            data:formData,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            headers: {
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              console.log(response);
              alert("Team Profile Updated");
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
              // location.href = "Welcome.html";
            },
          });   

          

        })
        $(document).on('click','#add-btn',function(e){

          $.ajax({
            url: "../users.php",
            type: "POST",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (data) {
              // const player = JSON.parse(data);
              console.log(data);
              var tbldata = `
              <td class="tbltxt"><select type="text" placeholder="Select Player" id="player-search"> </select></td>
              <td class="tbltxt">-</td>
              <td class="tbltxt"><input type="text" placeholder="Player type" id="ptype"></td>
              <td class="tbltxt"><button id="addnew-btn" class="btn btn-outline-primary">ADD PLAYER</button></td>
              `;
            
              $('#player-table tbody').append('<tr>'+tbldata+'</tr>');

              $('#player-search').data(data).append(`<option value="">Select Player</option>`)
              data.map(p => {
                $('#player-search').append(`<option value="${p.user_id}">${p.usertag}</option>`)
                return p;
              });
            
            },
            error: function () {
              alert("An error ocurred.Please try again");
              // location.href = "Welcome.html";
            },
          });
         
          
        })


        $(document).on('click','#addnew-btn',function(e){

          var teamId = localStorage.getItem('team');
          var formData = new FormData();
          formData.append("player_id", $("#player-search").val() );
          formData.append("team_id",teamId);
          formData.append("ptype",$("#ptype").val());
          formData.append("jointeam",true);

          $.ajax({
            url: "../users.php",
            type: "POST",
            data:formData,
            headers: {
              Authorization: "Bearer " + token,
            },
            contentType: false,
            mimeType: "multipart/form-data",
            processData: false,
            success: function (response) {
              alert("Joined successfully.");  
              location.reload();
              
            },
            error: function () {
              alert("Player already in team!");
              // location.href = "Welcome.html";
            },
          });

        })

        $(document).on('click','#remove-btn',function(e){
          e.preventDefault();
          var playerId = $(this).attr('data-id'); 
          console.log(playerId);
          var teamId = localStorage.getItem('team');
          var formData = new FormData();
          formData.append("player_id", playerId );
          formData.append("team_id",teamId);
          formData.append("removeplayer",true);

          $.ajax({
            url: "../users.php",
            type: "POST",
            data:formData,
            headers: {
              Authorization: "Bearer " + token,
            },
            contentType: false,
            mimeType: "multipart/form-data",
            processData: false,
            success: function (response) {
            //  console.log(response); 
            alert("Player removed.")
            location.reload();             
            },
            error: function () {
              alert("Couldn't remove player");
              // location.href = "Welcome.html";
            },
          });

        })
       
    
          $.ajax({
            url: "../teams.php?id=" + team,
            type: "GET",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (data) {

              $('#team-content').show();

              // console.log(data);
                if (data) {
                  const player = JSON.parse(data.player);
                  const team = JSON.parse(data.team);
                  const career = JSON.parse(data.career);

                  var datetime = team.created; 
                  var date=datetime.split(' ')[0];

                  localStorage.setItem("teamname", team.name);
                  localStorage.setItem("teamimage", team.image);

                  $('#team-name').text(team.name);
                  $('#region').text(team.region);
                  $('#description').text(team.description);
                  $('#created').text(date);
                  $('#createdby').text(team.cutag);
                  $('#pfp').find('img').attr('src', team.image);

                  if (player && player.length) {
                    // const len = player.length;
                    $("#career-table").hide();
                    var list = player
                      .map((p, i) => {
                        return playerItem(p, i);
                       })
                        .join("");
                        
                      }

                      $("#player-btn").click(function(e){

                        $("#player-table td").remove();
                        $("#career-table").hide();
                        $("#player-table").show();
                        $("#add-btn").show();

                        if (player && player.length) {
                          var list = player
                            .map((p, i) => {
                              return playerItem(p, i);
                             })
                              .join("");
                            }
                       
                        })

                        $("#career-btn").click(function(e){

                          $("#career-table td").remove();
                          $("#player-table").hide();
                          $("#add-btn").hide();
                          $("#career-table").show();

                          if (career && career.length) {
                            var list = career
                              .map((c, i) => {
                                return careerItem(c, i);
                               })
                                .join("");
                              }
                        })

                }
                         
              
            },
            error: function () {
              alert("An error ocurred.Please try again");
              // location.href = "Welcome.html";
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
            // location.href = "Welcome.html";
          },
        });
        return false;
    }
    if($('#event-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
      $('#manage-btn').hide();
      $('#host-btn').hide();
        
      function eventItem(e, i) {
        var item = ` <a class="event-click" data-gametype="${e.gametype}" data-enddate="${e.event_end}" data-id="${e.event_id}" data-lastdate="${e.last_date_of_registration}" href="#" id="event-banner-${i}"><div class="event">
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
      var enddate = $(this).attr('data-enddate');
      var eventId = $(this).attr('data-id'); 
      var gametype =$(this).attr('data-gametype');
      // alert(gametype)
          if(lastdate<today && enddate>today){
            location.href = gametype==='BR' ? "MatchBR.html?id=" + eventId : "MatchElse.html?id=" + eventId;
          }else if(enddate<today){
            location.href = gametype==='BR' ? "ResultBR.html?id=" + eventId : "ResultElse.html?id=" + eventId;
          }
          else{
            location.href = "EventRegister.html?id=" + eventId;
          }
                  
  });
    
  $("#manage-btn").click(function(e){
    e.preventDefault();
    location.href = "ModManager.html";
  })

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

          if(role <= 2) {
            $('#host-btn').show();
            $('#manage-btn').show();
        } else {
          $('#manage-btn').hide();
            $('#host-btn').hide();
        }

          if (events && events.length) {
            var list = events
              .map((e, i) => {
                return eventItem(e, i);
               })
                .join("");
                // console.log(events);
            $("#events-list").empty().append(list);
          }
        },
        error: function () {
          $("#event-page").hide();
          alert("An error ocurred.Please try again");         
          // location.href = "Welcome.html";
        },
      });  
      
    }       

    if($('#eventhost-page').length){

      var rId = 1234;
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
      function doUpload(file) {

        var userid = localStorage.getItem("userid");
        var that = this;
        var formData = new FormData();
    
        // add assoc key values, this will be posts values
        formData.append("name", file, file.name);
        formData.append("rId",rId);
        formData.append("userid",userid);

        // formData.append("upload_file", true);
    
        $.ajax({
            type: "POST",
            url: "../events.php",
            headers: {
              Authorization: "Bearer " + token,
            },
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(){}, false);
                }
                return myXhr;
            },
            success: function (data) {
                // alert("Image Uploaded");
            },
            error: function (error) {
                // handle error
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000
        });

    };
      $(document).on('click','#image-upload', function(e){
        e.preventDefault();
        var file = $('#image-file-1')[0].files[0];
        // console.log(file)
        doUpload(file);
      }).on('click','#create-event-btn',function(e){
        e.preventDefault();
        var token = localStorage.getItem("token");
        var userid = localStorage.getItem("userid");

        if($('#event-name').val()===''){
          alert('Event name required.');
          return;
        }
        if($('#event-start').val()===''){
          alert('Event start date required.');
          return;
        }else{
           const d = new Date($('#event-start').val());
           console.log(d);
           if(d.toString()==='Invalid Date'){
            alert('Invalid event start date.');
            return;
          }              
        }
        
        if($('#event-end').val()===''){
          alert('Event end date required.');
          return;
        }else{
          const d = new Date($('#event-end').val());
          console.log(d);
          if(d.toString()==='Invalid Date'){
           alert('Invalid event end date.');
           return;
         }  
        }   
        if($('#last-date').val()===''){
          alert('Last date required.');
          return;
        }else{
          const d = new Date($('#last-date').val());
          console.log(d);
          if(d.toString()==='Invalid Date'){
           alert('Invalid last date.');
           return;
         }  
        } 
        var game  = $('#choose-game').val();
        if(game===null){
          alert('Game required.');
          return;
        }
        var region  = $('#choose-region').val();
        if(region===null){
          alert('Event Region required.');
          return;
        }
        var category = $('#choose-category').val();
        if(category===null){
          alert('Category required.');
          return;
        }
        if($('#max-team').val()===''){
          alert('Maximum Teams required.');
          return;
        }  
        var eventImage  = $('#image-file-1').val();
        if(eventImage===''){
          alert('Upload event image.');
          return;
        }

         // console.log(userid);()
        
        var formData = new FormData();
        formData.append("create", "true");
        formData.append("event_name", $('#event-name').val());
        formData.append("event_start", $('#event-start').val());
        formData.append("event_end", $('#event-end').val());
        formData.append("region",$('#choose-region').val());
        formData.append("game",$('#choose-game').val());
        formData.append("category",$('#choose-category').val());
        formData.append("max_participants",$('#max-team').val());
        formData.append("last_date_of_registration", $('#last-date').val());
        formData.append("createdby", userid);
        formData.append("modifiedby", userid);
        formData.append("image",'../assets/uploads/'+userid+ '/'+rId+'/'+ eventImage.replace('C:\\fakepath\\',''));

        $.ajax({
          url: "../events.php",
          type: "POST",
          data: formData,
          headers: {
            Authorization: "Bearer " + token,
          },
          contentType: false,
          mimeType: "multipart/form-data",
          processData: false,
          success: function (response) {
            console.log(response);
            alert("Event Created");
            location.reload();
          },
          error: function () {
            alert("Event Creation Failed");
          },
        });
        return false;
      });
        
    }
    
    if($('#career-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
      function archiveItem(a, i) {
        var item = `<div
        style="
          display: flex;
          flex-direction: row;
          border-top: 4px solid #202833;
          border-bottom: 4px solid #202833;
          justify-content: space-around;
          margin-top: 1rem;
        "
      >
        <h3 class="eventid">${a.event_name}</h3>
        <h3 class="eventid">${a.event_start}</h3>
      </div>
    `;
      return item;
    }
      var form = new FormData();
      form.append("getarchivedevents", "true");
       
      $.ajax({
        url: "../events.php",
        type: "POST",
        data:form,
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        headers: {
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          const archives = JSON.parse(data);
          if (archives && archives.length) {
            var list = archives
              .map((a, i) => {
                return archiveItem(a, i);
               })
                .join("");
                // console.log(archives);
            $("#archive-list").empty().append(list);
              }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
      return false;

    }
    if($('#home-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
    }

    if($('#matchbr-page').length){
      const urlParams = new URLSearchParams(window.location.search);
      const eventId = urlParams.get('id');
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
         
      function teamItem(t, i) {
        var r = `  <div class="row">`;
        var item = `<div class="box">
        <div style="width: 6rem; height: 6rem; border: 2px solid grey">
          <img
            style="object-fit: cover; width: 100%"
            src="${t.image}"
            alt=""
          />
        </div>
        <h1 class="txt" style="font-size: 1rem; text-align: center">
          ${t.team_name}
        </h1>
      </div>`;
        if ((i + 1) % 4 === 0 || i === 0) {
          var closeDiv = i !== 0 ? "</div>" : "";
          return closeDiv + r + item;
        }

        return item;
      }
      $.ajax({
        url: "../events.php?id=" + eventId ,
        type: "GET",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);
          if (data) {
            const event = JSON.parse(data.event);
            const team = JSON.parse(data.team);
            $('#event-name').text(event.event_name)
            if (team && team.length) {
              var list = team
              .map((t, i) => {
                return teamItem(t, i);
               })
                .join("");
                // console.log(archives);
            $("#team-list").empty().append(list);
            }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
      

    }

    if($('#matchelse-page').length){
      const urlParams = new URLSearchParams(window.location.search);
      const eventId = urlParams.get('id');
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      $("#back-btn").click(function(e){
        e.preventDefault();
        history.back();
      })
              
      $.ajax({
        url: "../events.php?id=" + eventId ,
        type: "GET",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          if (data) {
            // const player = JSON.parse(data.player);
            const event = JSON.parse(data.event);
            $('#event-name').text(event.event_name)
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
      return false;

    }

    if($('#user-page').length){
      var token = localStorage.getItem("token");
      var userid = localStorage.getItem("userid");
      if (!token) {
        location.href = "Welcome.html";
      }
      $('#logout-btn').click(function(e){
        e.preventDefault();
        localStorage.removeItem("token");   
        location.href = "Welcome.html";
      })

      $("#back-btn").click(function(e){
        e.preventDefault();
        history.back();
      })

      $("#edit-btn").click(function(e){
        e.preventDefault();

        $(this).hide();

        $("#username").prop('contentEditable',true);
        $("#region").prop('contentEditable',true);
        $("#language").prop('contentEditable',true);
        $("#discord").prop('contentEditable',true);
        $("#email").prop('contentEditable',true);
        $("#phone").prop('contentEditable',true);

        var profileimg = `<div class="upld">
        <div class="input-group mb-3">
          <div class="custom-file">
            <input
              type="file"
              class="custom-file-input"
              id="image-file-1"
            />
            <label class="custom-file-label" for="inputGroupFile02"
              >Change Profile Image</label
            >
          </div>
          <div class="input-group-append">
            <span class="input-group-text" id="image-upload">Upload</span>
          </div>
        </div>
      </div>`;

      $("#pContent").append(profileimg);

        $("#update-btn").prop('hidden',false);
        
      })
      var rId = 1234;

        function doUpload(file) {
          var userid = localStorage.getItem("userid");
          var that = this;
          var formData = new FormData();

          // add assoc key values, this will be posts values
          formData.append("name", file, file.name);
          formData.append("rId", rId);
          formData.append("userid", userid);

          // formData.append("upload_file", true);

          $.ajax({
            type: "POST",
            url: "../events.php",
            headers: {
              Authorization: "Bearer " + token,
            },
            xhr: function () {
              var myXhr = $.ajaxSettings.xhr();
              if (myXhr.upload) {
                myXhr.upload.addEventListener(
                  "progress",
                  function () {},
                  false
                );
              }
              return myXhr;
            },
            success: function (data) {
              // alert("Image Uploaded");
            },
            error: function (error) {
              // handle error
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            timeout: 60000,
          });
        }
        $(document).on("click", "#image-upload", function (e) {
            e.preventDefault();
            var file = $("#image-file-1")[0].files[0];
            // console.log(file)
            doUpload(file);
            
          }).on('click','#update-btn',function(e){
           e.preventDefault();
        
        var username = $("#username").text();
        var region = $("#region").text();
        var language = $("#language").text();
        var discord = $("#discord").text();
        var email = $("#email").text();
        var phone = $("#phone").text();
        var userImage = $("#image-file-1").val();
        if (userImage != "") {
        var form = new FormData();
        form.append("update", "true");
        form.append("usertag", username);
        form.append("region", region);
        form.append("language", language);
        form.append("social_acc", discord);
        form.append("email", email);
        form.append("phone", phone);
        form.append(
          "image",
          "../assets/uploads/" +
            userid +
            "/" +
            rId +
            "/" +
            userImage.replace("C:\\fakepath\\", "")
        );
        }else{
          var uimage = localStorage.getItem("uimage");
          var form = new FormData();
          form.append("update", "true");
          form.append("usertag", username);
          form.append("region", region);
          form.append("language", language);
          form.append("social_acc", discord);
          form.append("email", email);
          form.append("phone", phone);
          form.append("image",uimage); 
        }
        $.ajax({
          url: "../users.php?id=" + userid ,
          type: "POST",
          data:form,
          "processData": false,
          "mimeType": "multipart/form-data",
          "contentType": false,
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            // console.log(response);
            alert("Profile Updated");
            location.reload();
          },
          error: function () {
            alert("An error ocurred.Please try again");
            // location.href = "Welcome.html";
          },
        });       
      
      })

      var role = localStorage.getItem('role');
      if(role>2){
        $("#req-btn").show();
      }else{
        $("#req-btn").hide();
      }

      $("#req-btn").click(function(e){
        e.preventDefault();

        var form = new FormData();
        form.append("modrequest", "true");
        // console.log(userid);

        $.ajax({
          url: "../users.php?id=" + userid ,
          type: "POST",
          data:form,
          "processData": false,
          "mimeType": "multipart/form-data",
          "contentType": false,
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            console.log(response);
            alert("Moderator request sent.");
            var btn = document.getElementById("req-btn");
            btn.value = 'mod-request'; 
            btn.innerHTML = 'Moderator Request Sent';
            $("#req-btn").prop('disabled',true);
          },
          error: function () {
            alert("An error ocurred.Please try again");
            // location.href = "Welcome.html";
          },
        });        
      })
      
      
      $.ajax({
        url: "../users.php?id=" + userid ,
        type: "GET",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          // console.log(data);
          if (data) {
            $('#box').find('img').attr('src', data.image)
            $('#username').text(data.usertag)
            $('#region').text(data.region)
            $('#discord').text(data.social_acc)
            $('#language').text(data.language)
            $('#teamname').text(data.teamname)
            $('#email').text(data.email)
            $('#phone').text(data.phone)

            localStorage.setItem("uimage", data.image);

            var req = data.mod_request;
            if(req != 1){
              $("#mod-btn").show();
            }else{
              $("#mod-btn").hide();
            }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
      return false;

    }

    if($('#mod-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
     
      $("#back-btn").click(function(e){
        location.href = "Event.html";
      })
      function modItem(m, i) {

        var createdDate = m.created; 
        var modifiedDate = m.modified; 
        var cDate=createdDate.split(' ')[0];
        var mDate=modifiedDate.split(' ')[0];

        var tbldata = `<td class="tbltxt" >${m.event_name}</td>
        <td class="tbltxt">${cDate}</td>
        <td class="tbltxt">${mDate}</td>
        <td class="tbltxt">${m.event_start}</td>
        <td><button data-id=${m.event_id} id="edit-btn" class="btn btn-outline-info" style="padding: .4rem;margin-left: 1.5rem;">EDIT</button></td>
        <td><button data-id=${m.event_id} id="delete-btn" class="btn btn-outline-danger" style="padding: .4rem;margin-left: 1.5rem;">DELETE</button></td>`;
  
        $('#mod-table tbody').append('<tr>'+tbldata+'</tr>');
      }
     
      $(document).on('click','#delete-btn', function(e){
        e.preventDefault();
        var evntId = $(this).attr('data-id'); 
        $.ajax({
          url: "../events.php?id=" + evntId ,
          type: "DELETE",
          headers: {
            "content-type": "application/json",
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            // console.log(response);
            location.reload();
            // $('#myTableRow').remove();
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
      })

      $(document).on('click','#edit-btn', function(e){
        e.preventDefault();
        var evntId = $(this).attr('data-id'); 
        $.ajax({
          url: "../events.php?id=" + evntId ,
          type: "POST",
          headers: {
            "content-type": "application/json",
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            // console.log(response);
            location.reload();
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
      })

      var userid = localStorage.getItem("userid");
      var form = new FormData();
      form.append("modevent", "true");
      form.append("user", userid);
       
      $.ajax({
        url: "../events.php",
        type: "POST",
        data:form,
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        headers: {
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          const modevents = JSON.parse(data);
          console.log(modevents);
          if (modevents && modevents.length) {
            var list = modevents
              .map((m, i) => {
                return modItem(m, i);
               })
                .join("");
              }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
    
      return false;
    }

    if($('#resultbr-page').length) {
      const urlParams = new URLSearchParams(window.location.search);
      const eventId = urlParams.get('id');
      // console.log(eventId);
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }
      $("#back-btn").click(function(e){
        e.preventDefault();
        history.back();
      })
      $(".pos-block").click(function(e){
       const pos = $(this).attr('data-pos');
       $("#pos").text("#"+pos);
       const data = $('#pos' + pos).data();
      //  console.log(data);
       $("#score-table tbody").empty();
       Object.keys(data).map(d => {
         const p = data[d];
        const item = `<tr>
        <td class="tbltxt">${p.usertag}</td>
        <td class="tbltxt">${p.kill}</td>
        <td class="tbltxt">${p.death}</td>
        <td class="tbltxt">${p.assist}</td>
      </tr>`;
 
      $("#score-table tbody").append(item);
         return d;
       })

       
      //  alert(pos);
      })

      $.ajax({
        url: "../results.php?id=" + eventId ,
        type: "GET",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);

          const first = data.find(t => t.position === "1");
          const second = data.find(t => t.position === "2");
          const third = data.find(t => t.position === "3");
          if(first && first.name) {
          console.log(first.name);
          $("#pos1").text(first.name);
          $("#head").text(first.event_name);
          $("pos1-image").attr("src",first.teamimage)
          $("#pos1").data(data.filter(t => t.position === '1'))
          $(".pos-block").eq(1).click();

          }
          if(second && second.name) {
            // console.log(first.name);
            $("#pos2").text(second.name);
            $("pos2-image").attr("src",second.teamimage)
            $("#pos2").data(data.filter(t => t.position === '2'))
            }
            if(third && third.name) {
              // console.log(first.name);
              $("#pos3").text(third.name);
              $("pos3-image").attr("src",third.teamimage)
              $("#pos3").data(data.filter(t => t.position === '3'))
              }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
      

    }

    if($('#auser-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      function userItem(u, i) {

        var createdDate = u.created; 
        var cDate=createdDate.split(' ')[0];
  
          var tbldata = `
          <td class="list">${u.usertag}</td>
          <td class="list">${u.user_id}</td>
          <td class="list">${cDate}</td>
          <td class="list">${u.team}</td>
          <td
            id="ban-btn"
            data-id=${u.user_id}
            class="btn btn-outline-danger"
            style="display: block; margin: auto"
          >
            BAN
          </td>
          `;
         
          $('#auser-table tbody').append('<tr>'+tbldata+'</tr>');
         
        }

        $(document).on('click','#ban-btn', function(e){
          e.preventDefault();
          var userId = $(this).attr('data-id'); 
          $.ajax({
            url: "../users.php?id=" + userId ,
            type: "DELETE",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              // console.log(response);
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
        })
     

      $.ajax({
        url: "../users.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);
          if (data) {
            // const user = JSON.parse(data);
            if (data && data.length) {
              var list = data
                .map((u, i) => {
                  return userItem(u, i);
                 })
                  .join("");
                  
                }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
    }

    if($('#ateam-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      function teamItem(t, i) {

        var createdDate =t.created; 
        var cDate=createdDate.split(' ')[0];
        var modifiedDate =t.modified; 
        var mDate=modifiedDate.split(' ')[0];
  
          var tbldata = `
          <tr>
                <td class="list">${t.name}</td>
                <td class="list">${cDate}</td>
                <td class="list">${mDate}</td>
                <td class="list">${t.region}</td>
                <td id="ban-btn"
                data-id=${t.id}
                class="btn btn-outline-danger" style="display: block; margin: auto;">BAN</td>
            </tr>      
          `;
         
          $('#ateam-table tbody').append('<tr>'+tbldata+'</tr>');
         
        }
        $(document).on('click','#ban-btn', function(e){
          e.preventDefault();
          var teamId = $(this).attr('data-id'); 
          $.ajax({
            url: "../teams.php?id=" + teamId ,
            type: "DELETE",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              // console.log(response);
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
        })

      $.ajax({
        url: "../teams.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);
          if (data) {
            // const user = JSON.parse(data);
            if (data && data.length) {
              var list = data
                .map((t, i) => {
                  return teamItem(t, i);
                 })
                  .join("");
                  
                }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
    }

    if($('#agame-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      // function for displaying games
      function gameItem(g, i) {

        var createdDate =g.created; 
        var cDate=createdDate.split(' ')[0];
       
          var tbldata = `
          <tr>
                <td class="list">${g.name}</td>
                <td class="list">${cDate}</td>
                <td class="list">${g.gametype}</td>
                <td class="list">${g.number_of_players}</td>
                <td class="btn btn-outline-success" style="display: block; margin: auto;">IMAGE</td>
                <td id="edit-btn" data-id="${g.id}" class="btn btn-outline-warning" style="display: block; margin: auto;">EDIT</td>
                <td id="update-btn" data-id="${g.id}" class="btn btn-outline-primary" style="display: block; margin: auto;" hidden>UPDATE</td>
                <td id="delete-btn" data-id="${g.id}" class="btn btn-outline-danger" style="display: block; margin: auto;">DELETE</td>
            </tr>     
          `;
         
          $('#agame-table tbody').append('<tr>'+tbldata+'</tr>');
         
        }

        // event to input details on click to add game
        $(document).on('click','#addnew-btn', function(e){
          e.preventDefault();
          var tbldata = `
          <tr>
                <td class="list"><input type="text" placeholder="Game name.." id="name"></td>
                <td class="list"><div contenteditable="false">-</div></td>
                <td class="list"><input type="text" placeholder="Game type.." id="gametype"></td>
                <td class="list"><input type="text" placeholder="No of players.." id="playerno"></td>
                <td class="btn btn-outline-success" style="display: block; margin: auto;">IMAGE</td>
                <td id="add-btn" class="btn btn-outline-warning" style="display: block; margin: auto;">ADD</td>
               
            </tr>     
          `;
           
          $('#agame-table tbody').append('<tr>'+tbldata+'</tr>');

        })

        $(document).on('click','#edit-btn', function(e){
          e.preventDefault();

          $(this).hide();
                    
          var currentTD = $(this).closest("tr");
            currentTD.find("td:eq(0)").prop('contenteditable', true)
            currentTD.find("td:eq(2)").prop('contenteditable', true)
            currentTD.find("td:eq(3)").prop('contenteditable', true)   

            currentTD.find("#update-btn").prop('hidden', false)

            // request to update on click
        $(document).on('click','#update-btn', function(e){
          e.preventDefault();

          var col1=currentTD.find("td:eq(0)").text(); 
          var col2=currentTD.find("td:eq(2)").text();
          var col3=currentTD.find("td:eq(3)").text();


          var gameId = $(this).attr('data-id'); 
          var removed=0;

            // formdata input
            var formData = new FormData();
            formData.append("update", "true");
            formData.append("name", col1);
            formData.append("gametype", col2);
            formData.append("number_of_players", col3);
            formData.append("removed",removed);

          $.ajax({
            url: "../games.php?id="+gameId,
            type: "POST",
            data: formData,
            headers: {
              Authorization: "Bearer " + token,
            },
            contentType: false,
            mimeType: "multipart/form-data",
            processData: false,
            success: function (response) {
              console.log(response);
              alert("Game Updated");
              location.reload();
            },
            error: function () {
              alert("Error");
            },
          });
        })
                   
    })
  
        // add new game on click
        $(document).on('click','#add-btn', function(e){
          e.preventDefault();

          var image="./assets/img/nullimage.jpg";

          // formdata input
          var formData = new FormData();
          formData.append("create", "true");
          formData.append("name", $('#name').val());
          formData.append("gametype", $('#gametype').val());
          formData.append("number_of_players", $('#playerno').val());
          formData.append("image", image);
          formData.append("wallpaper", null);

          // request to create game
          $.ajax({
            url: "../games.php",
            type: "POST",
            data: formData,
            headers: {
              Authorization: "Bearer " + token,
            },
            contentType: false,
            mimeType: "multipart/form-data",
            processData: false,
            success: function (response) {
              // console.log(response);
              alert("Game Added");
              location.reload();
            },
            error: function () {
              alert("Error");
            },
          });

        })

        // request to delete on click
        $(document).on('click','#delete-btn', function(e){
          e.preventDefault();
          var gameId = $(this).attr('data-id'); 
          $.ajax({
            url: "../games.php?id=" + gameId ,
            type: "DELETE",
            headers: {
              "content-type": "application/json",
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              alert("Game deleted");
              console.log(response);
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
        })

        

        // request to fetch games
      $.ajax({
        url: "../games.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          // console.log(data);
          if (data) {
            if (data && data.length) {
              var list = data
                .map((g, i) => {
                  return gameItem(g, i);
                 })
                  .join("");
                  
                }
          }
         
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
    }

    if($('#aevent-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      function eventItem(e, i) {

        var createdDate =e.created; 
        var cDate=createdDate.split(' ')[0];
        var modifiedDate =e.modified; 
        var mDate=modifiedDate.split(' ')[0];

        if(e.archive == 1){
          var tbldata = `
          <tr>
          <td class="list">${e.event_name}</td>
          <td class="list">${cDate}</td>
          <td class="list">${mDate}</td>
          <td class="list">${e.event_start}</td>
          <td class="list">${e.eCount}</td>
          <td class="list">${e.region}</td>
          <td
            id="archive-btn"
            data-id="${e.event_id}"
            class="btn btn-warning disabled"
            style="display: block; margin: auto;"
           
          >
            Archived
          </td>
          <td
          id="ban-btn"
            class="btn btn-outline-danger"
            style="display: block; margin: auto"
          >
            BAN
          </td>
        </tr> 
          `;
         
          $('#aevent-table tbody').append('<tr>'+tbldata+'</tr>');
        }else{
       
          var tbldata = `
          <tr>
          <td class="list">${e.event_name}</td>
          <td class="list">${cDate}</td>
          <td class="list">${mDate}</td>
          <td class="list">${e.event_start}</td>
          <td class="list">${e.eCount}</td>
          <td class="list">${e.region}</td>
          <td
            id="archive-btn"
            data-id="${e.event_id}"
            class="btn btn-outline-warning"
            style="display: block; margin: auto"
          >
            Archive
          </td>
          <td
            id="ban-btn"
            data-id="${e.event_id}"
            class="btn btn-outline-danger"
            style="display: block; margin: auto"
          >
            BAN
          </td>
        </tr> 
          `;
         
          $('#aevent-table tbody').append('<tr>'+tbldata+'</tr>');
        }
         
        }

        // request to archive event
        $(document).on('click','#archive-btn', function(e){
          e.preventDefault();
          
           // form input for request
        var form = new FormData();
        var eventId = $(this).attr('data-id'); 
        form.append("archiveevent", "true");
       
        // request to archive event
        $.ajax({
          url: "../events.php?id=" + eventId ,
          type: "POST",
          data:form,
          "processData": false,
          "mimeType": "multipart/form-data",
          "contentType": false,
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            console.log(response);
            alert("Event archived");
            location.reload();
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
        })

         // request to archive event
         $(document).on('click','#ban-btn', function(e){
          e.preventDefault();

        var eventId = $(this).attr('data-id'); 
        
        // request to archive event
        $.ajax({
          url: "../events.php?id=" + eventId ,
          type: "DELETE",
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            console.log(response);
            alert("Event Banned");
            location.reload();
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
        })

    //  request to fetch events
      $.ajax({
        url: "../events.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);
          if (data) {
            if (data && data.length) {
              var list = data
                .map((e, i) => {
                  return eventItem(e, i);
                 })
                  .join("");
                  
                }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });
    }
    
    if($('#eventreg-page').length){

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      const urlParams = new URLSearchParams(window.location.search);
      const eventId = urlParams.get('id');

      $("#back-btn").click(function(e){
        e.preventDefault();
        history.back();
      })

      $.ajax({
        url: "../events.php?id=" + eventId,
        type: "GET",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          // console.log(data);
          var teamname = localStorage.getItem("teamname");
          var teamimage = localStorage.getItem("teamimage");
          //  console.log(teamimage);
          if (data) {
            const event = JSON.parse(data.event);
            $('#wallpaper').find('img').attr('src', event.image);
            $('#event-name').text(event.event_name)
            $('#event-start').text(event.event_start)
            $('#region').text(event.region)
            $('#teamname').text(teamname)
            $('#pfp').find('img').attr('src', teamimage);


            var playernum = event.category;
            if(playernum == 1){
            $("#teamreg").hide();
            }else{
              $("#teamreg").show();
            }

            // request to fetch team players
            var formData = new FormData();
            formData.append("teamplayer", "true");
            var teamId = localStorage.getItem("team");
            $.ajax({
              url: "../teams.php?id="+ teamId,
              type: "POST",
              data: formData,
              headers: {
                Authorization: "Bearer " + token,
              },
              contentType: false,
              mimeType: "multipart/form-data",
              processData: false,
              success: function (data) {
                const team = JSON.parse(data);
                console.log(team);
                if(playernum != 1){
                  for(var i=1;i<=playernum;i++){
                                        
                  var playerInput = `<select id="pl-${i}" class="inp pl-sm-4 e-player" type="text" placeholder="Player" >`
                  playerInput += `<option value=''>Select player</option>`;
                  playerInput += team.map(t => `<option value="${t.player_id}">${t.usertag}</option>`).join('');
                  playerInput+=`</select>`;
                  $("#player-input").append(playerInput);
                    
                  }                
                  
                                  
                }
                
              },
              error: function () {
                alert("Couldn't fetch players");
              },
            });

            var checkPlayers = [];
            $(document).on('change', '.e-player', function(e) {
             
              var v = $(this).val();
              if(!checkPlayers.includes(v)) {
                checkPlayers.push(v);
              } else {
                alert('Player already selected');
                $(this).val('');
              }
              

            });

          $("#register-btn").click(function(e){
            e.preventDefault();
    
    
            if(playernum == 1){
    
            var userid = localStorage.getItem("userid");
    
            var formData = new FormData();
            formData.append("playerevent", "true");
            formData.append("player_id", userid);
            formData.append("event_id", eventId);
    
            $.ajax({
              url: "../events.php",
              type: "POST",
              data: formData,
              headers: {
                Authorization: "Bearer " + token,
              },
              contentType: false,
              mimeType: "multipart/form-data",
              processData: false,
              success: function (response) {
                // console.log(response);
                alert("Registered Successfully");
              },
              error: function () {
                alert("Sorry,couldn't register");
              },
            });
            return false;
    
            }    
            else{

              var team = localStorage.getItem('team');
              var players = [];
              var ePlayer = $(".e-player").map(e => {
               const v = $(".e-player").eq(e).val();
               players.push(v);
                return $(".e-player").eq(e).val()
              });
              console.log(ePlayer);

              var formData = new FormData();
              formData.append("teamevent", "true");
              formData.append("team_id", team);
              formData.append("event_id", eventId);
              formData.append("eventplayers",JSON.stringify(players));
      
              $.ajax({
                url: "../events.php",
                type: "POST",
                data: formData,
                headers: {
                  Authorization: "Bearer " + token,
                },
                contentType: false,
                mimeType: "multipart/form-data",
                processData: false,
                success: function (response) {
                console.log(response);
                },
                error: function () {
                  alert("Sorry,couldn't register");
                },
              });
              return false;
      
            }
          })
            
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });

    }

    if($('#ahome-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      function userItem(m, i) {
  
          var tbldata = `          
          <td class="list">${m.usertag}</td>
          <td class="list">${m.user_id}</td>
          <td class="list">${m.social_acc}</td>
          <td
            id="updt-btn"
            class="btn btn-outline-warning"
            style="display: block; margin: auto"
            data-id=${m.user_id}
          >
            Update Role
          </td>
          <td
            id="cancel-btn"
            class="btn btn-outline-danger"
            style="display: block; margin: auto"
            data-id=${m.user_id}
          >
            Cancel Request
          </td>
          `;
         
          $('#mod-table tbody').append('<tr>'+tbldata+'</tr>');
         
        }

        // request to assign mod role
        $(document).on('click','#updt-btn', function(e){
          e.preventDefault();
          var form = new FormData();
        form.append("updatemodrole", "true");
          var userId = $(this).attr('data-id'); 
          $.ajax({
            url: "../users.php?id=" + userId ,
            type: "POST",
            data:form,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            headers: {
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              console.log(response);
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
        })

        // request to cancel mod request
        $(document).on('click','#cancel-btn', function(e){
          e.preventDefault();
          var form = new FormData();
        form.append("cancelrequest", "true");
          var userId = $(this).attr('data-id'); 
          $.ajax({
            url: "../users.php?id=" + userId ,
            type: "POST",
            data:form,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            headers: {
              Authorization: "Bearer " + token,
            },
            success: function (response) {
              console.log(response);
              location.reload();
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
        })


        var form = new FormData();
        form.append("reqplayers", "true");

        $.ajax({
          url: "../users.php",
          type: "POST",
          data:form,
          "processData": false,
          "mimeType": "multipart/form-data",
          "contentType": false,
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (data) {
            const modplayers = JSON.parse(data);
            console.log(modplayers);
            if (modplayers && modplayers.length) {
              var list = modplayers
                .map((m, i) => {
                  return userItem(m, i);
                 })
                  .join("");
                }
          },
          error: function () {
            alert("An error ocurred.Please try again");
            // location.href = "Welcome.html";
          },
        });

    }

    if($('#arole-page').length) {

      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      function roleItem(r, i) {

        var createdDate = r.created; 
        var cDate=createdDate.split(' ')[0];

        var role = r.role;

        if(role == 1){
  
        var tbldata = `          
          <td class="list">${r.usertag}</td>
          <td class="list">${cDate}</td>
          <td class="list">Y</td>
          <td class="list">-</td>
          <td class="list">-</td>
          <td id="edit-btn" data-id="${r.user_id}" class="btn btn-outline-warning" style="display: block; margin: auto;">EDIT</td>
          <td
          id="update-btn"
            class="btn btn-outline-primary"
            style="display: block; margin: auto"
            data-id=${r.user_id}
            hidden
          >
            UPDATE
          </td>
        `;
       
        $('#role-table tbody').append('<tr>'+tbldata+'</tr>');
        }
        else if(role == 2){
  
          var tbldata = `          
            <td class="list">${r.usertag}</td>
            <td class="list">${cDate}</td>
            <td class="list">-</td>
            <td class="list">Y</td>
            <td class="list">-</td>
            <td id="edit-btn" data-id="${r.user_id}" class="btn btn-outline-warning" style="display: block; margin: auto;">EDIT</td>
            <td
              id="update-btn"
              class="btn btn-outline-primary"
              style="display: block; margin: auto"
              data-id=${r.user_id}
              hidden
            >
              UPDATE
            </td>
          `;
         
          $('#role-table tbody').append('<tr>'+tbldata+'</tr>');
          }
          else if(role == 3){
  
            var tbldata = `          
              <td class="list">${r.usertag}</td>
              <td class="list">${cDate}</td>
              <td class="list">-</td>
              <td class="list">-</td>
              <td class="list">Y</td>
              <td id="edit-btn" data-id="${r.user_id}" class="btn btn-outline-warning" style="display: block; margin: auto;">EDIT</td>
              <td
              id="update-btn"
                class="btn btn-outline-primary"
                style="display: block; margin: auto"
                data-id=${r.user_id}
                hidden
              >
                UPDATE
              </td>
            `;
           
            $('#role-table tbody').append('<tr>'+tbldata+'</tr>');
            }
            else{
              var tbldata = `          
              <td class="list">${r.usertag}</td>
              <td class="list">${cDate}</td>
              <td class="list">-</td>
              <td class="list">-</td>
              <td class="list">-</td>
              <td id="edit-btn" data-id="${r.user_id}" class="btn btn-outline-warning" style="display: block; margin: auto;">EDIT</td>
              <td
                id="update-btn"
                class="btn btn-outline-primary"
                style="display: block; margin: auto"
                data-id=${r.user_id}
                hidden
              >
                UPDATE
              </td>
            `;
           
            $('#role-table tbody').append('<tr>'+tbldata+'</tr>');
            }
       
      }

      $(document).on('click','#edit-btn', function(e){
        e.preventDefault();

        $(this).hide();
                  
        var currentTD = $(this).closest("tr");
          currentTD.find("td:eq(2)").prop('contenteditable', true)
          currentTD.find("td:eq(3)").prop('contenteditable', true)
          currentTD.find("td:eq(4)").prop('contenteditable', true)   

          currentTD.find("#update-btn").prop('hidden', false)

     
      $("#role-table").on('click','#update-btn', function(e){
        e.preventDefault();

        // getting value of each cell     
         var col1=currentTD.find("td:eq(2)").text(); 
         var col2=currentTD.find("td:eq(3)").text();
         var col3=currentTD.find("td:eq(4)").text();

         if((col1 == 'Y' && col2 == '-' && col3 == '-')||
            (col1 == '-' && col2 == 'Y' && col3 == '-')||
            (col1 == '-' && col2 == '-' && col3 == 'Y')||
            (col1 == '-' && col2 == '-' && col3 == '-')){

         if(col1 == "Y"){
           var role = 1;
         }else if(col2 == "Y"){
          var role = 2;
         }else if(col3 == "Y"){
          var role = 3;
         }
         else{
           var role = 4;
         }

           // form input for request
        var form = new FormData();
        var userId = $(this).attr('data-id'); 
        form.append("updaterole", "true");
        form.append("role", role);

          // request for updating user role
        $.ajax({
          url: "../users.php?id=" + userId ,
          type: "POST",
          data:form,
          "processData": false,
          "mimeType": "multipart/form-data",
          "contentType": false,
          headers: {
            Authorization: "Bearer " + token,
          },
          success: function (response) {
            alert("Role updated");
            location.reload();
          },
          error: function () {
            alert("An error ocurred.Please try again");
          },
        });
        // request end

        }
        else{
          alert("Couldn't update role! Multiple roles assigned.");
        }      
      })
    })

      // request to fetch users
      $.ajax({
        url: "../users.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          // console.log(data);
          if (data) {
            // const user = JSON.parse(data);
            if (data && data.length) {
              var list = data
                .map((r, i) => {
                  return roleItem(r, i);
                 })
                  .join("");
                  
                }
          }
        },
        error: function () {
          alert("An error ocurred.Please try again");
        },
      });
      // request end

    }

    if($('#teamjoin-page').length){
      var token = localStorage.getItem("token");
      if (!token) {
        location.href = "Welcome.html";
      }

      // search request
      $(document).on("change","#search-box",function(e){
        var v = $(this).val();
        var data = $(this).data();
        // console.log(v);
        // console.log(data);
        var idx = Object.keys(data).find(d => parseInt(data[d].id) === parseInt(v));
        const team = data[idx];
        $('#team-name').text(team.name);
        $('#team-region').text(team.region);
        $('#description').text(team.description);
        // $('#createdby').text(team.cutag);
        $('#created').text(team.created)
        $("#show-team").show();
      }).on('click','#join-btn',function(e){

        var userid = localStorage.getItem("userid");
        var formData = new FormData();
        formData.append("team_id", $("#search-box").val() );
        formData.append("player_id",userid);
        formData.append("ptype","CARRY");
        formData.append("jointeam",true);
        
        $.ajax({
          url: "../users.php",
          type: "POST",
          data:formData,
          headers: {
            Authorization: "Bearer " + token,
          },
          contentType: false,
          mimeType: "multipart/form-data",
          processData: false,
          success: function (response) {
            alert("Joined successfully.");  
            
          },
          error: function () {
            alert("An error ocurred.Please try again");
            // location.href = "Welcome.html";
          },
        });
      }) 
       
       
      //  request to fetch teams
      $.ajax({
        url: "../teams.php",
        type: "POST",
        headers: {
          "content-type": "application/json",
          Authorization: "Bearer " + token,
        },
        success: function (data) {
          console.log(data);
          $('#search-box').data(data).append(`<option value="">Select team</option>`)
          data.map(t => {
            $('#search-box').append(`<option value="${t.id}">${t.name}</option>`)
            return t;

          })
        },
        error: function () {
          alert("An error ocurred.Please try again");
          // location.href = "Welcome.html";
        },
      });


    }

  });


