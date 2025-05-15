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
            <h2 class="text-center mb-5">Экспонаты</h2>
            <?php /** @var \App\Models\ExhibitGroup $exhibitGroup */ ?>
            @foreach($museum->exhibitGroups as $exhibitGroup)
                <h4 class="">#{{$exhibitGroup->number}} {{$exhibitGroup->name}}</h4>
                <p>{{$exhibitGroup->short_description}}</p>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" style="margin-bottom: 40px">
                    {{--                    todo just make components for this tables--}}
                        <?php /** @var \App\Models\Exhibit $exhibit */ ?>
                    @foreach($exhibitGroup->exhibits as $exhibit)
                        <div class="col">
                            <div class="card exhibition-card h-100">
                                <img src="{{$exhibit->getIconUrl()}}" class="card-img-top exhibition-image"
                                     alt="{{$exhibit->name}}">
                                <div class="card-body">
                                    <h5 class="card-title">{{$exhibit->name}}</h5>
                                    <p class="card-text">{{$exhibit->short_description}}</p>
                                    <a href="{{ route('exhibit.show', $exhibit) }}"
                                       class="btn btn-primary">Подробнее</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Through December 2023
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </section>
    </div>
@endsection
