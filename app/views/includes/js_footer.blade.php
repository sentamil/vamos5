

<script type="text/javascript">

$(function () {
    $('.check').on('click', function () {
        var valu = $('.check').each(function(){});
        var count = 0;
        if(list.length)
        {
          for (var a in list){
              if(valu[0].checked == true)
                $(questionCheckBox[list[a]]).each(function(){ this.checked = true; });
              else if (valu[0].checked != true)
                $(questionCheckBox[list[a]]).each(function(){ this.checked = false; });
          };
          
      }
      else
      {
          for (var a in value){
            if(valu[0].checked == true)
              $(questionCheckBox[count]).each(function(){ this.checked = true; });
            else if (valu[0].checked != true)
              $(questionCheckBox[count]).each(function(){ this.checked = false; });
            count++;
          };
        }
    }); 
});


$( ".searchkey" ).keyup(function() {
    list = [];
    var valThis = $(this).val().toLowerCase();
     $('.vehiclelist>input').each(function(index){
         var text = $(this).val().toLowerCase();
         if(text.indexOf(valThis) >= 0) {
          $(this).parent('div').fadeIn();
          list.push(index);
         }
         else{
          $(this).parent('div').fadeOut();
         }

    });
})

</script>
	


