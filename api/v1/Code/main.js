
$(document).ready(function(){
    //code here
    var token = localStorage.getItem('token');
    var role = localStorage.getItem('role');
    var team = localStorage.getItem('team');
    console.log('Team',team);
    if(role <= 2) {
        $('#host-btn').show();
    } else {
        
        $('#host-btn').hide();
    }

    $('#team').click(function(){

        if(team == "false"){
            location.href = "TeamDefault.html";
        }else{
            location.href = "Team.html";
        }
      
    })
    if($('#team-page').length) {
          
          var token = localStorage.getItem("token");
    
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
                $('#created').text(team.created)
                $('#createdby').text(team.cutag)
              }
            },
            error: function () {
              alert("An error ocurred.Please try again");
            },
          });
    }


});

