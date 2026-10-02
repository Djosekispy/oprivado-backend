<!-- Modal Destacar Perfil -->
<div class="modal fade" id="highlightProfileModal" tabindex="-1" aria-labelledby="highlightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow overflow-hidden">

            <!-- Cover -->
            <div class="position-relative"
                 style="height:85px;
                        background: {{$settings->color_default}}
                        @if($user->cover)
                            url('{{ Helper::getFile(config('path.cover').$user->cover) }}')
                        @endif
                        no-repeat center center;
                        background-size:cover;">

                <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                     width="70"
                     height="70"
                     class="rounded-circle border border-3 border-white shadow position-absolute"
                     style="left:50%;bottom:-35px;transform:translateX(-50%);">
            </div>

            <div class="modal-body pt-5 px-4">

                <div class="text-center mb-3">
					 <h6 class="card-title mb-1" id="highlightModalLabel">
                        Destacar perfil
                    </h6>

                    <small class="text-muted">
                        Mais visibilidade durante 20 dias
                    </small>
                </div>

                <!-- Informações -->
                <div class="bg-light rounded p-3 mb-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                           Montante à pagar
                        </span>

                        <strong>
                            5.000 {{ $settings->currency_symbol }}
                        </strong>
                    </div>
                 
                </div>

                <div id="insufficientWarning"
                     class="alert alert-danger py-2 text-center small display-none">
                   Saldo insuficiente, carregue sua carteira
                </div>

                <form method="POST"
                      action="{{ url('highlight-profile') }}"
                      id="formHighlightProfile">
                    @csrf

                    <div id="errorHighlight"
                         class="alert alert-danger py-2 small display-none">
                        <span id="showErrorsHighlight"></span>
                    </div>

                    <button type="submit"
                            id="btnConfirmHighlight"
                            class="btn btn-primary btn-block">
                        Confirmar
                    </button>

                    <button type="button"
                            class="btn btn-link btn-block text-muted mt-2"
                            data-dismiss="modal">
                        Cancelar
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>