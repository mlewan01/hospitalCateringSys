/* console logging helper for easier typing
 */
function lo(msg){
  console.log(msg);
}
/* Andrev Moren published on www.stackoverflow.com accesse 20-04-2018 https://stackoverflow.com/questions/1217929/how-to-automatically-reload-a-web-page-at-a-certain-time
 * reloads page on given times
 */
function refreshAt() {
    var now = new Date();
    var then = new Date();
    var se = [10, 14, 18]; // end of meal times, breakfast lunch and supper acordingly
    var sec = 0; var ofset = 0;
    if(now.getSeconds() <= se[0]) { // same day reload
        sec = se[0];
    }else if(now.getSeconds() <= se[1]){
      sec = se[1];
    }else if(now.getSeconds() <= se[2]){
      sec = se[2];
    }else { // addjustment for next day reload calculation
      sec = se[0];
      // ofset = 1; // for testing with seconds
      then.setDate(now.getDate() + 1);
    }

    then.setHours(sec);
    then.setMinutes(0);
    then.setSeconds(0);

    var timeout = (then.getTime() - now.getTime());
    setTimeout(function() {
      // window.location.reload(true);
      ajax();
    }, timeout);
    now.setTime(timeout); lo('meal ends in: '+now.getHours()+'h '+now.getMinutes()+'m');
}
function showTime() {
    var now = new Date();
    var then = new Date();
    var se = [10, 14, 18]; // end of meal times, breakfast lunch and supper acordingly
    var sec = 0; var ofset = 0;
    if(now.getSeconds() <= se[0]) { // same day reload
        sec = se[0];
    }else if(now.getSeconds() <= se[1]){
      sec = se[1];
    }else if(now.getSeconds() <= se[2]){
      sec = se[2];
    }else { // addjustment for next day reload calculation
      sec = se[0];
      // ofset = 1; // for testing with seconds
      then.setDate(now.getDate() + 1);
    }

    then.setHours(sec);
    then.setMinutes(0);
    then.setSeconds(0);

    var timeout = (then.getTime() - now.getTime());
    then.setTime(timeout);
    setInterval(function() {
      then.setTime((then.getTime() - 1000));
      $("#time").html('current meal ends in: <b>'+then.getHours()+'</b>h <b>'+then.getMinutes()+'</b>m <b>'+then.getSeconds()+'</b>s');
    }, 1000);
}
/*
 *	makes api call in order to retrive the weather data in json format
 *	formats them and updates the HTML
 */
function aOrders(){
  setInterval(function() {
    ajax();
  }, 1000);
}
function aMenue(){
  setInterval(function() {
    ajax();
  }, 1000);
}
function ajax(whe = ''){
  whe = window.location.href;
  if(window.location.search == ''){
    where = whe+'?is=ajax'
  }else{
    where = whe+'&is=ajax'
  }

  $.ajax({
    url: where,
    type: 'GET',
    dataType: 'html',
    success: function(response){
      lo('ajax success '+where);
      $("#contactus_content").html('');
      $("#contactus_content").html(response);
      $('#error').html('');
      $('#error').hide();
    },
    error: function(xhr, error){
      lo('ajax error...');
      $('#error').show();
      //$('#errorInfo').show();
      $('#error').html('error during ajax call...');
    }
  });
}
$(document).ready(function() {
  if (window.location.search == '') {
    lo('home page');
    refreshAt();
    showTime();
    // aMenue();
  }else if(window.location.search == '?page=view_order'){
    lo('page view_order');
    aOrders();
  }
  else {
    lo('different page...');
  }
  lo('path: '+window.location.pathname);
  lo('query: '+window.location.search);
});
