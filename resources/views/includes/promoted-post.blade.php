<div
    class="promoted-post-placeholder"
    data-promoted-post-url="{{ route('promoted-post.random') }}"
    hidden
></div>

@once
    <script>
        (() => {
            const initializedFeeds = new WeakSet();
            const observedPromotions = new WeakSet();

            function randomInterval() {
                const intervals = [1, 2, 4];
                return intervals[Math.floor(Math.random() * intervals.length)];
            }

            async function fetchPromotion(url) {
                const response = await fetch(url, {
                    cache: 'no-store',
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (response.status === 204) {
                    return null;
                }

                if (!response.ok) {
                    throw new Error(`Pedido de publicação patrocinada falhou: ${response.status}.`);
                }

                const template = document.createElement('template');
                template.innerHTML = (await response.text()).trim();

                const card = template.content.firstElementChild;
                if (
                    !card
                    || !card.classList.contains('card-updates')
                    || !card.dataset.impressionUrl
                    || template.content.children.length !== 1
                ) {
                    throw new Error('A resposta da publicação patrocinada não contém o card esperado.');
                }

                return card;
            }

            function initializePromotionMedia(card) {
                if (window.lightbox && typeof window.lightbox.reload === 'function') {
                    window.lightbox.reload();
                }

                const videos = card.querySelectorAll('video.js-player');
                if (videos.length && typeof window.Plyr !== 'function') {
                    console.error('Não foi possível inicializar o reprodutor da publicação patrocinada.');
                    return;
                }

                if (typeof window.Plyr === 'function') {
                    videos.forEach(video => new window.Plyr(video));
                }
            }

            function recordImpression(card) {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const sendImpression = () => fetch(card.dataset.impressionUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    keepalive: true,
                    headers: {
                        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (!response.ok && response.status !== 204) {
                        console.error('Não foi possível registar a impressão da publicação patrocinada.', response.status);
                    }
                }).catch(error => {
                    console.error('Erro ao registar a impressão da publicação patrocinada.', error);
                });

                if (!('IntersectionObserver' in window)) {
                    sendImpression();
                    return;
                }

                const observer = new IntersectionObserver((entries, intersectionObserver) => {
                    if (!entries.some(entry => entry.isIntersecting && entry.intersectionRatio >= 0.5)) {
                        return;
                    }

                    intersectionObserver.disconnect();
                    sendImpression();
                }, { threshold: 0.5 });

                observer.observe(card);
            }

            function observePromotion(card) {
                if (observedPromotions.has(card)) {
                    return;
                }

                observedPromotions.add(card);
                recordImpression(card);
            }

            function initializePromotion(card) {
                initializePromotionMedia(card);
                observePromotion(card);
            }

            async function loadStandalonePromotion(slot) {
                try {
                    const card = await fetchPromotion(slot.dataset.promotedPostUrl);
                    if (!card) {
                        slot.remove();
                        return;
                    }

                    slot.replaceWith(card);
                    initializePromotion(card);
                } catch (error) {
                    console.error('Erro ao carregar uma publicação patrocinada.', error);
                    slot.remove();
                }
            }

            function initializeFeed(feed, slot) {
                if (initializedFeeds.has(feed)) {
                    slot.remove();
                    return;
                }

                initializedFeeds.add(feed);
                slot.remove();

                const pendingPosts = [];
                const processedPosts = new WeakSet();
                const endpoint = slot.dataset.promotedPostUrl;
                let postsSincePromotion = 0;
                let nextPromotionAfter = randomInterval();
                let processingPosts = false;
                let noMorePromotions = false;

                async function insertPromotionAfter(post) {
                    const card = await fetchPromotion(endpoint);
                    if (!card) {
                        noMorePromotions = true;
                        return;
                    }

                    post.insertAdjacentElement('afterend', card);
                    initializePromotion(card);
                }

                async function processPosts() {
                    if (processingPosts) {
                        return;
                    }

                    processingPosts = true;
                    while (pendingPosts.length > 0) {
                        const post = pendingPosts.shift();
                        if (!post.isConnected) {
                            continue;
                        }

                        postsSincePromotion++;
                        if (!noMorePromotions && postsSincePromotion >= nextPromotionAfter) {
                            postsSincePromotion = 0;
                            nextPromotionAfter = randomInterval();
                            try {
                                await insertPromotionAfter(post);
                            } catch (error) {
                                console.error('Erro ao carregar uma publicação patrocinada.', error);
                            }
                        }
                    }
                    processingPosts = false;
                }

                function enqueuePosts(node) {
                    if (!(node instanceof Element)) {
                        return;
                    }

                    const posts = [];
                    if (node.matches('.card-updates')) {
                        posts.push(node);
                    }
                    posts.push(...node.querySelectorAll('.card-updates'));

                    posts.forEach(post => {
                        if (post.dataset.impressionUrl) {
                            observePromotion(post);
                            return;
                        }
                        if (!processedPosts.has(post)) {
                            processedPosts.add(post);
                            pendingPosts.push(post);
                        }
                    });

                    processPosts();
                }

                feed.querySelectorAll('.card-updates[data-impression-url]').forEach(observePromotion);
                feed.querySelectorAll('.card-updates').forEach(enqueuePosts);

                new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        mutation.addedNodes.forEach(enqueuePosts);
                    });
                }).observe(feed, { childList: true, subtree: true });
            }

            function initializePromotedPosts() {
                document.querySelectorAll('.promoted-post-placeholder').forEach(slot => {
                    const feed = slot.closest('.grid-updates');
                    if (feed) {
                        initializeFeed(feed, slot);
                    } else {
                        loadStandalonePromotion(slot);
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializePromotedPosts, { once: true });
            } else {
                initializePromotedPosts();
            }
        })();
    </script>
@endonce
