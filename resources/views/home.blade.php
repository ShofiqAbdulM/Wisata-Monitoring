@extends('layouts.admin')

@section('main-content')
    <!-- Leaflet  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <!--link rel chart-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
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
        <div class="col-lg-9 mb-2">
            <div id='map' style="height:50em;"></div>s
        </div>
        <div class="col-lg-3 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-xl-12">
                    <form onchange="cari(this.value)">
                        {{-- @foreach ($results as $item) --}}
                        <div class="input-group mb-0">
                            <input type="text" class="form-control bg-light border-0 mr-1" placeholder="Cari Wisata..."
                                name="search" value="{{ request('search') }}">
                            <button class="btn" style="background-color:#000;color:#fff" type="submit"
                                id="button-addon2"><i class="fas fa-search fa-sm"></i></button>
                        </div>
                        {{-- @endforeach --}}
                    </form>
                    {{-- <select onchange="cari(this.value)">
                        @foreach ($results as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select> --}}
                </div>
            </div>
            <div class="row flex-grow pt-2">
                <div class="col-xl-12">
                    <div class=" card card-rounded" style="background-color:#000">
                        <div class="card-body pb-0">
                            <p class="card-title card-title-dash text-white mb-4 text-center" id="demo"
                                style="font-size:40px"></p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="text-white mb-3 text-center" id="demonew"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-grow pt-2">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Jumlah Pengunjung</h6>
                            <p class="m-0 fst-italic text-gray-500">Today</p>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                            </div>

                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i>Buka
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-danger"></i>Ramai
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Hujan
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-info"></i> 25˚Celcius
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        var geoLayer;
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
                    // var iconLabel = L.divIcon({
                    //     className: 'label-bidang',
                    //     html: '<img src="img/icon' + detail[index].icon + '">',
                    //     iconSize: [100, 20]
                    // });
                    // L.marker(layer.getBounds()
                    //     .getCenter(), {
                    //         icon: iconLabel
                    //     }).addTo(map);
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
                                    detail[index].nama +
                                    '</strong></p>';
                                html += '<img src="img/' + detail[index]
                                    .gambar +
                                    '" width="500em" height="350em"></div>';

                                var style = {
                                    'maxWidth': '5000',
                                }
                                L.popup(style)
                                    .setLatLng(layer.getBounds()
                                        .getCenter())
                                    .setContent(html)
                                    .addTo(map);
                            });
                        });
                    })
                    layer.addTo(map);
                }
            });
        })

        function cari(id) {
            geoLayer.eachLayer(function(layer) {
                if (layer.feature.properties.id == id) {
                    map.flyTo(layer.getBounds().getCenter(), 17);
                    // layer.bindPopup(layer.feature.properties.nama);
                }
            });
        };
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
        document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#trafficChart")).setOption({
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    top: '0',
                    left: 'center'
                },
                series: [{
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [{
                        value: 1048,
                        name: 'Jumlah Pengunjung'
                    }]
                }]
            });
        });
    </script>
@endsection
