<x-app-layout>
    @section('title', 'Edit Profil')
    @php $pageTitle = 'Edit Profil'; @endphp

    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <span>Profil</span>
            </div>
            <h2 class="page-title">Edit Profil</h2>
            <p class="page-sub">Kelola informasi akun dan keamanan Anda</p>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:20px;max-width:600px">
        <div class="panel" style="padding:24px">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="panel" style="padding:24px">
            @include('profile.partials.update-password-form')
        </div>

        <div class="panel" style="padding:24px">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
