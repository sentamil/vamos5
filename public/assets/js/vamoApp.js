var total = 0;
var tankSize=0;
var fuelLtr=0;
var chart = null;

//menu loading
// $("#testLoad").load("../public/menu");

//logo loading

var logo =document.location.host;

function ValidateIPaddress(ipaddress)   
{  
	var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;  
	if(ipaddress.match(ipformat)) {
	  return (true)  
	}  
	// alert("You have entered an invalid IP address!")  
	return (false)  
}  

// logo = 'localhost';

if(ValidateIPaddress(logo)) {
	var parser    =   document.createElement('a');
	parser.href   =   document.location.ancestorOrigins[0];
	logo      	  =   parser.host;
}
var imgName= context+'/public/uploads/'+logo+'.small.png';

var folder = imgName;



var wwwSplit = logo.split(".")
if(wwwSplit[0]=="www"){
  wwwSplit.shift();
  imgName = context+'/public/uploads/'+wwwSplit[0]+'.'+wwwSplit[1]+'.small.png';
}

  $('#imagesrc').attr('src', imgName);


var gmarkers=[];
var ginfowindow=[];
var geomarker=[];
var geoinfo=[];

var app = angular.module('mapApp',['ui.bootstrap']);
// var app = angular.module('mapApp',['ui.bootstrap', 'ngSanitize']);


  /*! Idle Timer - v0.9.2 - 2013-08-04
* https://github.com/mikesherov/jquery-idletimer
* Copyright (c) 2013 Paul Irish; Licensed MIT */
 (function(e){e.idleTimer=function(t,i,d){d=e.extend({startImmediately:!0,idle:!1,enabled:!0,timeout:3e4,events:"mousemove keydown DOMMouseScroll mousewheel mousedown touchstart touchmove"},d),i=i||document;var l=e(i),a=l.data("idleTimerObj")||{},o=function(t){"number"==typeof t&&(t=void 0);var l=e.data(t||i,"idleTimerObj");l.idle=!l.idle;var a=+new Date-l.olddate;if(l.olddate=+new Date,l.idle&&d.timeout>a)return l.idle=!1,clearTimeout(e.idleTimer.tId),d.enabled&&(e.idleTimer.tId=setTimeout(o,d.timeout)),void 0;var m=e.Event(e.data(i,"idleTimer",l.idle?"idle":"active")+".idleTimer");e(i).trigger(m)},m=function(e){var t=e.data("idleTimerObj")||{};t.enabled=!1,clearTimeout(t.tId),e.off(".idleTimer")};if(a.olddate=a.olddate||+new Date,"number"==typeof t)d.timeout=t;else{if("destroy"===t)return m(l),this;if("getElapsedTime"===t)return+new Date-a.olddate}l.on(e.trim((d.events+" ").split(" ").join(".idleTimer ")),function(){var t=e.data(this,"idleTimerObj");clearTimeout(t.tId),t.enabled&&(t.idle&&o(this),t.tId=setTimeout(o,t.timeout))}),a.idle=d.idle,a.enabled=d.enabled,a.timeout=d.timeout,d.startImmediately&&(a.tId=setTimeout(o,a.timeout)),l.data("idleTimer","active"),l.data("idleTimerObj",a)},e.fn.idleTimer=function(t,i){return i||(i={}),this[0]?e.idleTimer(t,this[0],i):this}})(jQuery);


//check the diff of the two dates 
  function daydiff(first, second) {
      return Math.round((second-first)/(1000*60*60*24));
  }


$(function() {
    // Set idle time
    document.cookie = "timer = 6000 ms";
    
    $( document ).idleTimer( 60000 );
    //console.log(' inside the timer ');
});

$(function() {
    $( document ).on( "idle.idleTimer", function(event, elem, obj){
       var cookie_s = document.cookie;
       
       //console.log(' cooliew '+cookie_s);
       if(!cookie_s)
        window.location.href = "login"
    });  
});


//loading start function
  var startLoading    = function () {
    $('#status').show(); 
    $('#preloader').show();
  };

  //loading stop function
  var stopLoading   = function () {
    $('#status').fadeOut(); 
    $('#preloader').delay(350).fadeOut('slow');
    $('body').delay(350).css({'overflow':'visible'});
  };


function splitColon(spValue){
  return spValue.split(':');
}


function sortByDate(field){
  var sort = {       
                sortingOrder : field,
                reverse : false
    };
    return sort;
}

function radians(n) {
  return n * (Math.PI / 180);
}
function degrees(n) {
  return n * (180 / Math.PI);
}

function getBearing(startLat,startLong,endLat,endLong){
  
  startLat = radians(startLat);
  startLong = radians(startLong);
  endLat = radians(endLat);
  endLong = radians(endLong);

  var dLong = endLong - startLong;

  var dPhi = Math.log(Math.tan(endLat/2.0+Math.PI/4.0)/Math.tan(startLat/2.0+Math.PI/4.0));
  if (Math.abs(dLong) > Math.PI){
    if (dLong > 0.0)
       dLong = -(2.0 * Math.PI - dLong);
    else
       dLong = (2.0 * Math.PI + dLong);
  }

  return (degrees(Math.atan2(dLong, dPhi)) + 360.0) % 360.0;
}


