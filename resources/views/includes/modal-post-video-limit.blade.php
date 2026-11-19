<div class="modal fade" id="postVideoLimitModal" tabindex="-1" role="dialog" aria-labelledby="postVideoLimitTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="postVideoLimitTitle">Limite de vídeos atingido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="postVideoLimitMessage" class="mb-0">
                    Atingiu o limite de vídeos que pode ver neste período. Torne-se PRO para continuar.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Fechar</button>
                @if (auth()->user()->verified_id !== 'yes')
                    <button type="button" id="openBecomeProFromVideoLimit" class="btn btn-primary">
                        Tornar-se PRO
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
