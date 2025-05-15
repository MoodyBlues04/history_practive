<?php
/**
 * @var \App\Models\Museum $museum
 */
?>

@extends('template')
@section('content')
    <div class="hero-section d-flex align-items-center justify-content-center">
        <div class="text-center text-white">
            <h1 class="museum-title mb-4">Музей оптики ИТМО</h1>
            <p class="lead fs-4">Discover Centuries of Human Achievement</p>
        </div>
    </div>

    <div class="container">
        <section class="mb-5">
            <h2 class="text-center mb-4">Добро пожаловать</h2>
            <p class="lead text-muted text-center mb-5">
                {{$museum->description}}
            </p>
        </section>

        <!-- Exhibition Cards -->
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
                                <h5 class="card-title">{{$exhibitGroup->name}}</h5>
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
@endsection

@push('styles')
    <style>
        .hero-section {
            height: 70vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
            url('https://d3mvlb3hz2g78.cloudfront.net/wp-content/uploads/2014/10/thumb_720_450_dreamstime_xl_12297924-Custom.jpg') center/cover;
            position: relative;
            margin-bottom: 3rem;
        }

        .museum-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
    </style>
@endpush
