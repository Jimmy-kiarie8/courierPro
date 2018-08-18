@extends('layouts.app')

@section('content')
<v-content>
      <v-container fluid fill-height>
        <v-layout justify-center align-center>
          <div class="container">
              
              <div class="row justify-content-center">
                  <div class="col-md-5">
                      <div class="panel">
                          <div class="panel-header text-center">
                              File Upload page
                              <a href="/" class="btn btn-primary pull-right">Back</a>
                          </div>
                          <div class="panel-body">
                              
                             <!-- <div class="col-md-12"> -->
                                 <file-management :settings="{{ json_encode($props) }}"></file-management>
                             <!-- </div> -->
                             <!-- <div class="col-md-12"> -->
                                 <attachment-list :settings="{{ json_encode($props) }}"></attachment-list>
                             <!-- </div> -->
                          </div>
                          <div class="panel-footer"></div>
                      </div>
                  </div>
              </div>
          </div>
        </v-layout>
    </v-container>
</v-content>
@endsection