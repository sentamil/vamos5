app.factory('vamoservice', function($http, $q){
  
    return {
        
        timeCalculate: function(duration){
            
            var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
            var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
            
            hours    = (hours < 10) ? "0" + hours : hours;
            minutes  = (minutes < 10) ? "0" + minutes : minutes;
            seconds  = (seconds < 10) ? "0" + seconds : seconds;
            temptime = hours + " H : " + minutes +' M';
      
        return temptime;
        },
        
        dayhourmin:function(t){
            var cd = 24 * 60 * 60 * 1000,
                ch = 60 * 60 * 1000,
                 d = Math.floor(t / cd),
                 h = Math.floor( (t - d * cd) / ch),
                 m = Math.round( (t - d * cd - h * ch) / 60000),
               pad = function(n){ return n < 10 ? '0' + n : n; };
     
            if( m === 60 ){
              h++;
              m = 0;
            }
            
            if( h === 24 ){
              d++;
              h = 0;
            }

          return [d+'D', pad(h)+'H', pad(m)+'M'].join(':');
        },

        geocodeToserver: function (lat, lng, address) {
         
         try { 
        // var reversegeourl = 'http://'+globalIP+context+'/public/store?geoLocation='+lat+','+lng+'&geoAddress='+address;
        // return this.getDataCall(reversegeourl);
         }
         catch(err){ console.log(err); }
      
        },

        getDataCall: function(url){
          var defdata = $q.defer();
          $http.get(url).success(function(data){
               defdata.resolve(data);
            }).error(function() {
                    defdata.reject("Failed to get data");
            });

          return defdata.promise;
        },

        
        statusTime:function(arrVal){

          var posTime={};
          var temptime = 0;
          var tempcaption = 'Position';

          if(arrVal.parkedTime!=0){
            temptime = this.dayhourmin(arrVal.parkedTime);
            tempcaption = 'Parked';

          }else if(arrVal.movingTime!=0){
            temptime = this.dayhourmin(arrVal.movingTime);
            tempcaption = 'Moving';

          }else if(arrVal.idleTime!=0){
            temptime = this.dayhourmin(arrVal.idleTime);
            tempcaption = 'Idle';

          }else if(arrVal.noDataTime!=0){
            temptime = this.dayhourmin(arrVal.noDataTime);
            tempcaption = 'No data';

          }
        
            posTime['temptime'] = temptime;
            posTime['tempcaption'] = tempcaption;
        
          return posTime;
        },
        
        iconURL:function(temp){

          var pinImage;
          
          if(temp.color =='P' || temp.color =='N' || temp.color =='A'){
            
            if(temp.color =='A'){
               pinImage = 'assets/imgs/orangeB.png';

            }else{
               pinImage = 'assets/imgs/'+temp.color+'.png';
            }

          } else if(temp.position == 'N') {
               pinImage =  'assets/imgs/trans.png';

          } else if(temp.position=="M" && temp.ignitionStatus=="OFF"){
               pinImage = 'assets/imgs/P.png'; 

          } else{
               pinImage = 'assets/imgs/'+temp.color+'_'+temp.direction+'.png';
          }
      
          return pinImage;
        }, 

        markerImage:function(temp){

            var pinImage;

            if(temp.color =='P' || temp.color =='N' || temp.color =='A'){

                if(temp.color =='A'){
                   // pinImage = 'assets/imgs/orangeB.png';
                      pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_Idle.png';

                }else{
                   // pinImage = 'assets/imgs/'+temp.color+'.png';
                      pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_'+temp.color+'.png';
                }

              } else if(temp.position == 'N') {
                     pinImage =  'assets/imgs/trans.png';
                 //  pinImage =  'assets/imgs/vehicle-marker/'+temp.vehicleType+'_N.png';
              } else if(temp.position=="M" && temp.ignitionStatus=="OFF"){
                // pinImage = 'assets/imgs/P.png';
                   pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_P.png';
               
              } else{
               //  pinImage = 'assets/imgs/'+temp.color+'_'+temp.direction+'.png';
                   pinImage = 'assets/imgs/vehicle-marker/'+temp.vehicleType+'_'+temp.color+'.png';
              }

          return pinImage;
        },

        googleAddress:function(data) {

            var tempVar = data;
            var strNo  = 'sta:null';
            var rotNam = 'rot:null';
            var locs   = 'loc:null';
            var add1   = 'ad1:null';
            var add2   = 'ad2:null';
            var coun   = 'con:null';
            var postal = 'pin:null';

            for(var i=0;i<tempVar.length;i++){
             //console.log(newVarr[i].types);
              for(var j=0;j<tempVar[i].types.length;j++){
               //console.log(newVarr[i].types[j]);
                //console.log(newVarr[i].long_name);
              var valType = tempVar[i].types[j];
              var valName = tempVar[i].long_name;
        
              switch(valType){
        
                case "street_number":
                 //console.log("stn : "+valName);
                  strNo ='sta:'+valName;
                break;
                case "route":
                 //console.log("rot : "+valName);
                  rotNam='rot:'+valName;
                break;
                case "neighborhood":
                  //console.log("neigh : "+valName);
                  //retVar+='nei:'+valName;
                break;
                /*case "sublocality":
                  //console.log("loc : "+valName);
                  retVar+='loc:'+valName+' ';
                break;*/
                case "locality":
                  //console.log("loc : "+valName);
                  locs='loc:'+valName;
                break;
                case "administrative_area_level_1":
                  //console.log("ad1 : "+valName);
                  add1='ad1:'+valName;
                break;
                case "administrative_area_level_2":
                  //console.log("ad2 : "+valName);
                  add2='ad2:'+valName;
                break;
                case "country":
                  //console.log("con : "+valName);
                  coun='con:'+valName;
                break;
                case "postal_code":
                  //console.log("pin : "+valName);
                  postal='pin:'+valName;
                break;
              }
        
            }
          }

         var retVar = strNo+' '+rotNam+' '+locs+' '+add1+' '+add2+' '+coun+' '+postal;
      // console.log(retVar);

      return retVar;
      },


    }  
});