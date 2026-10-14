@foreach ($messagesInbox as $msg)
    {{-- Ignora mensagens cujo usuário foi apagado (remitter null) --}}
    @continue(! $msg->remitter())

    @php
        $erro = null;
        $html = '';

        try {
            // Verificações que mostram exatamente o que está faltando
            if (! isset($settings)) {
                throw new \Exception('$settings não está definida nesta view');
            }
            if (! auth()->check()) {
                throw new \Exception('auth()->user() é null (sessão expirada?)');
            }
            if (! $msg->sender) {
                throw new \Exception('sender é null (relação inexistente para a msg #' . $msg->id . ')');
            }
            if (! $msg->receiver) {
                throw new \Exception('receiver é null (relação inexistente para a msg #' . $msg->id . ')');
            }

            // Renderiza o item dentro do try para capturar erros do próprio Blade
            $html = view('includes.inbox-item', [
                'msg'      => $msg,
                'settings' => $settings,
            ])->render();

        } catch (\Throwable $e) {
            $erro = $e;
            \Log::error('Erro no inbox (msg #' . $msg->id . '): ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    @endphp

    @if ($erro)
        <div class="alert alert-danger m-2 rounded-0" style="font-size:12px;">
            <strong>Erro na mensagem #{{ $msg->id }}</strong><br>
            {{ $erro->getMessage() }}<br>
            <small>{{ basename($erro->getFile()) }}:{{ $erro->getLine() }}</small>
        </div>
    @else
        {!! $html !!}
    @endif
@endforeach

@if ($messagesInbox->count() == 0)
    <div class="card border-0 text-center">
        <div class="card-body">
            <h4 class="mb-0 font-montserrat mt-2"></h4>
            <p class="lead text-muted mt-0">{{ __('general.no_chats') }}</p>
        </div>
    </div>
@endif

@if ($messagesInbox->hasMorePages())
    <div class="btn-block text-center d-none">
        {{ $messagesInbox->appends(['q' => request('q')])->links('vendor.pagination.loadmore') }}
    </div>
@endif