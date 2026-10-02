<style>
    .modal-content {
        animation: fadeInUp 0.3s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .btn-loading {
        pointer-events: none;
        position: relative;
        color: transparent !important;
    }
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255,255,255,0.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .btn-outline-secondary.btn-loading::after {
        border-color: rgba(108, 117, 125, 0.4);
        border-top-color: #6c757d;
    }
    @keyframes spin {
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

@if(auth()->check() && auth()->user()->acceptSensitiveContent === null)
<div class="modal fade" id="sensitiveModal" tabindex="-1" aria-labelledby="sensitiveModalLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
 
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold" id="sensitiveModalLabel">
                    ⚠️ Conteúdo sensível (adulto)
                </h5>
            </div>
          
            <div class="modal-body">
                <p class="mb-3"> No Oprivado contém conteúdos sensíveis (imagens e vídeos) produzidos por outros criadores de conteúdos dentro da plataforma.</p>
                <p class="fw-semibold mb-1">
                    Deseja permitir a visualização de conteúdo sensível?
                </p>
                <div class="small text-muted">
                    Pode alterar a qualquer momento nas suas definições.
                </div>
            </div>

            <!-- Formulário -->
            <form id="sensitiveForm">
                <!-- CSRF -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <!-- Allow Sensitive Content -->
                <input type="hidden" name="allowSensitiveContent" id="allowInput" value="false">

                <div class="modal-footer border-0 gap-2">
                    <button type="button" id="btn-no"
                            class="btn btn-outline-secondary px-4 py-2"
                            onclick="submitSensitive(false)">
                        Não permitir
                    </button>
                  
                    <button type="button" id="btn-yes"
                            class="btn btn-primary px-4 py-2"
                            onclick="submitSensitive(true)">
                        Sim, permitir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(auth()->check() && auth()->user()->acceptSensitiveContent === null)
        const sensitiveModal = new bootstrap.Modal(document.getElementById('sensitiveModal'), {
            backdrop: 'static',
            keyboard: false
        });
        sensitiveModal.show();
    @endif
});

function submitSensitive(allow) {
    const btnNo = document.getElementById('btn-no');
    const btnYes = document.getElementById('btn-yes');
   
    btnNo.disabled = true;
    btnYes.disabled = true;
    const activeBtn = allow ? btnYes : btnNo;
    activeBtn.classList.add('btn-loading');

    document.getElementById('allowInput').value = allow;

    $.ajax({
        url: '/user/sensitive-content',
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            allowSensitiveContent: allow
        },
        success: function (response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || 'Ocorreu um erro.');
                resetButtons();
            }
        },
        error: function (xhr) {
            console.error(xhr.responseText);
           
            if (xhr.status === 419) {
                alert('Erro 419 - Token expirado.\n\nAtualize a página (F5) e tente novamente.');
            } else {
                alert('Erro ao processar o pedido. Tente novamente.');
            }
           
            resetButtons();
        }
    });
}

function resetButtons() {
    const btnNo = document.getElementById('btn-no');
    const btnYes = document.getElementById('btn-yes');
   
    btnNo.disabled = false;
    btnYes.disabled = false;
    btnNo.classList.remove('btn-loading');
    btnYes.classList.remove('btn-loading');
}
</script>