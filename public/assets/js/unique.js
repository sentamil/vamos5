//menu loading

$("#testLoad").load("../public/menu");
    
//logo img

var logo =document.location.host;
var imgName= '/vamo/public/assets/imgs/'+logo+'.small.png';
$('#imagesrc').attr('src', imgName);

// date and time function

$(function () {
  $('#dateFrom, #dateTo').datetimepicker({
    format:'YYYY-MM-DD',
    useCurrent:true,
    pickTime: false
  });
  $('#timeFrom').datetimepicker({
    pickDate: false,
    useCurrent:true
  });
  $('#timeTo').datetimepicker({
    pickDate: false,
    useCurrent:true,

  });
});

//translate function

function googleTranslateElementInit() 
  {
       new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
  }   

 // $(document).ready(function(){
 //        $('#minmax').click(function(){
 //            $('#contentmin').animate({
 //                height: 'toggle'
 //            },500);
 //        });
 //    });   