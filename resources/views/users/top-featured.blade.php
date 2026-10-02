@extends('layouts.app')

@section('title', $title ?? 'Criadores em destaque')

@section('content')
<section>
    <div class="container pt-5 mt-5 pb-5">

        <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-12 py-5">
			<h2 class="mb-0 font-montserrat">Criadores em destaque</h2>
        </div>
      </div>

        @if (isset($error_message))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $error_message }}
            </div>
        @endif

        @if ($topUsers->isNotEmpty())

            <div class="row g-4">

                @foreach ($topUsers as $user)

                    <div class="col-md-6 mb-4 col-lg-4">

                        <a href="{{ url($user->username) }}"
                           class="creator-card">

                            <div class="creator-cover">

                                @if ($user->cover)
                                    <img
                                        src="{{ Helper::getFile(config('path.cover').$user->cover) }}"
                                        alt="{{ $user->username }}">
                                @else
                                    <div class="cover-placeholder"></div>
                                @endif

                                <div class="cover-overlay"></div>
                            </div>

                            <div class="creator-content">

                                <img
                                    src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}"
                                    alt="{{ $user->username }}"
                                    class="creator-avatar">

                                <h5 class="creator-name">

                                    {{ $user->hide_name == 'yes'
                                        ? '@'.$user->username
                                        : $user->name }}

                                    @if ($user->verified_id == 'yes')
                                        <i class="bi bi-patch-check-fill verified-icon"></i>
                                    @endif

                                </h5>

                                <span class="creator-username">
                                    @ {{ $user->username }}
                                </span>

                            </div>

                        </a>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-stars"></i>
                </div>

                <h4>
                    Nenhum perfil em destaque
                </h4>

                <p>
                    Os criadores que promoverem os seus perfis aparecerão aqui.
                </p>

            </div>

        @endif

    </div>
</section>

<style>
.featured-section {
    background: #fafafa;
    min-height: 100vh;
}

/* HEADER */

.featured-header {
    max-width: 720px;
    margin: 0 auto;
}

.featured-pill {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(255, 159, 67, .12);
    color: #ff7a00;
    font-size: .85rem;
    font-weight: 600;
}

.featured-title {
    font-size: 2.3rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 12px;
}

.featured-description {
    color: #6b7280;
    font-size: 1rem;
    margin-bottom: 0;
}

/* CARD */

.creator-card {
    display: block;
    overflow: hidden;
    text-decoration: none;
    background: #fff;
    border-radius: 24px;
    transition: all .3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}

.creator-cover {
    position: relative;
    height: 240px;
    overflow: hidden;
}

.creator-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(
        135deg,
        #ff7a59,
        #ff5f6d
    );
}

.cover-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(0,0,0,.45),
        transparent
    );
}

.featured-badge {
    position: absolute;
    top: 16px;
    left: 16px;

    background: rgba(255,255,255,.95);
    backdrop-filter: blur(12px);

    color: #111827;
    font-size: .8rem;
    font-weight: 600;

    padding: 8px 14px;
    border-radius: 999px;
}

.creator-content {
    position: relative;
    text-align: center;
    padding: 0 24px 24px;
    margin-top: -55px;
}

.creator-avatar {
    width: 110px;
    height: 110px;
    object-fit: cover;

    border-radius: 50%;
    border: 4px solid #fff;

    box-shadow: 0 12px 25px rgba(0,0,0,.12);
}

.creator-name {
    margin-top: 18px;
    margin-bottom: 5px;

    color: #111827;
    font-size: 1.15rem;
    font-weight: 700;
}

.creator-username {
    color: #6b7280;
    font-size: .95rem;
}

.verified-icon {
    color: #ff8035!important;
    margin-left: 4px;
}

/* EMPTY STATE */

.empty-state {
    background: #fff;
    border-radius: 24px;
    padding: 80px 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    background: rgba(255,159,67,.1);

    font-size: 2rem;
    color: #ff7a00;
}

.empty-state h4 {
    color: #111827;
    font-weight: 700;
    margin-bottom: 10px;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 0;
}

/* RESPONSIVO */

@media (max-width: 768px) {

    .featured-title {
        font-size: 1.8rem;
    }

    .creator-cover {
        height: 200px;
    }

    .creator-avatar {
        width: 95px;
        height: 95px;
    }
}
</style>
@endsection