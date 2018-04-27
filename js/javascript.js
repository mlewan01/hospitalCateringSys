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
    if(now.getHours() < se[0]) { // same day reload
        sec = se[0];
        lo('getHours: '+now.getHours());
    }else if(now.getHours() < se[1]){
      sec = se[1];
      lo('getHours: '+now.getHours());
    }else if(now.getHours() < se[2]){
      sec = se[2];
      lo('getHours: '+now.getHours());
    }else { // addjustment for next day reload calculation
      sec = se[0];
      // ofset = 1; // for testing with seconds
      then.setDate(now.getDate() + 1);
      lo('getHours: '+now.getHours());
    }
    lo('time set: '+sec);
    then.setHours(sec);
    then.setMinutes(0);
    then.setSeconds(0);

    var timeout = (then.getTime() - now.getTime());
    setTimeout(function() {
      window.location.replace('');
      // ajax();
    }, timeout);
    now.setTime(timeout);
    lo('meal ends in: '+now.getHours()+'h '+now.getMinutes()+'m');
}
function showTime() {
    var now = new Date();
    var then = new Date();
    var se = [10, 14, 18]; // end of meal times, breakfast lunch and supper acordingly
    var sec = 0; var ofset = 0;
    if(now.getHours() < se[0]) { // same day reload
        sec = se[0];
    }else if(now.getHours() < se[1]){
      sec = se[1];
    }else if(now.getHours() < se[2]){
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
      $("#time").html('Current meal ends in: <b>'+then.getHours()+'</b>h <b>'+then.getMinutes()+'</b>m <b>'+then.getSeconds()+'</b>s');
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
function aPatientInfo(){
  info = "nothing or error...";
  id = $("#patient_id").text();
  info = $("#patient_info").text();
  console.log(info);
  where = "servers/patient_info_get.php?p_id="+id;
  lo(where);
  setInterval(function() {
    $.ajax({
      url: where,
      type: 'GET',
      dataType: 'html',
      success: function(response){
        lo('ajax success '+where);
        if(response != '' && info != ''){
          $("#patient_info").show();
        }else $("#patient_info").hide();
        $("#patient_info").html(response);
        // $("#patient_info").html("test");
        if(info != response){
          lo( 'new info' );
          lo( 'info '+info+' respon '+response);
          info = response;
          window.location.replace('');
        }else{
          lo( 'same info' );
          lo( 'info '+info+' respon '+response);
          if(response != '' && info != ''){
            $("#patient_info").show();
          }else $("#patient_info").hide();
        }
      },
      error: function(xhr, error, text){
        // lo('ajax error...'+text+xhr.status);
      }
    });
  }, 2000);
}
function ajax(whe = window.location.href){
  lo('in ajax search '+window.location.search);
  if(window.location.search == ''){
    where = whe+'?is=ajax'
  }else{
    where = whe+'&is=ajax'
  }
  lo('ajax path: '+where);
  $.ajax({
    url: where,
    type: 'GET',
    dataType: 'html',
    success: function(response){
      lo('ajax success '+where);
      $("#view_orders").html('');
      $("#view_orders").html(response);
      $('#error').html('');
      $('#error').hide();
    },
    error: function(xhr, error, text){
      lo('ajax error...'+text+xhr.status);
      $("#view_orders").html('<div id="error"></div>');
      $('#error').show();
      //$('#errorInfo').show();
      $('#error').html('error during ajax call...');
    }
  });
}
function cancelOrders(){
  // lo('function cancelOrders()');
  // href = window.location.href;
  // host = window.location.host;
  // path = window.location.pathname;
  // lo('href '+ href);
  // lo('host '+ host);
  // lo('path '+ path);
  // link = host+path+'?is=ajax';
  // lo('cancel order path: '+link);
  // ajax(link);
  // window.location.replace('?is=ajax&bid=1&pid=6');
  where = "index.php"+'?is=ajax'+'&bid=1&pid=6';
  // lo("cancelOrders ajax link : "+where);
  $.ajax({
    url: where,
    type: 'GET',
    dataType: 'html',
    success: function(response){
      lo('ajax success '+where);
    },
    error: function(xhr, error, text){
      // lo('ajax error...'+text+xhr.status);
    }
  });
}
$(document).ready(function() {
  if (window.location.search == '') {
    lo('home page');
    refreshAt();
    showTime();
    aPatientInfo();
    // aMenue();
    // cancelOrders();
  }else if(window.location.search == '?page=view_order'){
    lo('page view_order');
    aOrders();
  }// }else if(window.location.search == '?page=bed_pat_diet'){
  //   // $('#form9').on('submit', function () {
  //   //   // might not work very well if tested on the same machine.
  //   //   // it does work well if the call comes from differet machine
  //   //   // lo('Form submitted!');
  //   //   if(clicked === "Save"){
  //   //     // cancelOrders();
  //   //   }else{
  //   //   }
  //   // });
  // }
  else {
    lo('different page...');
  }
  lo('path: '+window.location.pathname);
  lo('query: '+window.location.search);
});
