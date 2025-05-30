<?php
/**
 * @var \App\Models\ExhibitGroup $exhibitGroup
 */
?>

@extends('template')
@section('content')
    <div class="container">
        <section style="margin-bottom: 20px">
            <h2 class="text-center mb-4">...</h2>
        </section>

        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 museum-title">#{{$exhibitGroup->number}} {{ $exhibitGroup->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('exhibit_group.index') }}">Инсталляции</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $exhibitGroup->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Image Gallery -->
        <div class="row mb-5">
            <div class="col-12">
                <div id="exhibitCarousel" class="carousel slide shadow-lg" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php /** @var \App\Models\Photo $photo */ ?>
                        @foreach($exhibitGroup->photos as $idx => $photo)
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                <img src="{{ $photo->getPublicUrl() }}"
                                     class="d-block w-100 exhibit-gallery-image"
                                     alt="{{ $exhibitGroup->name }} - Image {{ $idx + 1 }}"
                                     style="max-height: 800px"
                                >
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#exhibitCarousel"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#exhibitCarousel"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="exhibit-description">
                    @if(!empty($exhibitGroup->description))
                        <h2 class="mb-4">Об инсталляции</h2>
                        @foreach(explode("\n", $exhibitGroup->description) as $paragraph)
                            <p class="lead">{{ $paragraph }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <section id="exhibitions" class="mb-5">
            @if(!$exhibitGroup->exhibits->isEmpty()):
                <h2 class="text-center mb-5">Экспонаты</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <!-- Card 1 -->
                    <?php /** @var \App\Models\Exhibit $exhibit */ ?>
                    @foreach($exhibitGroup->exhibits as $exhibit)
                        <div class="col">
                            <div class="card exhibition-card h-100">
                                <img src="{{$exhibit->getIconUrl()}}" class="card-img-top exhibition-image"
                                     alt="{{$exhibit->name}}">
                                <div class="card-body">
                                    <h5 class="card-title">{{$exhibit->name}}</h5>
                                    <p class="card-text">{{$exhibit->short_description}}</p>
                                    <a href="{{ route('exhibit.show', $exhibit) }}" class="btn btn-primary">Подробнее</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Through December 2023
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Navigation -->
        <div class="row exhibit-navigation">
            <div class="col-6 text-start">
                @if($exhibitGroup->previousGroup !== null)
                    <a href="{{ route('exhibit_group.show', $exhibitGroup->previousGroup) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-chevron-left"></i> Предыдущая инсталляция
                    </a>
                @endif
            </div>
            <div class="col-6 text-end">
                @if($exhibitGroup->nextGroup !== null)
                    <a href="{{ route('exhibit_group.show', $exhibitGroup->nextGroup) }}" class="btn btn-outline-secondary">
                        Следующая инсталляция <i class="bi bi-chevron-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
