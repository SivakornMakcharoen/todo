@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <a href="" ><img src="https://www.pngarts.com/files/4/Pokemon-Logo-PNG-Background-Image.png" width="260" height="240"></a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div id="pokemon-status" class="alert alert-info">กำลังโหลดข้อมูล...</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <form id="pokemon-search-form" class="mb-3" action="" method="get">
                <div class="input-group">
                    <input id="pokemon-search-input" name="search" type="search" class="form-control" placeholder="ค้นหาโปเกมอน (ชื่อหรือรหัส)" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">ค้นหา</button>
                    <button id="pokemon-search-clear" type="button" class="btn btn-secondary">ล้าง</button>
                </div>
            </form>

            <nav aria-label="Pokemon pagination">
                <ul id="pokemon-pagination" class="pagination flex-wrap"></ul>
            </nav>
        </div>
    </div>

    <div class="row" id="pokemon-list"></div>

    <script src="{{ asset('js/pokemon.js') }}"></script>
@endsection
