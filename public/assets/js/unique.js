//menu loading

$("#testLoad").load("../public/menu");
    
//logo img

var logo =document.location.host;
var imgName= '/vamo/public/assets/imgs/'+logo+'.small.png';
$('#imagesrc').attr('src', imgName);

// date and time function

$(function () {
  $('#dateFromh, #dateToh').datetimepicker({
    format:'YYYY-MM-DD',
    useCurrent:true,
    pickTime: false
  });
  $('#timeFromh').datetimepicker({
    pickDate: false,
    useCurrent:true
  });
  $('#timeToh').datetimepicker({
    pickDate: false,
    useCurrent:true,

  });
});

//translate function

function googleTranslateElementInit() 
  {
       new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
  }   

 $(document).ready(function(){
        $('#minmax').click(function(){
            $('#contentmin').animate({
                height: 'toggle'
            },500);
        });
    });   