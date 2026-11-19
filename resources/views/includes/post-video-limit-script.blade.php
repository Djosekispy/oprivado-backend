<script>
(function () {
  const endpoint = @json(route('post-video.playback'));
  const authorizedMediaIds = new Set();
  const pendingMediaIds = new Set();
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');

  function showLimitModal(result) {
    const message = document.getElementById('postVideoLimitMessage');
    const periodLabel = {
      never: 'total',
      daily: 'diário',
      monthly: 'mensal'
    }[result.reset_period] || 'atual';

    message.textContent = 'Atingiu o limite ' + periodLabel + ' de vídeos (' + result.limit + '). Torne-se PRO para continuar a ver publicações em vídeo.';
    window.jQuery('#postVideoLimitModal').modal('show');
  }

  function showPlaybackError(error) {
    console.error('Não foi possível verificar o limite de reprodução de vídeos.', error);
    if (typeof window.swal === 'function') {
      window.swal('Erro', 'Não foi possível verificar o acesso a este vídeo. Tente novamente.', 'error');
    } else {
      window.alert('Não foi possível verificar o acesso a este vídeo. Tente novamente.');
    }
  }

  async function requestAuthorization(mediaId) {
    const response = await fetch(endpoint, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ media_id: mediaId })
    });

    let result;
    try {
      result = await response.json();
    } catch (error) {
      throw new Error('O servidor devolveu uma resposta inválida.');
    }

    if (!response.ok) {
      throw new Error(result.message || 'Falha ao verificar o acesso ao vídeo (' + response.status + ').');
    }

    if (!result.allowed) {
      showLimitModal(result);
      return false;
    }

    authorizedMediaIds.add(String(mediaId));
    return true;
  }

  function authorizeAndContinue(mediaId, onAllowed) {
    const normalizedId = String(mediaId);
    if (authorizedMediaIds.has(normalizedId)) {
      onAllowed();
      return;
    }

    if (pendingMediaIds.has(normalizedId)) return;
    pendingMediaIds.add(normalizedId);

    requestAuthorization(normalizedId)
      .then(function (allowed) {
        if (allowed) onAllowed();
      })
      .catch(showPlaybackError)
      .finally(function () {
        pendingMediaIds.delete(normalizedId);
      });
  }

  document.addEventListener('play', function (event) {
    const video = event.target;
    if (!(video instanceof HTMLVideoElement) || !video.dataset.postVideoId) return;

    const mediaId = video.dataset.postVideoId;
    if (authorizedMediaIds.has(mediaId)) return;

    video.pause();
    authorizeAndContinue(mediaId, function () {
      if (!video.isConnected) return;
      const playback = video.play();
      if (playback && typeof playback.catch === 'function') {
        playback.catch(showPlaybackError);
      }
    });
  }, true);

  document.addEventListener('click', function (event) {
    const target = event.target instanceof Element ? event.target : event.target.parentElement;
    const embedGate = target && target.closest('[data-post-video-embed-gate]');
    if (embedGate) {
      event.preventDefault();
      event.stopImmediatePropagation();
      authorizeAndContinue(embedGate.dataset.postVideoId, function () {
        const iframe = document.getElementById(embedGate.dataset.postVideoIframeId);
        if (!iframe || !iframe.contentWindow) {
          showPlaybackError(new Error('O player de vídeo incorporado não está disponível.'));
          return;
        }

        iframe.style.pointerEvents = 'auto';
        embedGate.remove();
        const command = embedGate.dataset.postVideoProvider === 'youtube'
          ? { event: 'command', func: 'playVideo', args: [] }
          : { method: 'play' };
        iframe.contentWindow.postMessage(JSON.stringify(command), '*');
      });
      return;
    }

    const videoLink = target && target.closest('a[data-post-video-id]');
    if (!videoLink) return;

    if (videoLink.dataset.postVideoAuthorized === 'true') {
      delete videoLink.dataset.postVideoAuthorized;
      return;
    }

    event.preventDefault();
    event.stopImmediatePropagation();
    authorizeAndContinue(videoLink.dataset.postVideoId, function () {
      if (!videoLink.isConnected) return;
      videoLink.dataset.postVideoAuthorized = 'true';
      videoLink.click();
    });
  }, true);

  const becomeProButton = document.getElementById('openBecomeProFromVideoLimit');
  if (becomeProButton) {
    becomeProButton.addEventListener('click', function () {
      const limitModal = window.jQuery('#postVideoLimitModal');
      limitModal.one('hidden.bs.modal', function () {
        window.jQuery('#becomeProModal').modal('show');
      });
      limitModal.modal('hide');
    });
  }

  const becomeProForm = document.getElementById('formBecomePro');
  if (becomeProForm) {
    window.jQuery(becomeProForm).on('submit', function (event) {
      event.preventDefault();

      const button = window.jQuery('#btnConfirmBecomePro');
      button.data('original-text', button.html());
      button.prop('disabled', true)
        .html('<i class="spinner-border spinner-border-sm mr-1"></i> Processando...');

      window.jQuery.ajax({
        url: becomeProForm.action,
        type: 'POST',
        data: window.jQuery(becomeProForm).serialize(),
        success: function (result) {
          if (result.success) {
            window.jQuery('#becomeProModal').modal('hide');
            window.swal({
              title: @json(__('general.congratulations')),
              text: result.message,
              type: 'success',
              confirmButtonText: @json(__('users.ok'))
            }, function () {
              window.location.reload();
            });
            return;
          }

          if (result.message && result.message.toLowerCase().includes('saldo')) {
            window.jQuery('#insufficientProWarning').removeClass('display-none');
          } else {
            window.jQuery('#errorBecomePro')
              .removeClass('display-none')
              .find('span')
              .text(result.message);
          }
          resetBecomeProButton(button);
        },
        error: function (xhr) {
          const message = xhr.responseJSON && xhr.responseJSON.message
            ? xhr.responseJSON.message
            : 'Ocorreu um erro ao processar.';
          window.jQuery('#errorBecomePro')
            .removeClass('display-none')
            .find('span')
            .text(message);
          resetBecomeProButton(button);
        }
      });
    });
  }

  function resetBecomeProButton(button) {
    button.prop('disabled', false)
      .html(button.data('original-text') || 'Confirmar');
  }
})();
</script>
