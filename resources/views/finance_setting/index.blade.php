@extends('layouts.company.company')

@section('content')
<finance-setting :setting="{{json_encode($setting)}}"></finance-setting>

@endsection
