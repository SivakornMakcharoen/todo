@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <a href="{{ route('pokemon') }}" class="btn btn-secondary">ย้อนกลับไปหน้า Pokemon</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div id="pokemon-status" class="alert alert-info">กำลังโหลดรายละเอียดโปเกมอน...</div>
        </div>
    </div>

    <div class="row" id="pokemon-detail"></div>

    <script>
        async function loadPokemonDetail() {
            const statusEl = document.getElementById('pokemon-status');
            const detailEl = document.getElementById('pokemon-detail');
            const pokemonId = '{{ $id }}';

            try {
                const response = await fetch(`https://pokeapi.co/api/v2/pokemon/${pokemonId}`);
                if (!response.ok) {
                    throw new Error(`HTTP error ${response.status}`);
                }

                const pokemon = await response.json();
                const imageUrl = pokemon.sprites?.other?.['official-artwork']?.front_default
                    || pokemon.sprites?.front_default
                    || `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemonId}.png`;

                const types = pokemon.types.map(type => type.type.name).join(', ');
                const abilities = pokemon.abilities.map(ability => ability.ability.name).join(', ');
                const stats = pokemon.stats.map(stat => `
                    <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                        <span class="text-capitalize">${stat.stat.name}</span>
                        <span class="badge bg-primary rounded-pill">${stat.base_stat}</span>
                    </li>
                `).join('');

                statusEl.textContent = `แสดงรายละเอียดของ ${pokemon.name}`;
                statusEl.className = 'alert alert-success';

                detailEl.innerHTML = `
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm h-100 text-center p-3">
                            <img src="${imageUrl}" alt="${pokemon.name}" class="img-fluid mx-auto" style="max-height: 320px;" onerror="this.src='https://via.placeholder.com/320?text=No+Image'" />
                            <div class="card-body">
                                <h2 class="card-title text-capitalize">${pokemon.name}</h2>
                                <p class="mb-1"><strong>Type:</strong> ${types}</p>
                                <p class="mb-1"><strong>Height:</strong> ${pokemon.height} dm</p>
                                <p class="mb-1"><strong>Weight:</strong> ${pokemon.weight} hg</p>
                                <p class="mb-0"><strong>Abilities:</strong> ${abilities}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h4 class="card-title mb-3">Stats</h4>
                                <ul class="list-group list-group-flush">
                                    ${stats}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
            } catch (error) {
                statusEl.textContent = 'เกิดข้อผิดพลาดในการโหลดรายละเอียด: ' + error.message;
                statusEl.className = 'alert alert-danger';
            }
        }

        window.addEventListener('DOMContentLoaded', loadPokemonDetail);
    </script>
@endsection
