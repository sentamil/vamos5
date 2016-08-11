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


function getParameterByName(name) {
      name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
      var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
          results = regex.exec(location.search);
      return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
})

// (function(){

// var exportTable = function(){
// var link = function($scope, elm, attr){
// $scope.$on(‘export-pdf’, function(e, d){
//       elm.tableExport({type:’pdf’, escape:’false’});
//  });
// $scope.$on(‘export-excel’, function(e, d){
//        elm.tableExport({type:’excel’, escape:false});
//  });
// $scope.$on(‘export-doc’, function(e, d){
//      elm.tableExport({type: ‘doc’, escape:false});
//  });
// }
// return {
//   restrict: ‘C’,
//   link: link
//    }
//  }
// app.directive(‘exportTable’, exportTable);
// })();

