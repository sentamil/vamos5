#!/bin/sh

redis-cli -a ahanram@vamosystems.in sadd Tracking_IndivdiualReportsList HISTORY "SINGLE_TRACK" "MULTITRACK" ROUTES "ADDSITES_GEOFENCE"

redis-cli -a ahanram@vamosystems.in SADD Consolidatedvehicles_IndivdiualReportsList "CURRENT_STATUS" "VEHICLES_CONSOLIDATED" "CONSOLIDATED_SITE_LOCATION" "CONSOLIDATED_STOPPAGE" "TOLLGATE_REPORT" "SCHOOL_SMS_REPORT" "SITESTOPPAGE_ALERT" "SITE_LOCATION" "CONSOLIDATED_OVERSPEED"

redis-cli -a ahanram@vamosystems.in SADD Analytics_IndivdiualReportsList MOVEMENT OVERSPEED PARKED IDLE EVENT SITE "MULTIPLE_SITE" "SITE_TRIP" "TRIP_TIME" "TRIP_SUMMARY" "ALARM" AC STOPPAGE IGNITION FUEL

redis-cli -a ahanram@vamosystems.in SADD Statistics_IndivdiualReportsList DAILY POI CONSOLIDATED "EXECUTIVE_FUEL" "MONTHLY_DIST" "MONTHLY_DIST_AND_FUEL"

redis-cli -a ahanram@vamosystems.in SADD Sensor_IndivdiualReportsList LOAD TEMPERATURE "TEMPERATURE_DEVIATION" "DISTANCE_TIME" "FUEL_FILL" RFID CAMERA


redis-cli -a ahanram@vamosystems.in SADD Performance_IndivdiualReportsList "DAILY_PERFORMANCE" "MONTHLY_PERFORMANCE"


redis-cli -a ahanram@vamosystems.in SADD Useradmin_IndivdiualReportsList "PAYMENT_REPORTS" "EDIT_GROUP" "EDIT_NOTIFICATION" "RESET_PASSWORD" "EDIT_BUSSTOP" "GET_APIKEY"

redis-cli -a ahanram@vamosystems.in sadd  Scheduled_IndivdiualReportsList "SCHEDULED_REPORTS"

redis-cli -a ahanram@vamosystems.in SADD S_TotalReports Tracking_IndivdiualReportsList Consolidatedvehicles_IndivdiualReportsList Analytics_IndivdiualReportsList Statistics_IndivdiualReportsList Sensor_IndivdiualReportsList Performance_IndivdiualReportsList Useradmin_IndivdiualReportsList Scheduled_IndivdiualReportsList

redis-cli -a ahanram@vamosystems.in sadd S_UserVirtualReports "EDIT_NOTIFICATION:Useradmin" "ROUTES:Tracking" "EDIT_BUSSTOP:Useradmin" "EDIT_GROUP:Useradmin" "RESET_PASSWORD:Useradmin" "ADDSITES_GEOFENCE:Tracking" "GET_APIKEY:Useradmin"



redis-cli -a ahanram@vamosystems.in sadd S_Default_ReportList "OVERSPEED:Analytics" "PARKED:Analytics" "MOVEMENT:Analytics" "DAILY:Statistics" "RESET_PASSWORD:Useradmin" "DAILY_PERFORMANCE:Performance" "CURRENT_STATUS:Consolidatedvehicles" "MULTITRACK:Tracking" "SINGLE_TRACK:Tracking" "IDLE:Analytics" "PAYMENT_REPORTS:Useradmin" "HISTORY:Tracking"



java -Duser.timezone=Asia/Calcutta -Djetty.host=127.0.0.1  -cp "/prd/vamos/backend2016/lib/*:/prd/vamos/backend2016/"  com.el.service.rest.locater.UtilityLocatorServices

#set report user
