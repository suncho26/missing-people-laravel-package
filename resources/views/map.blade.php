﻿@extends('missing-persons::layouts.default')
@section('page_css')
    <link href="{{ asset('vendor/missing/js/leafletjs/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/missing/js/leafletjs/leaflet.markercluster/markercluster.default.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/missing/js/leafletjs/leaflet.markercluster/markercluster.css') }}" rel="stylesheet">
@stop
@section('breadcrumbs')
    {{ Breadcrumbs::render('map') }}
@stop
@section('content')
    <div class="container my-12 mx-auto px-4 md:px-12">
        <div class="flex flex-wrap overflow-hidden xl:-mx-2">
            <div id="map-box" class="h-64 w-full"></div>
        </div>
    </div>
@endsection
@section('page_js')
    <script>
        window.locations = @json($locations);
    </script>
    <script type="text/javascript" src="{{ asset('vendor/missing/js/leafletjs/leaflet.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/missing/js/leafletjs/leaflet.markercluster/leaflet.markercluster.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/missing/js/pages/map.page.js') }}"></script>
@stop