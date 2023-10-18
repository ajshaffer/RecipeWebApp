<?php
//mealplan.php




?>
<!DOCTYPE html>
<html>
 <head>
  <title>Meal Planner</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" /> <!--full calendar Stylesheet -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" /> <!--Bootstrap stylesheet -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> <!--jquery library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script><!-- j query user interface -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script> <!-- Javascript library for full calendar plugin -->
  <script>
   
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({ //activate full calendar 
    editable:true, //drag and resize events on calendar 
    header:{ //buttons at the top of the plugin 
     left:'prev,next today',
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: 'load.php', //display events table data, load.php document
    selectable:true, //allows user to highlight multiple days by clicking or dragging cursor
    selectHelper:true, //draw a place holder while user drags an event 
    select: function(start, end, allDay) //add new events 
    {
     var title = prompt("Enter Event Title"); //what pops up when you add an event
     if(title) //checks if title has some value
     {
      var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss"); //generate start date time and store into a variable
      var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");//generate end date time and store into a variable 
      $.ajax({
       url:"insert.php", //send requests to this page
       type:"POST", //define method for sending data
       data:{title:title, start:start, end:end}, //sending title, start and end date data to server
       success:function() //this function is called if request is completed successfully 
       {
        calendar.fullCalendar('refetchEvents'); //refreshes calendar data
        alert("Added Successfully"); //message when event is added
       }
      })
     }
    },
    editable:true, //allows to edit event data
    eventResize:function(event) //calls function when there is a resize event
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function(){
       calendar.fullCalendar('refetchEvents');
       alert('Event Update');
      }
     })
    },

    eventDrop:function(event) //drag and drop event
    {
     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
     var title = event.title;
     var id = event.id;
     $.ajax({
      url:"update.php",//sed=nd request to this page
      type:"POST",
      data:{title:title, start:start, end:end, id:id},
      success:function()
      {
       calendar.fullCalendar('refetchEvents'); //reloads data on calendar
       alert("Event Updated");
      }
     });
    },

    eventClick:function(event) //execute ajax request when you click on event 
    {
     if(confirm("Are you sure you want to remove it?")) //if the user clicks confirm
     {
      var id = event.id;
      $.ajax({
       url:"delete.php",
       type:"POST",
       data:{id:id},
       success:function()
       {
        calendar.fullCalendar('refetchEvents');
        alert("Event Removed");
       }
      })
     }
    },

   });
  });
   
  </script>
 </head>
 <body>
  <br />
  <h2 align="center"><a href="#">Meal Planner</a></h2>
  <br />
  <div class="container">
   <div id="calendar"></div>
  </div>
 </body>
</html>