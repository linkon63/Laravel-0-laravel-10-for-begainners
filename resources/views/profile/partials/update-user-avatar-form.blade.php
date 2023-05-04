<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            User Avatar
        </h2>
        <img width="50" height="50" class="rounded-full" src="{{"/storage/$user->avatar"}}" alt="avatar image" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Add or update user avatar
        </p>
    </header>


    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if (session('message'))
    <div class="border text-red">
        {{ session('message') }}
    </div>
    @endif

    <form method="post" action="{{route('profile.avatar')}}" class="mt-6 space-y-6=" enctype="multipart/form-data">
        @method('patch')
        @csrf
        <div>
            <x-input-label for="name" value="Avatar" />
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" :value="old('avatar', $user->avatar)" autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>