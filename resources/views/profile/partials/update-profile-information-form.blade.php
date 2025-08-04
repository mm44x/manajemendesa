<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="dark:text-white" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full bg-white text-gray-900 border-gray-300 
                       dark:bg-gray-700 dark:text-white dark:border-gray-600"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 dark:text-red-400" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-white" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full bg-white text-gray-900 border-gray-300 
                       dark:bg-gray-700 dark:text-white dark:border-gray-600"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 dark:text-red-400" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-white">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 
                                   dark:text-gray-300 dark:hover:text-white
                                   rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-600 border border-transparent 
           rounded-md font-semibold text-xs text-white uppercase tracking-widest 
           hover:bg-blue-700 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 
           focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>


            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
