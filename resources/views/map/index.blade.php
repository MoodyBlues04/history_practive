<?php
/**
 * @var \App\Models\Museum $museum
 * @var \App\Models\ExhibitGroup[] $exhibitGroups
 * @var ?string $searched
 */
?>
@extends('template')
@section('content')
    <div class="container">
        <section style="margin-bottom: 20px">
            <h2 class="text-center mb-4">...</h2>
        </section>

        <div class="container mt-100">

            <div class="museum-map mb-5 bg-light border rounded-4 shadow-sm overflow-hidden">
                <div class="p-3 border-bottom d-flex" style="justify-content: space-between; align-items: center">
                    <h5 class="mb-0 text-dark">Интерактивная карта музея</h5>
                    <a class="btn btn-success" href="{{ route('map.load', $museum) }}">
                        <i class="bi bi-download"></i> Скачать карту
                    </a>
                </div>
                <div class="position-relative" id="museumMap" style="min-height: 500px;">
                    <img src="{{$museum->map->getPublicUrl()}}"
                         alt="Карта музея"
                         class="img-fluid w-100 h-100 object-fit-cover"
                         id="map-img"
                         title="Нажмите, чтобы изменить масштаб"
                         style="cursor: zoom-in">

                    <?php /** @var \App\Models\ExhibitGroup $exhibitGroup */ ?>
                    @foreach($museum->exhibitGroups as $exhibitGroup)
                        <button class="hotspot"
                                style="top: {{$exhibitGroup->getMapTop()}}%; left: {{$exhibitGroup->getMapLeft()}}%;"
                                data-number="{{$exhibitGroup->number}}"
                                title="{{$exhibitGroup->name}}">
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

            <h2 class="text-center mb-5" id="exhibits-header">Инсталляции</h2>

            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="input-group">
                        <input id="search-input"
                               type="text"
                               class="form-control"
                               placeholder="Поиск по названию или номеру"
                               aria-label="Поиск экспонатов">
                        <button id="search-input-btn" class="btn btn-primary" type="button" title="Поиск">
                            <i class="bi bi-search"></i>
                        </button>
                        @if(!empty($searched))
                            <button class="btn btn-secondary clear-search-input-btn" type="button"
                                    title="Сбросить поиск">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <section id="exhibitions" class="mb-5">
                @if (!empty($exhibitGroups))
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" style="margin-top: 30px">
                        @foreach($exhibitGroups as $exhibitGroup)
                            <div class="col">
                                <div class="card exhibition-card h-100 shadow-sm">
                                    <img src="{{$exhibitGroup->getIconUrl()}}"
                                         class="card-img-top exhibition-image"
                                         alt="{{$exhibitGroup->name}}">
                                    <div class="card-body">
                                        <h5 class="card-title">#{{$exhibitGroup->number}} {{$exhibitGroup->name}}</h5>
                                        <p class="card-text">{{$exhibitGroup->short_description}}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('exhibit_group.show', $exhibitGroup) }}"
                                               class="btn btn-primary btn-sm">Подробнее</a>
                                            <a href="#museumMap"
                                               class="text-decoration-none map-pin-icon"
                                               data-exhibit-number="{{$exhibitGroup->number}}"
                                               title="Показать на схеме">
                                                Показать<i class="bi bi-geo-alt-fill text-danger fs-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        Through December 2023
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 px-3">
                        <div class="empty-state animate__animated animate__fadeIn">
                            <div class="icon-wrapper mb-4">
                                <svg class="search-empty-icon" width="80" height="80" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.5 14H14.71L14.43 13.73C15.41 12.59 16 11.11 16 9.5C16 5.91 13.09 3 9.5 3C5.91 3 3 5.91 3 9.5C3 13.09 5.91 16 9.5 16C11.11 16 12.59 15.41 13.73 14.43L14 14.71V15.5L19 20.49L20.49 19L15.5 14ZM9.5 14C7.01 14 5 11.99 5 9.5C5 7.01 7.01 5 9.5 5C11.99 5 14 7.01 14 9.5C14 11.99 11.99 14 9.5 14Z"
                                        fill="#adb5bd"/>
                                    <path d="M10 9H9V8H10V9ZM10 10H9V11H10V10Z" fill="#adb5bd"/>
                                </svg>
                            </div>

                            <h3 class="mb-3 text-muted">Инсталляций не найдено</h3>

                            <button class="btn btn-outline-secondary btn-sm clear-search-input-btn">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>
                                Сбросить поиск
                            </button>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        @push('styles')
            <style>
                .museum-map {
                    position: relative;
                    transition: all 0.3s ease;
                }

                .museum-map:hover {
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                }

                #museumMap {
                    transform-origin: 0px 0px;
                    transform: scale(1) translate(0px, 0px);
                    min-height: 500px;
                    background: #f8f9fa;
                }

                /* Updated Hotspot Styling */
                .hotspot {
                    position: absolute;
                    width: 34px;
                    height: 34px;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    background: #2c3e50;
                    color: white;
                    transform: translate(-50%, -50%);
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                    font-weight: 500;
                }

                .hotspot:hover {
                    background: #e74c3c;
                    transform: translate(-50%, -50%) scale(1.15);
                    z-index: 1000;
                }

                /* Map Pin Icon Animation */
                .map-pin-icon {
                    transition: all 0.3s ease;
                    opacity: 0.8;
                }

                .map-pin-icon:hover {
                    opacity: 1;
                    transform: scale(1.15);
                }

                .info-popup {
                    display: none;
                    position: absolute;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
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

                .museum-map + .row { /* Targets the row immediately after museum-map */
                    margin-top: 2rem;
                }

                .input-group {
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                    border-radius: 0.5rem;
                }

                .input-group input {
                    border-right: 0;
                    height: 50px;
                }

                .input-group button {
                    border-left: 0;
                    padding: 0 1.5rem;
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

                    const clickedX = e.clientX - elemRect.x + window.scrollX,
                        clickedY = e.clientY - elemRect.y + window.scrollY;
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
                    const parentMarginBottom = parentRect.bottom + parseFloat(parentStyles.marginBottom);

                    if (popupRect.right >= parentMarginRight) {
                        const widthPercentage = (popupRect.width / parentRect.width) * 100;
                        popup.style.left = `${parseInt(popup.style.left) - widthPercentage}%`
                    }
                    if (popupRect.bottom >= parentMarginBottom) {
                        const heightPercentage = (popupRect.height / parentRect.height) * 100;
                        popup.style.top = `${parseInt(popup.style.top) - heightPercentage}%`
                    }
                }

                document.querySelectorAll('.map-pin-icon').forEach(icon => {
                    icon.addEventListener('click', function (e) {
                        e.preventDefault();
                        const exhibitNumber = this.dataset.exhibitNumber;

                        document.getElementById('museumMap').scrollIntoView({
                            behavior: 'smooth'
                        });

                        setTimeout(() => {
                            const hotspot = document.querySelector(`.hotspot[data-number="${exhibitNumber}"]`);
                            if (hotspot) {
                                hotspot.click();
                            }
                        }, 300);
                    });
                });

                if (<?= (int)!empty($searched) ?>) {
                    document.getElementById('exhibits-header').scrollIntoView({
                        behavior: 'smooth'
                    });
                    document.getElementById('search-input').value = "<?= $searched ?>";
                }

                const PAGE_URL = "<?= route('map.index') ?>";
                const doSearch = () => window.location.href = PAGE_URL + "?search=" + document.getElementById('search-input').value;

                document.getElementById('search-input-btn').addEventListener('click', (e) => {
                    doSearch();
                })

                document.getElementById('search-input').addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        doSearch();
                    }
                })

                document.querySelectorAll('.clear-search-input-btn').forEach((el) => {
                    el.addEventListener('click', (e) => {
                        window.location.href = PAGE_URL;
                    })
                })
            </script>
        @endpush
    </div>
@endsection


