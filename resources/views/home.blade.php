@extends('layouts.admin')

@section('map-content')
    <!-- Leaflet  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-10 mb-2">
            <div id='map' style="height:50em;"></div>
            <script>
                var peta1 = L.tileLayer(
                    'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        attribution: '<a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            '<a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox/streets-v11'
                    });

                var peta2 = L.tileLayer(
                    'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        attribution: '<a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox/satellite-v9'
                    });


                var peta3 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                });

                var peta4 = L.tileLayer(
                    'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox/dark-v10'
                    });

                var map = L.map('map', {
                    center: [-7.884550294687469, 112.52448965839899],
                    zoom: 14,
                    layers: [peta1]
                });

                var baseMaps = {
                    "peta1": peta1,
                    "peta2": peta2,
                    "peta3": peta3,
                    "peta4": peta4
                };

                L.control.layers(baseMaps).addTo(map);

                $.getJSON('geojson/map.geojson', function(json) {
                    geoLayer = L.geoJson(json, {
                        style: function(feature) {
                            // switch (feature.properties.party) {
                            //     case 'Republican':
                            return {
                                fillOpacity: 0.5,
                                opacity: 1,
                                weight: 2,
                                color: "#ff0000"
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            // alert(feature.properties.id)
                            layer.on('click', (e) => {
                                // alert(feature.properties.id);
                                $.getJSON('wisata/' + feature.properties.id, function(detail) {
                                    $.each(detail, function(index) {
                                        // alert(detail[index].gambar);
                                        // L.marker(layer.getBounds().getCenter()).addTo(
                                        //     map);
                                        var html =
                                            '<div align="center"><p style="color:#FF0000;  font-family:Helvetica Neue; font-size:25px;" class="text-uppercase"><strong>' +
                                            detail[index].nama + '</strong></p>';
                                        html += '<img src="img/' + detail[index]
                                            .gambar +
                                            ' " width="700em" height="500em"></div>';

                                        L.popup()
                                            .setLatLng(layer.getBounds().getCenter())
                                            .setContent(html)
                                            .addTo(map);
                                        // L.popup()
                                        //     .setLatLng(layer.getBounds().getCenter())
                                        //     .setContent(html)
                                        //     .openOn(map);

                                    });
                                });
                            })
                            layer.addTo(map);
                        }
                    });
                })
            </script>
        </div>
        <div class="col-lg-2 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                    <div class="card card-rounded" style="background-color:#000">
                        <div class="card-body pb-0">
                            <h3 class="card-title card-title-dash text-white mb-4 text-center" id="demo"></h3>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-white mb-3 text-center" id="demonew"></p>
                                </div>
                                {{-- x --}}
                                <script>
                                    var today = new Date();
                                    var dd = today.getDate();
                                    var mm = today.getMonth() + 1; //January is 0!
                                    var yyyy = today.getFullYear();
                                    if (dd < 10) {
                                        dd = '0' + dd
                                    }

                                    if (mm < 10) {
                                        mm = '0' + mm
                                    }

                                    today = mm + '/' + dd + '/' + yyyy;
                                    document.getElementById("demonew").innerHTML = today;
                                    var myVar = setInterval(function() {
                                        myTimer()
                                    }, 1000);

                                    function myTimer() {
                                        var d = new Date();
                                        document.getElementById("demo").innerHTML = d.toLocaleTimeString();
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-grow">
                <div class="col-md-6 col-lg-12 grid-margin stretch-card">
                    <div class="card card-rounded" style="background-color:#000">
                        <div class="card-body pb-0">
                            <h4 class="card-title card-title-dash text-white mb-4">Total Pengunjung</h4>
                            <p class="text-white mb-3 text-center" id="demonew">1200 Pengunjung</p>
                            <h4 class="card-title card-title-dash text-white mb-4">Suhu</h4>
                            <p class="text-white mb-3 text-center" id="demonew">25˚C</p>
                            <h4 class="card-title card-title-dash text-white mb-4">Cuaca</h4>
                            <p class="text-white mb-3 text-center" id="demonew">Hujan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
