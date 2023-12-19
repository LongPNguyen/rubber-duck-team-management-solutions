jQuery(document).ready(function ($) {
  var workStartTime;
  var workTimerInterval; // Variable to store the work timer interval
  var onBreak = false;
  var breakStartTime;
  var breakEndTime;

  function updateClock() {
    var now = new Date();
    var days = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ];
    var day = days[now.getDay()];
    var date = now.getDate();
    var month = now.getMonth() + 1;
    var year = now.getFullYear();

    var hours = now.getHours();
    var minutes = now.getMinutes();
    var ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? "0" + minutes : minutes;

    var dateString = day + ", " + month + "/" + date + "/" + year;
    var timeString = hours + ":" + minutes + " " + ampm;
    $("#realTimeClock").text(timeString);
    $("#realTimeDate").text(dateString);
  }

  function updateWorkTimer() {
    if (!onBreak) {
      var now = new Date();
      var elapsed = now - workStartTime;
      var hours = Math.floor(elapsed / 3600000);
      var minutes = Math.floor((elapsed % 3600000) / 60000);
      var seconds = Math.floor((elapsed % 60000) / 1000);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      $("#workTimer").text(hours + ":" + minutes + ":" + seconds);
    }
  }

  function startWorkTimer() {
    workStartTime = new Date();
    workTimerInterval = setInterval(updateWorkTimer, 1000);
    $("#workTimer").show();
  }

  function stopWorkTimer() {
    clearInterval(workTimerInterval);
    workTimerInterval = null;
  }

  $("#clockInBtn").click(function () {
    $("#statusMessage")
      .addClass("alert-success")
      .text("Clocked In Successfully")
      .show();
    startWorkTimer();
    $(this).hide();
    $("#startBreakBtn").show();
    $("#clockOutBtn").show();
  });

  $("#startBreakBtn").click(function () {
    onBreak = true;
    onBreak = true;
    breakStartTime = new Date(); // Record break start time
    $("#statusMessage")
      .removeClass("alert-success")
      .addClass("alert-info")
      .text("On Break")
      .show();
    $("#startBreakBtn").hide();
    $("#endBreakBtn").show();

    // Timer continues but doesn't update due to onBreak flag
  });

  $("#endBreakBtn").click(function () {
    onBreak = false;
    breakEndTime = new Date(); // Record break end time
    var breakDuration = breakEndTime - breakStartTime;
    workStartTime = new Date(workStartTime.getTime() + breakDuration); // Adjust work start time
    $("#statusMessage")
      .removeClass("alert-info")
      .addClass("alert-success")
      .text("Break Ended")
      .show();
    // Timer updates resume
    $(this).hide();
    $("#startBreakBtn").show();
  });

  $("#clockOutBtn").click(function () {
    $("#statusMessage")
      .addClass("alert-warning")
      .text("Clocked Out Successfully")
      .show();
    stopWorkTimer();
    $(this).hide();
    $("#clockInBtn").show();
    $("#startBreakBtn").hide();
    $("#endBreakBtn").hide();
  });

  setInterval(updateClock, 1000);
});
