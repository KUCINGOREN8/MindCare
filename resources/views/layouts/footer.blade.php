<div class="bg-[#1F2937] flex flex-col items-center w-full py-10 px-6 sm:px-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 w-full max-w-7xl mb-10">
        {{-- Logo --}}
        <div class="flex flex-col items-center lg:items-start text-center lg:text-left space-y-4">
            <div class="flex shrink-0 items-center">
                <img src="img/logo2.svg" alt="Your Company" class="h-8 w-auto" />
            </div>
            <div class="pt-3">
                {{-- SLOGAN --}}
                <p class="text-[#9CA3AF] text-sm leading-relaxed max-w-xs">
                    {{ __('footer.slogan') }}
                </p>
            </div>
            <div class="flex pt-2 space-x-4">
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
        <div class="flex flex-col items-center lg:items-start text-center lg:text-left space-y-3">
            <h3 class="text-white font-semibold text-lg mb-1">{{ __('footer.services') }}</h3>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.session_booking') }}</p>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.realtime_counseling') }}</p>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.progress_reports') }}</p>
            <a href="{{ route('psychologist.signup.step1') }}"
                class="text-[#9CA3AF] text-sm font-semibold hover:text-white transition-colors">
                {{ __('footer.join_psychologist') }}
            </a>
        </div>

        {{-- Support --}}
        <div class="flex flex-col items-center lg:items-start text-center lg:text-left space-y-3">
            <h3 class="text-white font-semibold text-lg mb-1">{{ __('footer.support') }}</h3>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.help_center') }}</p>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.privacy_policy') }}</p>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.terms_of_service') }}</p>
            <p class="text-[#9CA3AF] text-sm hover:text-white transition-colors cursor-default">
                {{ __('footer.about_us') }}</p>
        </div>

        {{-- Contact --}}
        <div class="flex flex-col items-center lg:items-start text-center lg:text-left space-y-3">
            <h3 class="text-white font-semibold text-lg mb-1">{{ __('footer.contact') }}</h3>
            <div class="flex items-center space-x-3 text-[#9CA3AF]">
                <img src="/img/telephone.svg" alt="Telephone" class="h-4 w-4 flex-shrink-0">
                <p class="text-sm">+1 (123) 123-4567</p>
            </div>
            <div class="flex items-center space-x-3 text-[#9CA3AF]">
                <img src="/img/mail.svg" alt="Mail" class="h-4 w-4 flex-shrink-0">
                <p class="text-sm">support@beokay.com</p>
            </div>
            <div class="flex items-center space-x-3 text-[#9CA3AF]">
                <img src="/img/ticker2.svg" alt="Clock" class="h-4 w-4 flex-shrink-0">
                <p class="text-sm">{{ __('footer.available_247') }}</p>
            </div>
        </div>
    </div>
    <div>
        <div class="w-full border-t border-gray-700 pt-8 text-center">
            {{-- Copyright Dynamic Year --}}
            <h5 class="text-[#9CA3AF] text-sm">
                {{ __('footer.rights_reserved', ['year' => date('Y')]) }}
            </h5>
        </div>
    </div>
</div>
