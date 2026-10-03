@extends('admin.layout')

@section('content')
	<h5 class="mb-4 fw-light">
		<a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
		<i class="bi-chevron-right me-1 fs-6"></i>
		<span class="text-muted">Utilizadores Pro ({{ $data->total() }})</span>
	</h5>

	<div class="content">
		<div class="row g-3 mb-3">
			<div class="col-md-4">
				<div class="card shadow-custom border-0 h-100">
					<div class="card-body">
						<div class="text-muted">Pro ativos</div>
						<div class="fs-3 fw-semibold">{{ number_format($activeCount) }}</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card shadow-custom border-0 h-100">
					<div class="card-body">
						<div class="text-muted">Expiram nos próximos 7 dias</div>
						<div class="fs-3 fw-semibold">{{ number_format($expiringCount) }}</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card shadow-custom border-0 h-100">
					<div class="card-body">
						<div class="text-muted">Expirados aguardando atualização</div>
						<div class="fs-3 fw-semibold">{{ number_format($expiredCount) }}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow-custom border-0">
			<div class="card-body p-lg-4">
				<form action="{{ url('panel/admin/pro-users') }}" method="get" class="row g-2 align-items-end mb-3">
					<div class="col-lg-4 col-md-6">
						<label for="proSearch" class="form-label">Pesquisar utilizador</label>
						<input id="proSearch" type="search" name="q" class="form-control" value="{{ $search }}" placeholder="Nome, username ou email">
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="proStatus" class="form-label">Estado</label>
						<select id="proStatus" name="status" class="form-select">
							<option value="active" @selected($status === 'active')>Ativos</option>
							<option value="expired" @selected($status === 'expired')>Expirados</option>
							<option value="all" @selected($status === 'all')>Todos</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="proExpires" class="form-label">Vencimento</label>
						<select id="proExpires" name="expires" class="form-select">
							<option value="">Qualquer data</option>
							<option value="7" @selected($expires === '7')>Próximos 7 dias</option>
							<option value="30" @selected($expires === '30')>Próximos 30 dias</option>
						</select>
					</div>
					<div class="col-lg-2 col-md-6">
						<label for="proSort" class="form-label">Ordenar por</label>
						<select id="proSort" name="sort" class="form-select">
							<option value="expiry_asc" @selected($sort === 'expiry_asc')>Expiração mais próxima</option>
							<option value="expiry_desc" @selected($sort === 'expiry_desc')>Expiração mais distante</option>
							<option value="started_desc" @selected($sort === 'started_desc')>Ativação mais recente</option>
							<option value="name_asc" @selected($sort === 'name_asc')>Nome</option>
						</select>
					</div>
					<div class="col-lg-2 d-flex gap-2">
						<button type="submit" class="btn btn-primary flex-grow-1">Filtrar</button>
						<a href="{{ url('panel/admin/pro-users') }}" class="btn btn-outline-secondary" title="Limpar filtros" aria-label="Limpar filtros">
							<i class="bi bi-x-lg"></i>
						</a>
					</div>
				</form>

				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="text-muted">{{ number_format($data->total()) }} resultado(s)</span>
				</div>

				<div class="table-responsive">
					<table class="table table-hover align-middle">
						<thead>
							<tr>
								<th>ID</th>
								<th>Utilizador</th>
								<th>Email</th>
								<th>Início do Pro</th>
								<th>Expiração</th>
								<th>Estado</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($data as $user)
								@php($isActive = $user->pro_expires_at->isFuture())
								<tr>
									<td>{{ $user->id }}</td>
									<td>
										<a href="{{ url($user->username) }}" target="_blank" rel="noopener">
											<img src="{{ Helper::getFile(config('path.avatar') . $user->avatar) }}" width="36" height="36" class="rounded-circle me-1" alt="">
											{{ $user->name }}
										</a>
										<div class="small text-muted">{{ '@' . $user->username }}</div>
									</td>
									<td>{{ $user->email }}</td>
									<td>{{ $user->pro_started_at ? $user->pro_started_at->format('d/m/Y H:i') : '—' }}</td>
									<td>
										{{ $user->pro_expires_at->format('d/m/Y H:i') }}
										<div class="small text-muted">{{ $user->pro_expires_at->diffForHumans() }}</div>
									</td>
									<td>
										@if ($isActive)
											<span class="badge rounded-pill bg-success">Ativo</span>
										@else
											<span class="badge rounded-pill bg-secondary">Expirado</span>
										@endif
									</td>
									<td>
										<a href="{{ url('panel/admin/members/edit/' . $user->id) }}" class="btn btn-outline-primary btn-sm" title="Editar utilizador" aria-label="Editar utilizador">
											<i class="bi bi-pencil"></i>
										</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="7" class="text-center text-muted py-5">Nenhum utilizador Pro encontrado.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

				@if ($data->lastPage() > 1)
					{{ $data->onEachSide(1)->links() }}
				@endif
			</div>
		</div>
	</div>
@endsection