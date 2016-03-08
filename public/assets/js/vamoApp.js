var total = 0;
var chart = null;

//menu loading
$("#testLoad").load("../public/menu");

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
	logo      =   parser.host;
}
var imgName= '/vamo/public/assets/imgs/'+logo+'.small.png';

$('#imagesrc').attr('src', imgName);
var gmarkers=[];
var ginfowindow=[];
var geomarker=[];
var geoinfo=[];
var app = angular.module('mapApp',['ui.bootstrap']);

app.run(function($rootScope) {
    $rootScope.test = new Date();
    console.log($rootScope.test)
});