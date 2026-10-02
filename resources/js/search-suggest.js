
(() => {
    const input = document.getElementById('searchInput');
    const box   = document.getElementById('suggestionsBox');
  
    if (!input) return;   // segurança

    // Debounce: evita chamadas excessivas
    let timer = null;
    const debounce = (fn, delay) => (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };

    // Renderiza a lista de sugestões
    const render = (items) => {
        if (!items.length) {
            box.style.display = 'none';
            return;
        }

        box.innerHTML = items
            .map(item => `<div class="suggestion-item px-2 py-1 cursor-pointer hover:bg-gray-100">${item}</div>`)
            .join('');
        box.style.display = 'block';
    };

    // Busca ao digitar
    const fetchSuggestions = debounce(async () => {
        const query = input.value.trim();
        if (query.length < 2) {
            render([]);
            return;
        }

        try {
            const resp = await fetch(`/search/suggestions?q=${encodeURIComponent(query)}`);
            const data = await resp.json();
            render(data.suggestions || []);
        } catch (e) {
            console.error('Erro ao buscar sugestões', e);
            render([]);
        }
    }, 300);

    // Eventos
    input.addEventListener('input', fetchSuggestions);
    input.addEventListener('focus', fetchSuggestions);

    // Clique nas sugestões → preenche o input
    box.addEventListener('click', e => {
        if (e.target && e.target.classList.contains('suggestion-item')) {
            input.value = e.target.textContent;
            render([]);
            // opcional: submeter o formulário automaticamente
            // input.form.submit();
        }
    });

    // Navegação por teclado (↑ ↓ Enter)
    let selectedIndex = -1;
    input.addEventListener('keydown', e => {
        const items = box.querySelectorAll('.suggestion-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
        } else if (e.key === 'Enter') {
            if (selectedIndex >= 0) {
                e.preventDefault();
                input.value = items[selectedIndex].textContent;
                render([]);
                // input.form.submit();   // opcional
            }
            return;
        } else {
            selectedIndex = -1;
            return;
        }

        // Highlight
        items.forEach((el, i) => el.classList.toggle('bg-gray-200', i === selectedIndex));
    });

    // Fecha a caixa se clicar fora
    document.addEventListener('click', e => {
        if (!box.contains(e.target) && e.target !== input) {
            box.style.display = 'none';
        }
    });
})();
