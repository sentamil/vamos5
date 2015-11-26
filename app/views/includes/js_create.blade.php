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
<script src="../vendor/iCheck/icheck.min.js"></script>
<script src="../vendor/peity/jquery.peity.min.js"></script>
<script src="../vendor/sparkline/index.js"></script>
<script src="../plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script> 
<script src="../scripts/homer.js"></script>
<script src="../scripts/charts.js"></script>

<script type="text/javascript">
    $( ".searchkey" ).keyup(function() {
    
       var valThis = $(this).val().toLowerCase();
       $('.vehiclelist>input').each(function(){
       var text = $(this).val().toLowerCase();
       if(text.indexOf(valThis) >= 0) {
        $(this).parent('div').fadeIn();
       }
       else{
        $(this).parent('div').fadeOut();
       }
       });
})


      $(function () {
        $("#example1").dataTable();

       /* var table = $('#example1').DataTable();
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
	<script type="text/javascript">
$( ".searchkey" ).keyup(function() {
  var valThis = $(this).val().toLowerCase();
   $('.userplace>input').each(function(){
       var text = $(this).val().toLowerCase();
       if(text.indexOf(valThis) >= 0) {
       	$(this).parent('div').fadeIn();
       }
       else{
       	$(this).parent('div').fadeOut();
       }

  });
})</script>

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
