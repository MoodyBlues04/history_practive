<?php
/**
 * @var \App\Models\Museum $museum
 */
?>
@extends('template')
@section('content')
    <div class="container">
        <section style="margin-bottom: 20px">
            <h2 class="text-center mb-4">...</h2>
        </section>

        <div class="container mt-100">
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
                             id="map-img"
                             title="Нажмите, чтобы изменить масштаб"
                             style="cursor: zoom-in">

                        <?php /** @var \App\Models\ExhibitGroup $exhibitGroup */ ?>
                        @foreach($museum->exhibitGroups as $exhibitGroup)
                            <button class="hotspot" style="top: {{$exhibitGroup->getMapTop()}}%; left: {{$exhibitGroup->getMapLeft()}}%;"
                                    data-number="{{$exhibitGroup->number}}">
                                <span>{{$exhibitGroup->number}}</span>
                            </button>
                            <div class="info-popup" id="info_popup_{{$exhibitGroup->number}}"
                                 style="top: {{$exhibitGroup->getMapTop()}}%; left: {{$exhibitGroup->getMapLeft()}}%;">
                                <div class="popup-photo-container" style="margin: -20px">
                                    <img src="{{ $exhibitGroup->getIconUrl() }}"
                                         alt="{{$exhibitGroup->name}}"
                                         class="popup-photo">
                                </div>
                                <div class="popup-header" style="margin-top: 25px">
                                    <h5><span>{{$exhibitGroup->number}}</span>. {{$exhibitGroup->name}}</h5>
                                    <button class="custom-close-btn">&times;</button>
                                </div>
                                <div class="popup-body">
                                    <a href="{{ route('exhibit_group.show', $exhibitGroup) }}">Подробнее</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <section id="exhibitions" class="mb-5">
                <h2 class="text-center mb-5">Инсталляции</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <!-- Card 1 -->
                    <?php /** @var \App\Models\ExhibitGroup $exhibitGroup */ ?>
                    @foreach($museum->exhibitGroups as $exhibitGroup)
                        <div class="col">
                            <div class="card exhibition-card h-100">
                                <img src="{{$exhibitGroup->getIconUrl()}}" class="card-img-top exhibition-image"
                                     alt="{{$exhibitGroup->name}}">
                                <div class="card-body">
                                    <h5 class="card-title">#{{$exhibitGroup->number}} {{$exhibitGroup->name}}</h5>
                                    <p class="card-text">{{$exhibitGroup->short_description}}</p>
                                    <a href="{{ route('exhibit_group.show', $exhibitGroup) }}" class="btn btn-primary">Подробнее</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Through December 2023
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
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

                .popup-photo-container {
                    border-radius: 8px 8px 0 0;
                    -webkit-border-radius: 8px 8px 0 0;
                    height: 160px;
                    overflow: hidden;
                }

                .popup-photo {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
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
                    // e.preventDefault();
                    if (isPopUpOpened) {
                        return;
                    }
                    if (isPopUpRecentlyClosed) {
                        isPopUpRecentlyClosed = false;
                        return;
                    }

                    const mapEl = document.getElementById("map-img");
                    mapEl.style.cursor = clicked ? 'zoom-in' : 'zoom-out';

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
                        const popup = document.getElementById(`info_popup_${exhibitIndex}`);
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
    </div>
@endsection


