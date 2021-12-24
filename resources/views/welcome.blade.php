<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{env('APP_NAME')}}</title>
      <link href="{{asset('assets/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    {{-- <link href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"> --}}
</head>
<body>
    <div id="app">
        <router-view> </router-view>
    </div>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.easing.min.js')}}"></script>
    <script src="{{asset('assets/js/sb-admin-2.min.js')}}"></script>
    
    
    {{-- <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/dataTables.bootstrap4.min.js')}}"></script> --}}
    <script src="{{asset('js/app.js')}}"></script>

    <!-- Page level custom scripts -->
    {{-- <script src="js/demo/datatables-demo.js"></script> --}}
</body>
</html>