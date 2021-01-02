
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


});