function getParameterByName(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
          results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

function checkXssProtection(str){

  var regex     = /<\/?[^>]+(>|$)/g;
  var enc       = encodeURI(str);
  var dec       = decodeURI(str);
  var replaced  = enc.search(regex) >= 0;
  var replaced1 = dec.search(regex) >= 0;

  return (replaced == false && replaced1 == false) ? true : false ;
  // if(replaced == false && replaced1 == false){
      
    
  //   return true;

  // } else {

    
  //   return false;

  // }

}


function todaydate(){
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd<10) {
      dd='0'+dd
  } 

  if(mm<10) {
      mm='0'+mm
  } 

  return mm+'/'+dd+'/'+yyyy;
}


function utcFormat(d,t){return new Date(d+' '+t).getTime();}

function removeSpace_Join(valu){return valu.split(' ').join('_')}

//common directive for sorting
app.directive("customSort", function() {
return {
    restrict: 'A',
    transclude: true,    
    scope: {
      order: '=',
      sort: '='
    },
    template : 
      ' <a ng-click="sort_by(order)" style="color: #555555;">'+
      '    <span ng-transclude></span>'+
      '    <i ng-class="selectedCls(order)"></i>'+
      '</a>',
    link: function(scope) {
                
    // change sorting order
    scope.sort_by = function(newSortingOrder) {       
        var sort = scope.sort;
        
        if (sort.sortingOrder == newSortingOrder){
            sort.reverse = !sort.reverse;
        }                    

        sort.sortingOrder = newSortingOrder;        
    };
    
   
    scope.selectedCls = function(column) {
        if(column == scope.sort.sortingOrder){
            return ('icon-chevron-' + ((scope.sort.reverse) ? 'down' : 'up'));
        }
        else{            
            return'icon-sort' 
        } 
    };      
  }// end link
}
});

app.directive('tooltips', function ($document, $compile) {
  return {
    restrict: 'A',
    scope: true,
    link: function (scope, element, attrs) {

      var tip = $compile('<span ng-class="tipClass">'+
        '<table class="tabStyles">'+
        // '<tr>'+'<th>'+'</th>'+'<th>'+'</th>'+'<th>'+'</th>'+'</tr>'+
        '<tr>'+'<td colspan="2">'+'{{ loc.date | date:"yyyy-MM-dd HH:mm:ss" }}'+'</td>'+'<td colspan="2">'+'{{ loc.shortName }}'+'</td>'+'</tr>'+
        
        '<tr>'+'<td>'+'Odo(kms)'+'</td>'+'<td>'+'{{ loc.odoDistance }}'+'</td>'+'<td>'+'Covered(kms)'+'</td>'+'<td>'+'{{ loc.distanceCovered}}'+'</td>'+'</tr>'+
        '<tr>'+'<td>'+'Ignition'+'</td>'+'<td>'+'{{ loc.ignitionStatus }}'+'</td>'+'<td>'+'MaxSpeed(kms)'+'</td>'+'<td>'+'{{ loc.overSpeedLimit }}'+'</td>'+'</tr>'+
        '<tr>'+'<td>'+'DeviceVolt'+'</td>'+'<td>'+'{{ loc.deviceVolt }}'+'</td>'+'<td>'+'Speed(kms)'+'</td>'+'<td>'+'{{loc.speed}}'+'</td>'+'</tr>'+
        '<tr>'+'<td colspan="4">'+'{{ loc.address }}'+'</td></tr>'+
        '</table>'+
        '</span>')(scope),
          tipClassName = 'tooltips',
          tipActiveClassName = 'tooltips-show';

      scope.tipClass = [tipClassName];
      scope.text = attrs.tooltips;
      
      if(attrs.tooltipsPosition) {
        scope.tipClass.push('tooltips-' + attrs.tooltipsPosition);
      }
      else {
       scope.tipClass.push('tooltips-down'); 
      }
      $document.find('#sidebar-wrapper').append(tip);
      
      element.bind('mouseover', function (e) {
        tip.addClass(tipActiveClassName);
        
        var pos = e.target.getBoundingClientRect(),
            offset = tip.offset(),
            tipHeight = tip.outerHeight(),
            tipWidth = tip.outerWidth(),
            elWidth = pos.width || pos.right - pos.left,
            elHeight = pos.height || pos.bottom - pos.top,
            tipOffset = 10;
        
        if(tip.hasClass('tooltips-right')) {
          offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
          offset.left = pos.right + tipOffset;
        }
        else if(tip.hasClass('tooltips-left')) {
          offset.top = pos.top - (tipHeight / 2) + (elHeight / 2);
          offset.left = pos.left - tipWidth - tipOffset;
        }
        else if(tip.hasClass('tooltips-down')) {
          offset.top = pos.top + elHeight + tipOffset;
          offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
        }
        else {
          offset.top = pos.top - tipHeight - tipOffset;
          offset.left = pos.left - (tipWidth / 2) + (elWidth / 2);
        }

        tip.offset(offset);
      });
      
      element.bind('mouseout', function () {
        tip.removeClass(tipActiveClassName);
      });

      tip.bind('mouseover', function () {
        tip.addClass(tipActiveClassName);
      });

      tip.bind('mouseout', function () {
        tip.removeClass(tipActiveClassName);
      });

      
    }
  }
});
