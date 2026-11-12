<!-- Modal Tornar-se Pro -->
<div class="modal fade" id="becomeProModal" tabindex="-1" aria-labelledby="becomeProModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow overflow-hidden">

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
                    <h6 class="card-title mb-1" id="becomeProModalLabel">
                        Oprivado Pro <i style="ffont-size: 12px;color: #fd7350;" class="bi-star-fill mr-1"></i>
                    </h6>

                    <small class="text-muted">
                        Acesso premium por 31 dias
                    </small>
					
					<div class="text-center mt-3">
					 <p style="font-size: 13px!important;" class="mb-1"> <i style=" font-size: 11px; color: #fd7350; " class="fa fa-check mr-1" aria-hidden="true"></i>Acesso exclusivo às mensagens</p>
					<p style="font-size: 13px!important;" class="mb-1"> <i style=" font-size: 11px; color: #fd7350; " class="fa fa-check mr-1" aria-hidden="true"></i>Acesso aos comentários em posts</p>
						<p style="font-size: 13px!important;" class="mb-1"> <i style=" font-size: 11px; color: #fd7350; " class="fa fa-check mr-1" aria-hidden="true"></i> Maior credibilidade</p>
						<p style="font-size: 13px!important;" class="mb-1"> <i style=" font-size: 11px; color: #fd7350; " class="fa fa-check mr-1" aria-hidden="true"></i> Mais opções de privacidade</p>
					<p style="font-size: 13px!important;" class="mb-1"> <i style=" font-size: 11px; color: #fd7350; " class="fa fa-check mr-1" aria-hidden="true"></i> Atendimento prioritário</p>
					</div>
					
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                           Montante à pagar
                        </span>

                        <strong>
                            {{ number_format(config('app.pro_price', 5000), 0, ',', '.') }} {{ $settings->currency_symbol }}
                        </strong>
                    </div>
                </div>

                <div id="insufficientProWarning"
                     class="alert alert-danger py-2 text-center small display-none">
                   Saldo insuficiente, <a href="https://oprivado.ao/my/wallet" class="stretched-link text-underline text-white"> carregue sua carteira</a>
                </div>

                <form method="POST"
                      action="{{ url('become-pro') }}"
                      id="formBecomePro">
                    @csrf

                    <div id="errorBecomePro"
                         class="alert alert-danger py-2 small display-none">
                        <span id="showErrorsBecomePro"></span>
                    </div>

                    <button type="submit"
                            id="btnConfirmBecomePro"
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
