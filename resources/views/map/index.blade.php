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
                <div class="card-body p-0 position-relative" id="museumMap">
                    <img src="{{$museum->map->getPublicUrl()}}"
                         alt="Карта музея"
                         class="img-fluid"
                         style="cursor: pointer">

{{--                    todo: from model --}}
                    <button class="hotspot" style="top: 30%; left: 25%;" data-number="5" data-info="Ancient Vase Collection">
                        <span>5</span>
                    </button>
{{--                    todo: make it change position if go out the margin--}}
                    <div class="info-popup" id="info_popup_5" style="top: 30%; left: 25%;">
                        <div class="popup-header">
                            <h3>Exhibit #<span id="popup_number_5">5</span></h3>
                            <button class="custom-close-btn">&times;</button>
                        </div>
                        <div class="popup-body">
                            <p id="popup_info_5">Sample exhibit information</p>
                        </div>
                    </div>

{{--                    todo property: number of exhibition group --}}
                    <button class="hotspot" style="top: 45%; left: 90%;" data-number="12" data-info="Renaissance Paintings">
                        <span>12</span>
                    </button>
                    <div class="info-popup" id="info_popup_12" style="top: 45%; left: 90%;">
                        <div class="popup-header align-items-center">
                            <h3>Exhibit #<span id="popup_number_12">12</span></h3>
                            <button class="custom-close-btn">&times;</button>
                        </div>
                        <div class="popup-body">
{{--                            todo just search by class inside the block--}}
                            <p id="popup_info_12">Other information</p>
                        </div>
                    </div>
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

            .hotspot {
                position: absolute;
                width: 32px;
                height: 32px;
                border: 2px solid #fff;
                border-radius: 50%;
                background: #2c3e50;
                color: white;
                cursor: pointer;
                transform: translate(-50%, -50%);
                transition: all 0.3s ease;
            }

            .hotspot:hover {
                background: #e74c3c;
                transform: translate(-50%, -50%) scale(1.1);
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
            }

            .info-popup {
                display: none;
                position: absolute;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
                z-index: 99999999999;
                width: 300px;
            }

            .popup-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }

            .custom-close-btn {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const SCALE = 2.3;
            const museumMapEl = document.getElementById("museumMap"),
                elemRect = museumMapEl.getBoundingClientRect();

            let clicked = false;
            let isPopUpOpened = false;
            let isPopUpRecentlyClosed = false;

            function setTransform(xOffset, yOffset) {
                if (clicked) {
                    museumMapEl.style.transform = `translate(${xOffset}px, ${yOffset}px) scale(${SCALE})`;
                } else {
                    museumMapEl.style.transform = "scale(1) translate(0px, 0px)";
                }
            }

            museumMapEl.onclick = function (e) {
                e.preventDefault();
                if (isPopUpOpened) {
                    return;
                }
                if (isPopUpRecentlyClosed) {
                    isPopUpRecentlyClosed = false;
                    return;
                }

                const clickedX = e.clientX - elemRect.x + window.scrollX, clickedY = e.clientY - elemRect.y + window.scrollY;
                const xs = clickedX, ys = clickedY;

                clicked = !clicked;
                setTransform(clickedX - xs * SCALE, clickedY - ys * SCALE);
            }

            document.querySelectorAll('.hotspot').forEach(button => {
                button.addEventListener('click', (e) => {
                    if (isPopUpOpened) {
                        return;
                    }
                    isPopUpOpened = true;

                    const exhibitIndex = e.currentTarget.dataset.number;
                    const exhibitInfo = e.currentTarget.dataset.info;
                    const popup = document.getElementById(`info_popup_${exhibitIndex}`);

                    document.getElementById(`popup_number_${exhibitIndex}`).textContent = exhibitIndex;
                    document.getElementById(`popup_info_${exhibitIndex}`).textContent = exhibitInfo;
                    popup.style.display = 'block';

                    fixMargin(popup);
                });
            });

            document.querySelectorAll('.custom-close-btn').forEach(popUpEl => {
                popUpEl.addEventListener('click', () => closePopUps())
            });

            function closePopUps() {
                isPopUpOpened = false;
                isPopUpRecentlyClosed = true;
                for (const infoGroup of document.getElementsByClassName('info-popup')) {
                    infoGroup.style.display = 'none';
                }
            }

            function fixMargin(popup) {
                const container = popup.parentElement;

                const popupRect = popup.getBoundingClientRect();
                const parentRect = container.getBoundingClientRect();

                const parentStyles = window.getComputedStyle(container);

                const parentMarginRight = parentRect.right + parseFloat(parentStyles.marginRight);

                if (popupRect.right <= parentMarginRight) {
                    return;
                }

                const widthPercentage = (popupRect.width / parentRect.width) * 100;
                popup.style.left = `${parseInt(popup.style.left) - widthPercentage}%`
            }

        </script>
    @endpush
@endsection


