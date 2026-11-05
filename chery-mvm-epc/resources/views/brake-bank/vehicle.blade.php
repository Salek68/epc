@extends('layouts.app')

@section('title', 'لنت ترمز ' . $vehicle->name)

@section('content')

<div class="page-heading">
    <a href="{{ route('brake-bank.index') }}" class="btn btn-secondary">
        ← بازگشت به جست‌وجو
    </a>
</div>

<section class="hero">
    <span class="badge">BRAKE PAD DATABASE</span>

    <h1 style="margin-top:12px;">
        مشخصات لنت ترمز {{ $vehicle->name }}
    </h1>

    <p>
        اطلاعات لنت جلو، لنت عقب و خودروهای مشترک
    </p>
</section>

<div class="stats">
    <div class="stat">
        <div class="stat-label">لنت جلو</div>
        <div class="stat-value">{{ $frontPads->count() }} مورد</div>
    </div>

    <div class="stat">
        <div class="stat-label">لنت عقب</div>
        <div class="stat-value">{{ $rearPads->count() }} مورد</div>
    </div>

    <div class="stat">
        <div class="stat-label">موقعیت نامشخص</div>
        <div class="stat-value">{{ $unknownPads->count() }} مورد</div>
    </div>
</div>

{{-- لنت جلو --}}
<section class="card">
    <h2>
        <span style="color:#2563eb;">●</span>
        لنت ترمز جلو
    </h2>

    @forelse($frontPads as $pad)
        @include('brake-bank.partials.pad-card', ['pad' => $pad])
    @empty
        <div class="empty">
            برای این خودرو لنت جلویی ثبت نشده است.
        </div>
    @endforelse
</section>

{{-- لنت عقب --}}
<section class="card">
    <h2>
        <span style="color:#15803d;">●</span>
        لنت ترمز عقب
    </h2>

    @forelse($rearPads as $pad)
        @include('brake-bank.partials.pad-card', ['pad' => $pad])
    @empty
        <div class="empty">
            برای این خودرو لنت عقبی ثبت نشده است.
        </div>
    @endforelse
</section>

{{-- موقعیت نامشخص --}}
@if($unknownPads->isNotEmpty())
    <section class="card">
        <h2>لنت‌های با موقعیت نامشخص</h2>

        @foreach($unknownPads as $pad)
            @include('brake-bank.partials.pad-card', ['pad' => $pad])
        @endforeach
    </section>
@endif

@endsection