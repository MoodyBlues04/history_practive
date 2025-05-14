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
        Имя: {{$exhibitGroup->name}}
        <br>
        TODO: Тут верстку с названием и кратким описанием музея, экспонатов и истории, ссылки на страницы экспонатов/экспозиций
    </div>
@endsection
