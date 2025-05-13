<?php
/**
 * @var \App\Models\Museum $museum
 */
?>
@extends('template')
@section('content')
    <div class="container">
        <div class="map-controls mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a class="btn btn-success" href="{{ route('map.load', $museum) }}">
                        <i class="bi bi-download"></i> Скачать карту
                    </a>
                    {{-- place other buttons here --}}
                </div>
            </div>
        </div>

        <!-- Блок с картой музея -->
        <div class="museum-map mb-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">План музея</h5>
                </div>
                <div class="card-body p-0 position-relative">
                    <img src="{{$museum->map->getPublicUrl()}}"
                         alt="Карта музея"
                         class="img-fluid"
                         style="cursor: pointer"
                         id="museumMap">
                </div>
            </div>
        </div>

        <h4 class="mb-4">Инсталляции</h4>
        <div class="row row-cols-3 g-4 justify-content-center">
            <?php /** @var \App\Models\ExhibitGroup $exhibitGroup */ ?>
            @foreach($museum->exhibitGroups as $exhibitGroup)
                <div class="col">
    {{--                        todo instead of link just highlight pointer to exhibit on scheme --}}
                    <a href="{{ route('exhibit_group.show', $exhibitGroup) }}" style="text-decoration: none">
                        <div class="card h-150">
    {{--                        todo check if exisis or default img --}}
                            <img src="{{ $exhibitGroup->photos->first()->getPublicUrl() }}"
                                 class="card-img-top"
                                 alt="{{ $exhibitGroup->name }}"
                                 style="height: 200px; object-fit: cover">

                            <div class="card-body">
                                <h5 class="card-title">{{ $exhibitGroup->name }}</h5>
                                <p class="card-text text-muted">{{ $exhibitGroup->description }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    @push('styles')
        <style>
            .museum-map {
                position: relative;
                border: 2px solid #dee2e6;
                border-radius: 8px;
                overflow: hidden;
            }

            #museumMap {
                transform-origin: 0px 0px;
                transform: scale(1) translate(0px, 0px);
                width: 100%;
                height: 100%;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const SCALE = 2.3;
            const museumMapEl = document.getElementById("museumMap"),
                elemRect = museumMapEl.getBoundingClientRect();

            let clicked = false;

            function setTransform(xOffset, yOffset) {
                if (clicked) {
                    museumMapEl.style.transform = `translate(${xOffset}px, ${yOffset}px) scale(${SCALE})`;
                } else {
                    museumMapEl.style.transform = "scale(1) translate(0px, 0px)";
                }
            }

            museumMapEl.onclick = function (e) {
                e.preventDefault();

                const clickedX = e.clientX - elemRect.x + window.scrollX, clickedY = e.clientY - elemRect.y + window.scrollY;
                const xs = clickedX, ys = clickedY;

                clicked = !clicked;
                setTransform(clickedX - xs * SCALE, clickedY - ys * SCALE);
            }
        </script>
    @endpush
@endsection


