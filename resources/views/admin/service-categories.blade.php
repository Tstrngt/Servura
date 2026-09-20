@extends('layouts.app')

@section('title', 'Productcategorieën - Servura Admin')

@section('content')
@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-50 lg:pl-64">
    <div class="mx-auto w-full max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Productcategorieën</h1>
            <p class="mt-1 text-sm text-slate-600">Groepeer producten voor beheer, catalogus en checkout.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[380px_minmax(0,1fr)]">
            <form action="{{ route('admin.service-categories.store') }}" method="POST" class="h-fit rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                @csrf
                <h2 class="mb-5 text-lg font-semibold text-slate-900">Nieuwe categorie</h2>
                <div class="form-group"><label class="form-label" for="name">Naam *</label><input class="form-input" id="name" name="name" required value="{{ old('name') }}"></div>
                <div class="form-group"><label class="form-label" for="description">Omschrijving</label><textarea class="form-textarea" id="description" name="description" rows="3">{{ old('description') }}</textarea></div>
                <div class="form-group"><label class="form-label" for="sort_order">Volgorde</label><input class="form-input" id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}"></div>
                <label class="mb-5 flex items-center gap-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-primary-600"> Actief en zichtbaar</label>
                <button class="btn btn-primary w-full" type="submit">Categorie toevoegen</button>
            </form>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-5 py-4"><h2 class="font-semibold text-slate-900">Bestaande categorieën</h2></div>
                <div class="divide-y divide-slate-200">
                    @forelse($categories as $category)
                        <form action="{{ route('admin.service-categories.update', $category) }}" method="POST" class="grid grid-cols-1 gap-3 p-5 md:grid-cols-[minmax(180px,1fr)_minmax(240px,2fr)_100px_100px_auto] md:items-center">
                            @csrf
                            @method('PUT')
                            <input class="form-input" name="name" required value="{{ $category->name }}" aria-label="Categorienaam">
                            <input class="form-input" name="description" value="{{ $category->description }}" placeholder="Omschrijving" aria-label="Omschrijving">
                            <input class="form-input" name="sort_order" type="number" min="0" value="{{ $category->sort_order }}" aria-label="Volgorde">
                            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600"> Actief</label>
                            <div class="flex items-center justify-end gap-2">
                                <span class="whitespace-nowrap text-xs text-slate-500">{{ $category->services_count }} producten</span>
                                <button class="rounded-lg px-3 py-2 text-sm font-semibold text-primary-700 hover:bg-primary-50" type="submit">Opslaan</button>
                            </div>
                        </form>
                        @if($category->services_count === 0)
                            <form action="{{ route('admin.service-categories.destroy', $category) }}" method="POST" class="-mt-4 flex justify-end px-5 pb-4" onsubmit="return confirm('Deze categorie verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Categorie verwijderen</button>
                            </form>
                        @endif
                    @empty
                        <div class="p-10 text-center text-sm text-slate-500">Nog geen categorieën aangemaakt.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
