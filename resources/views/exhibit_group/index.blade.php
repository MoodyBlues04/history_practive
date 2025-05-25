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
@endsection
