<?php
/**
 * @var \App\Models\Exhibit $exhibit
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
                <h1 class="display-4 museum-title">{{ $exhibit->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('main') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('exhibit.index') }}">Экспонаты</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('exhibit_group.show', $exhibit->exhibitGroup) }}">
                                {{$exhibit->exhibitGroup->name}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $exhibit->name }}</li>
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
                        @foreach($exhibit->photos as $idx => $photo)
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                <img src="{{ $photo->getPublicUrl() }}"
                                     class="d-block w-100 exhibit-gallery-image"
                                     alt="{{ $exhibit->name }} - Image {{ $idx + 1 }}">
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
                    <h2 class="mb-4">Об экспонате</h2>
                    @foreach(explode("\n", $exhibit->description) as $paragraph)
                        <p class="lead">{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
