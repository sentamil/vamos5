var total = 0;
var chart = null;

//menu loading
$("#testLoad").load("../public/menu");

//logo loading
var logo 	=	document.location.host;

if(Number(logo)>0 && Number(logo)<255) {
	var parser 		= 	document.createElement('a');
	parser.href 	= 	document.location.ancestorOrigins[0];
	logo 			= 	parser.host;
}


// console.log(parser.host)
var imgName	= 	'/vamo/public/assets/imgs/'+logo+'.small.png';
$('#imagesrc').attr('src', imgName);

// var gmarkers=[];
// var ginfowindow=[];
// var geomarker=[];
// var geoinfo=[];
var app = angular.module('mapApp',['ui.bootstrap']);

