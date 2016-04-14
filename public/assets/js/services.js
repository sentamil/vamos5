app.factory('vamoservice', function($http, $q){
	return {
        timeCalculate: function(duration){
            var milliseconds = parseInt((duration%1000)/100), seconds = parseInt((duration/1000)%60);
			var minutes = parseInt((duration/(1000*60))%60), hours = parseInt((duration/(1000*60*60))%24);
			hours = (hours < 10) ? "0" + hours : hours;
			minutes = (minutes < 10) ? "0" + minutes : minutes;
			seconds = (seconds < 10) ? "0" + seconds : seconds;
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
			 //    return this.getDataCall(reversegeourl);
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
        	
        	if(temp.color =='P' || temp.color =='N' || temp.color =='A'){
				if(temp.color =='A'){
					pinImage = 'assets/imgs/orangeB.png';
				}else{
					pinImage = 'assets/imgs/'+temp.color+'.png';
				}
			} else if(temp.position == 'N') {
				pinImage =	'assets/imgs/trans.png'
			} else{
				pinImage = 'assets/imgs/'+temp.color+'_'+temp.direction+'.png';
			}
			return pinImage;
        }  
    }  
});