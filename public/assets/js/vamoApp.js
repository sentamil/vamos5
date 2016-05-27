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



if(ValidateIPaddress(logo)) {
	var parser    =   document.createElement('a');
	parser.href   =   document.location.ancestorOrigins[0];
	logo      	  =   parser.host;
}
var imgName= '/vamo/public/uploads/'+logo+'.small.png';

$('#imagesrc').attr('src', imgName);
var gmarkers=[];
var ginfowindow=[];
var geomarker=[];
var geoinfo=[];

var app = angular.module('mapApp',['ui.bootstrap']);


  /*! Idle Timer - v0.9.2 - 2013-08-04
* https://github.com/mikesherov/jquery-idletimer
* Copyright (c) 2013 Paul Irish; Licensed MIT */
 (function(e){e.idleTimer=function(t,i,d){d=e.extend({startImmediately:!0,idle:!1,enabled:!0,timeout:3e4,events:"mousemove keydown DOMMouseScroll mousewheel mousedown touchstart touchmove"},d),i=i||document;var l=e(i),a=l.data("idleTimerObj")||{},o=function(t){"number"==typeof t&&(t=void 0);var l=e.data(t||i,"idleTimerObj");l.idle=!l.idle;var a=+new Date-l.olddate;if(l.olddate=+new Date,l.idle&&d.timeout>a)return l.idle=!1,clearTimeout(e.idleTimer.tId),d.enabled&&(e.idleTimer.tId=setTimeout(o,d.timeout)),void 0;var m=e.Event(e.data(i,"idleTimer",l.idle?"idle":"active")+".idleTimer");e(i).trigger(m)},m=function(e){var t=e.data("idleTimerObj")||{};t.enabled=!1,clearTimeout(t.tId),e.off(".idleTimer")};if(a.olddate=a.olddate||+new Date,"number"==typeof t)d.timeout=t;else{if("destroy"===t)return m(l),this;if("getElapsedTime"===t)return+new Date-a.olddate}l.on(e.trim((d.events+" ").split(" ").join(".idleTimer ")),function(){var t=e.data(this,"idleTimerObj");clearTimeout(t.tId),t.enabled&&(t.idle&&o(this),t.tId=setTimeout(o,t.timeout))}),a.idle=d.idle,a.enabled=d.enabled,a.timeout=d.timeout,d.startImmediately&&(a.tId=setTimeout(o,a.timeout)),l.data("idleTimer","active"),l.data("idleTimerObj",a)},e.fn.idleTimer=function(t,i){return i||(i={}),this[0]?e.idleTimer(t,this[0],i):this}})(jQuery);





$(function() {
    // Set idle time
    document.cookie = "timer = 6000 ms";
    localStorage.setItem('duck', 'entering');
    $( document ).idleTimer( 60000 );
    //console.log(' inside the timer ');
});

$(function() {
    $( document ).on( "idle.idleTimer", function(event, elem, obj){
       var cookie_s = document.cookie;
       var local_val = sessionStorage.getItem('duck');
       //console.log(' cooliew '+cookie_s);
       if(!cookie_s)
        window.location.href = "login"
    });  
});


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


