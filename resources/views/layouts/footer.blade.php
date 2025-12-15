<div class="bg-[#1F2937] flex flex-col items-center">
    <div class="flex m-10 justify-between">
        {{-- Logo --}}
        <div class="m-5 w-1/6">
            <div class="flex shrink-0 items-center">
                <img src="img/logo2.svg" alt="Your Company" class="h-8 w-auto" />
            </div>
            <div class="pt-3">
                {{-- SLOGAN --}}
                <p class="text-[#9CA3AF] justify-center items-center text-justify">
                    {{ __('footer.slogan') }}
                </p>
            </div>
            <div class="pt-2 space-x-2">
                <a href="https://facebook.com" target="_blank"
                    class="inline-block transition-transform duration-200 hover:scale-110">
                    <img src="/img/facebook.svg" alt="Facebook" class="h-5 w-5">
                </a>
                <a href="https://twitter.com" target="_blank"
                    class="inline-block transition-transform duration-200 hover:scale-110">
                    <img src="/img/twitter.svg" alt="Twitter" class="h-5 w-5">
                </a>
                <a href="https://instagram.com" target="_blank"
                    class="inline-block transition-transform duration-200 hover:scale-110">
                    <img src="/img/instagram.svg" alt="Instagram" class="h-5 w-5">
                </a>
                <a href="https://linkedin.com" target="_blank"
                    class="inline-block transition-transform duration-200 hover:scale-110">
                    <img src="/img/linkedin.svg" alt="LinkedIn" class="h-5 w-5">
                </a>
            </div>
        </div>

        {{-- Service --}}
        <div class="m-5 w-1/6">
            <h3 class="text-white font-semibold text-lg">{{ __('footer.services') }}</h3>
            <p class="text-[#9CA3AF] font-semibold pt-2">{{ __('footer.session_booking') }}</p>
            <p class="text-[#9CA3AF] font-semibold">{{ __('footer.realtime_counseling') }}</p>
            <p class="text-[#9CA3AF] font-semibold">{{ __('footer.progress_reports') }}</p>
            <a href="{{ route('psychologist.signup.step1') }}"
                class="text-[#9CA3AF] font-semibold hover:text-white transition-colors">
                {{ __('footer.join_psychologist') }}
            </a>
        </div>

        {{-- Support --}}
        <div class="m-5 w-1/6">
            <h3 class="text-white font-semibold text-lg">{{ __('footer.support') }}</h3>
            <p class="text-[#9CA3AF] font-semibold pt-2">{{ __('footer.help_center') }}</p>
            <p class="text-[#9CA3AF] font-semibold">{{ __('footer.privacy_policy') }}</p>
            <p class="text-[#9CA3AF] font-semibold">{{ __('footer.terms_of_service') }}</p>
            <p class="text-[#9CA3AF] font-semibold">{{ __('footer.about_us') }}</p>
        </div>

        {{-- Contact --}}
        <div class="m-5 w-1/6">
            <h3 class="text-white font-semibold text-lg">{{ __('footer.contact') }}</h3>
            <div class="pt-3 flex items-center space-x-2">
                <img src="/img/telephone.svg" alt="Telephone" class="h-4 w-4">
                <p class="text-[#9CA3AF] font-semibold">+1 (123) 123-4567</p>
            </div>
            <div class="pt-1 flex items-center space-x-2">
                <img src="/img/mail.svg" alt="Mail" class="h-4 w-4">
                <p class="text-[#9CA3AF] font-semibold">support@beokay.com</p>
            </div>
            <div class="pt-1 flex items-center space-x-2">
                <img src="/img/ticker2.svg" alt="Clock" class="h-4 w-4">
                <p class="text-[#9CA3AF] font-semibold">{{ __('footer.available_247') }}</p>
            </div>
        </div>
    </div>
    <div>
        <div class="fixed-bottom text-center pb-10">
            {{-- Copyright Dynamic Year --}}
            <h5 class="text-[#9CA3AF]">
                {{ __('footer.rights_reserved', ['year' => date('Y')]) }}
            </h5>
        </div>
    </div>
</div>
