const POKEMON_PER_PAGE = 20;

function getCurrentPage() {
    const params = new URLSearchParams(window.location.search);
    const page = parseInt(params.get('page'), 10);
    return Number.isInteger(page) && page > 0 ? page : 1;
}

function updatePageQuery(page) {
    const params = new URLSearchParams(window.location.search);
    params.set('page', page);
    const url = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', url);
}

function renderPagination(currentPage, totalPages) {
    const paginationEl = document.getElementById('pokemon-pagination');
    const pages = [];

    const visibleBefore = 2;
    const visibleAfter = 2;
    const startPage = Math.max(1, currentPage - visibleBefore);
    const endPage = Math.min(totalPages, currentPage + visibleAfter);

    pages.push({ label: 'ก่อนหน้า', page: Math.max(1, currentPage - 1), disabled: currentPage === 1 });

    if (startPage > 1) {
        pages.push({ label: '1', page: 1, active: currentPage === 1 });
        if (startPage > 2) {
            pages.push({ label: '...', page: null, disabled: true });
        }
    }

    for (let page = startPage; page <= endPage; page += 1) {
        pages.push({ label: String(page), page, active: page === currentPage });
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            pages.push({ label: '...', page: null, disabled: true });
        }
        pages.push({ label: String(totalPages), page: totalPages, active: currentPage === totalPages });
    }

    pages.push({ label: 'ถัดไป', page: Math.min(totalPages, currentPage + 1), disabled: currentPage === totalPages });

    paginationEl.innerHTML = pages.map(item => {
        const classes = ['page-item'];
        if (item.disabled) classes.push('disabled');
        if (item.active) classes.push('active');

        const ariaCurrent = item.active ? ' aria-current="page"' : '';
        const button = item.page ? `<button class="page-link" data-page="${item.page}"${ariaCurrent}>${item.label}</button>` : `<span class="page-link">${item.label}</span>`;

        return `<li class="${classes.join(' ')}">${button}</li>`;
    }).join('');

    paginationEl.querySelectorAll('button[data-page]').forEach(button => {
        button.addEventListener('click', event => {
            const page = Number(event.currentTarget.dataset.page);
            if (page && page !== currentPage) {
                loadPokemon(page);
            }
        });
    });
}

async function loadPokemon(page = getCurrentPage()) {
    const statusEl = document.getElementById('pokemon-status');
    const listEl = document.getElementById('pokemon-list');
    const offset = (page - 1) * POKEMON_PER_PAGE;

    statusEl.textContent = 'กำลังโหลดข้อมูล...';
    statusEl.className = 'alert alert-info';
    listEl.innerHTML = '';

    try {
        const response = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=${POKEMON_PER_PAGE}&offset=${offset}`);
        if (!response.ok) {
            throw new Error(`HTTP error ${response.status}`);
        }

        const data = await response.json();
        const pokemon = data.results || [];
        const totalCount = data.count || 0;
        const totalPages = Math.max(1, Math.ceil(totalCount / POKEMON_PER_PAGE));

        if (pokemon.length === 0) {
            statusEl.textContent = 'ไม่พบข้อมูลโปเกมอนสำหรับหน้านี้';
            statusEl.className = 'alert alert-warning';
            return;
        }

        statusEl.textContent = `หน้า ${page} จาก ${totalPages} - พบทั้งหมด ${totalCount} ตัว`;
        statusEl.className = 'alert alert-success';

        renderPagination(page, totalPages);
        updatePageQuery(page);

        listEl.innerHTML = pokemon.map(item => {
            const id = item.url.split('/').filter(Boolean).pop();
            const imageUrl = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png`;

            return `
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card shadow-sm h-100 position-relative">
                            <a href="/pokemon/${id}" class="stretched-link"></a>
                            <div class="card-body d-flex gap-3 align-items-center">
                                <img src="${imageUrl}" alt="${item.name}" class="img-fluid" width="72" height="72" onerror="this.src='https://via.placeholder.com/72?text=?'" />
                                <div>
                                    <h5 class="card-title text-capitalize mb-0">${item.name}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        }).join('');
    } catch (error) {
        statusEl.textContent = 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message;
        statusEl.className = 'alert alert-danger';
    }
}

window.addEventListener('DOMContentLoaded', () => loadPokemon());
