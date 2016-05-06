

<script type="text/javascript">

$(function () {
    $('.check').on('click', function () {
        var valu = $('.check').each(function(){});
        var count = 0;
        var counter = 0;
        if(list.length)
        {
          for (var a in list){
              if(valu[0].checked == true)
                $(questionCheckBox[list[a]]).each(function(){
                     this.checked = true;
                     // $(this).parent('div').appendTo('#selectedItems'); 
                });

              else if (valu[0].checked != true)
                $(questionCheckBox[list[a]]).each(function(){ this.checked = false; 
                  // $(this).parent('div').appendTo('#unSelectedItems');
                });

          };
          
      }
      else
      {
        
        if($( ".searchkey" ).val())
          for (var a in value){
              $(questionCheckBox[counter]).each(function(){ this.checked = false; 
              // $(this).parent('div').appendTo('#unSelectedItems');
            });
            counter++;
          }
        else
          for (var a in value){
            if(valu[0].checked == true)
              $(questionCheckBox[count]).each(function(){ 
                this.checked = true; 
                // $(this).parent('div').appendTo('#selectedItems');
              });
            else if (valu[0].checked != true)
              $(questionCheckBox[count]).each(function(){ this.checked = false; 
                // $(this).parent('div').appendTo('#unSelectedItems');
              });
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

$(function(){
    // console.log(' inside the onload ');
    $(questionCheckBox).each(function(index){
        $(questionCheckBox[index]).each(function(){
            if(this.checked == true){
              $(questionCheckBox[index]).parent('div').appendTo('#selectedItems')
            } 
        });
    });
});


// function for individual check box click
// $(function(){
//   $(questionCheckBox).on('click',function(clickindex){
//     var counting = 0;
//       console.log(' arunss '+this.checked+'--->'+clickindex);
//       $(questionCheckBox).each(function(){

//           if(questionCheckBox[counting].value == clickindex.delegateTarget.value)
//           {
//             if(this.checked == true)
//               $(questionCheckBox[counting]).parent('div').appendTo('#selectedItems');
//             else if(this.checked == false)
//               // $('#selectedItems').parent('div').appendTo(questionCheckBox[counting]);
//              $(questionCheckBox[counting]).parent('div').appendTo('#unSelectedItems');

//           }
//           counting++;
//       });
      // if(clickindex.delegateTarget.value == questionCheckBox[0]){
          // $(questionCheckBox[]).parent('div').appendTo('#selectedItems')
      //  
//   })
// });


</script>
	


