<script src="../vendor/jquery/dist/jquery.min.js"></script>
<script src="../vendor/jquery-ui/jquery-ui.min.js"></script>
<script src="../vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../vendor/jquery-flot/jquery.flot.js"></script>
<script src="../vendor/jquery-flot/jquery.flot.resize.js"></script>
<script src="../vendor/jquery-flot/jquery.flot.pie.js"></script>
<script src="../vendor/flot.curvedlines/curvedLines.js"></script>
<script src="../vendor/jquery.flot.spline/index.js"></script>
<script src="../vendor/metisMenu/dist/metisMenu.min.js"></script>
<script src="../assets/dropDown/bootstrap-select.min.js"></script>
<script src="../vendor/iCheck/icheck.min.js"></script>
<script src="../vendor/peity/jquery.peity.min.js"></script>
<script src="../vendor/sparkline/index.js"></script>
<script src="../plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script> 
<script src="../vendor/validation/js/jquery.guardian-1.0.min.js"></script>
<script src="../scripts/homer.js"></script>
<script src="../scripts/charts.js"></script>

<script type="text/javascript">

 $('.selectpicker').selectpicker();
                  
                  
                  
                  $(function () {
                      $('.check').on('click', function () {
                        var valu = $('.check').each(function(){});
                        var count = 0;
                        var counter = 0;
                        if(list.length)
                        {
                          for (var a in list){
                          if(valu[0].checked == true)
                            $(questionCheckBox[list[a]]).each(function(){ this.checked = true; 
                              // $(this).parent('div').appendTo('#selectedItems'); 
                            });
                          else if (valu[0].checked != true)
                            $(questionCheckBox[list[a]]).each(function(){ this.checked = false; });
                        };
                          
                      }
                      else
                      {
                        // if($(".searchkey").t)
                        if($( ".searchkey" ).val())
                          for (var a in value){
                              $(questionCheckBox[counter]).each(function(){ this.checked = false; });
                            counter++;
                          }
                        else
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
                
$(".edit").mouseover(function(e) {
    // console.log(e); 
    $(this).attr("src", '../assets/imgs/bedit.png');
}).mouseout(function() {
    $(this).attr("src", '../assets/imgs/wedit.png');
});

$(".delete").mouseover(function() { 
    $(this).attr("src", '../assets/imgs/bdel.png');
}).mouseout(function() {
    $(this).attr("src", '../assets/imgs/wdel.png');
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



//seperate onload function
$(function(){
    //console.log(' inside the onload ');
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



      $(function () {
        $("#example1").dataTable();

       /* var table = $('#example1').DataTable();   userplace
        var test  =   [];
        $('#example1 tbody').on('click', 'td', function () {
            $(this).closest('td').find("input").each(function() {
                test.push(this.value);
            });
        });

        $("form").submit(function(e)
        {
            vehicleList = test;
            var formURL = $(this).attr("action");
            $.ajax(
            {
                url : formURL,
                type: "POST",
                data : vehicleList,
                success:function(data, textStatus, jqXHR) 
                {
                    //data: return data from server
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    //if fails      
                }
            });
            e.preventDefault(); //STOP default action
            //e.unbind(); //unbind. to stop multiple form submit.
        });*/
        
      });








    </script>
	

<script>

    $(function () {

        /**
         * Flot charts data and options
         */
        var data1 = [ [0, 55], [1, 48], [2, 40], [3, 36], [4, 40], [5, 60], [6, 50], [7, 51] ];
        var data2 = [ [0, 56], [1, 49], [2, 41], [3, 38], [4, 46], [5, 67], [6, 57], [7, 59] ];

        var chartUsersOptions = {
            series: {
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 1,
                    fill: 0.4
                },
            },
            grid: {
                tickColor: "#f0f0f0",
                borderWidth: 1,
                borderColor: 'f0f0f0',
                color: '#6a6c6f'
            },
            colors: [ "#62cb31", "#efefef"],
        };

        $.plot($("#flot-line-chart"), [data1, data2], chartUsersOptions);

        /**
         * Flot charts 2 data and options
         */
        var chartIncomeData = [
            {
                label: "line",
                data: [ [1, 10], [2, 26], [3, 16], [4, 36], [5, 32], [6, 51] ]
            }
        ];

        var chartIncomeOptions = {
            series: {
                lines: {
                    show: true,
                    lineWidth: 0,
                    fill: true,
                    fillColor: "#64cc34"

                }
            },
            colors: ["#62cb31"],
            grid: {
                show: false
            },
            legend: {
                show: false
            }
        };

        $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);



    });

</script> 
