<!doctype html>
<html>
<head>
  <?php
  session_start();

  if(isset($_POST['token'])){
    $_SESSION['checkToken'] = $_POST['token'];
  }
  if($_SESSION['token'] != $_SESSION['checkToken']){
    die("Request forgery detected");
  }
  $isGuest=true;


  if($_POST['guest'] != "guest"){
    $isGuest = false;
    if (isset($_POST['name']) && isset($_POST['pw'])) {
      $_SESSION['user'] = $_POST['name'];
      $user = $_SESSION['user'];
      $_SESSION['pass'] = $_POST['pw'];
      $pw = $_SESSION['pass'];
    } else if(isset($_SESSION['user'])){
      $user = $_SESSION['user'];
      $pw = $_SESSION['pass'];
    }
    elseif(!$isGuest){
      session_destroy();
      header("Location: login.php?success=false");
      exit;
    }
  }
  else{
    $_SESSION['user'] = "guest";
    $user = "guest";
    $_SESSION['pass'] = "";
    $pw = "";
  }
  $mysqli = new mysqli('localhost', 'ubuntu', 'Cville4821', 'module6');

  if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
  }
  if(mysqli_num_rows(mysqli_query($mysqli, "SELECT name FROM registeredusers WHERE name = '$user'" )) >0 ){

    $result = mysqli_query($mysqli, "SELECT password FROM registeredusers WHERE name  = '$user'" );
    $hashedPass = $result->fetch_row();

    if(crypt($pw,$hashedPass[0]) != $hashedPass[0]){
      session_destroy();
      header("Location: login.php?success=false");
      exit;
    }

  }else{
    session_destroy();
    header("Location: login.php?success=false");
    exit;
  }
  $_SESSION['token2'] = substr(md5(rand()), 0, 10);
  echo "Logged in as: ".$user;
  ?>


  <link rel="stylesheet" type="text/css" href="calendar.css">
  <script src="jquery.js"></script>
  <script src="sprintf_plugin.js"></script>
  <script src="calendarView.js"></script>
  <script src="clientajax.js"></script>
  <title> Module 6 Calendar </title>
</head>
<body>
  <div id="month_view"></div>
  <div id="change_buttons">
    <button id="prev_btn" class="prev_btn" onclick="ajaxFunction("<?php $SESSION['user']?>")"> Previous Month </button>
    <button id="next_btn" class="next_btn" onclick="ajaxFunction("<?php $SESSION['user']?>")"> Next Month </button>
  </div>
  <div id="add_event">
  </div>


  <script type="text/javascript">
  //Initialize calendar to current month and year
  var user = "<?php $_SESSION['user']?>";
  var today = new Date();
  var month = today.getMonth();
  var year = today.getFullYear();
  var cal = new Calendar(month,year);
  cal.generateHTML();
  document.getElementById("month_view").innerHTML = cal.getHTML();
  ajaxFunction(user);
  //Setting up listeners for prev/next month buttons
  var currentMonth = new Month(year, month);
  document.getElementById("prev_btn").addEventListener("click",function(event){
    currentMonth = currentMonth.prevMonth();
    var cal = new Calendar(currentMonth.month,currentMonth.year);
    cal.generateHTML();
    document.getElementById("month_view").innerHTML = cal.getHTML();
    ajaxFunction(user);
  },false);
  document.getElementById("next_btn").addEventListener("click",function(event){
    currentMonth = currentMonth.nextMonth();
    var cal = new Calendar(currentMonth.month,currentMonth.year);
    cal.generateHTML();
    document.getElementById("month_view").innerHTML = cal.getHTML(); 
    ajaxFunction(user);
  },false);


  


  </script>

  
</body>
</html>

