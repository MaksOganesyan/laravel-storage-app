@extends('layouts.app')

@section('title', 'Добавить вещь')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Добавить новую вещь</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('things.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Название <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Гарантия / срок годности</label>
                <input type="date" name="wrnt" class="form-control" value="{{ old('wrnt') }}">
            </div>

            <button type="submit" class="btn btn-success">Сохранить вещь</button>
            <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
