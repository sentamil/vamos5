@include('includes.header_index')

<div id="wrapper">
      <div class="content animate-panel">
            <div class="hpanel">
                  <h6><b><font color="red"> {{ HTML::ul($errors->all()) }}</font></b></h6>
            <div class="panel-heading">
                  <h4><b><font>Licences</font></b></h4>
                  <span  style="position: absolute; float:right; right:5px;z-index: 1;margin-top:0px;">
                      <a class="btn btn-sm btn-primary" href="{{ URL::previous() }}" style="margin-right: 38px;margin-top: -46px;">Go Back</a>
                  </span>
            </div>
            <div class="panel-body">
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('preMonthly', 'Presents Month :')  }}
                                    <p class="form-control">{{$preMonthly}}</p>
                        </div>
                        <div class="col-md-6">
                              <!-- <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'1'.';'.$typeT.';'.$ownT) }}">Monthly :</a> -->
                              {{ Form::label('monthly', 'Monthly :')  }}
                                    <!-- <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'1'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$monthly}}</a> -->
                              @if($monthly != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'1'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$monthly}}</a>
                              @elseif($monthly == 0)
                                    <p class="form-control">{{$monthly}}</p>
                              @endif
                        </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('perQuater', 'present Quaterly :')  }}
                                    <p class="form-control">{{$perQuater}}</p>
                        </div>
                        <div class="col-md-6">
                              <!-- <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'2'.';'.$typeT.';'.$ownT) }}">Quaterly :</a> -->
                              {{ Form::label('quaterly', 'Quaterly :')  }}
                              @if($quaterly != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'2'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$quaterly}}</a>
                              @elseif($quaterly == 0)
                                    <p class="form-control">{{$quaterly}}</p>
                              @endif
                        </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('perHalfyearfly', 'Present Halfyearfly :')  }}
                                    <p class="form-control">{{$perHalfyearfly}}</p>
                        </div>
                        <div class="col-md-6">
                              {{ Form::label('halfyearfly', 'Halfyearfly :')  }}
                              @if($halfyearfly != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'3'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$halfyearfly}}</a>
                              @elseif($halfyearfly == 0)
                                    <p class="form-control">{{$halfyearfly}}</p>
                              @endif
                                    
                        </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('yearly', 'Yearly :')  }}
                              @if($yearfly != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'4'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$yearfly}}</a>
                              @elseif($yearfly == 0)
                                    <p class="form-control">{{$yearfly}}</p>
                              @endif
                                    
                        </div>
                        <div class="col-md-6">
                              {{ Form::label('two_years', '2 Years :')  }}
                              @if($yearfly2 != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'5'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$yearfly2}}</a>
                              @elseif($yearfly2 == 0)
                                    <p class="form-control">{{$yearfly2}}</p>
                              @endif
                              </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('three_years', '3 Years :')  }}
                              @if($yearfly3 != 0)
                                          <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'6'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$yearfly3}}</a>
                              @elseif($yearfly3 == 0)
                                    <p class="form-control">{{$yearfly3}}</p>
                              @endif
                        </div>
                        <div class="col-md-6">
                              {{ Form::label('four_years', '4 Years :')  }}
                              @if($yearfly4 !=0)
                                    <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'7'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$yearfly4}}</a>
                              @elseif($yearfly4 == 0)
                              <p class="form-control">{{$yearfly4}}</p>
                              @endif
                        </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-6">
                              {{ Form::label('five_years', '5 Years :')  }}
                              @if($yearfly5 !=0)
                                    <a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'8'.';'.$typeT.';'.$ownT) }}" class="form-control" style="color: #086fa1; text-decoration: underline;">{{$yearfly5}}</a>
                              @elseif($yearfly5 == 0)
                              <p class="form-control">{{$yearfly5}}</p>
                              @endif
                        </div>
                  </div>
            </div>
            </div>
      </div>
</div>

@include('includes.js_index')

</body>
</html>

