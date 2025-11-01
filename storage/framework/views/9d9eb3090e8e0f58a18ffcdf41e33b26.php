<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="overflow-hidden">

    <div class="flex" style="height: 100vh;">
        <!-- Left Container-->
        <div class="w-full lg:w-[70%] flex items-center justify-center bg-white overflow-y-auto" style="padding-top: 12rem; padding-bottom: 4rem; padding-left: 2rem; padding-right: 2rem;">
            <div class="w-full max-w-lg">
                <h2 class="text-5xl font-bold mb-8 text-center" style="color: #009C8F;">SIGN UP</h2>

                <form class="space-y-6" method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <img src="<?php echo e(asset('assets/signup/user.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="text" name="full_name" placeholder="Full Name" class="w-full outline-none text-black placeholder-gray-400 bg-transparent" value="<?php echo e(old('full_name')); ?>">
                        </div>
                        <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-2 ml-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <img src="<?php echo e(asset('assets/signup/email.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="email" name="email" placeholder="Email" class="w-full outline-none text-black placeholder-gray-400 bg-transparent" value="<?php echo e(old('email')); ?>">
                        </div>
                         <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-sm mt-2 ml-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <img src="<?php echo e(asset('assets/signup/password.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                        <input type="password" name="password" placeholder="Password" class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                    </div>

                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <img src="<?php echo e(asset('assets/signup/password.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                    </div>

                   <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <img src="<?php echo e(asset('assets/signup/calender.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                        <input type="date" name="date_of_birth" class="w-full outline-none bg-transparent" style="color: #9CA3AF;" onchange="this.style.color = '#000000'" value="<?php echo e(old('date_of_birth')); ?>">
                    </div>

                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <img src="<?php echo e(asset('assets/signup/gender.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                        <select name="gender" class="w-full outline-none bg-transparent text-gray-400" onchange="this.style.color = this.value ? '#000000' : '#9CA3AF'">
                            <option value="" disabled selected class="text-gray-400">Gender</option>
                            <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?> class="text-black">Male</option>
                            <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?> class="text-black">Female</option>
                            <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?> class="text-black">Other</option>
                        </select>
                    </div>

                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <img src="<?php echo e(asset('assets/signup/language.svg')); ?>" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                        <select name="language" class="w-full outline-none bg-transparent text-gray-400" onchange="this.style.color = this.value ? '#000000' : '#9CA3AF'">
                            <option value="" disabled selected class="text-gray-400">Preferred Language</option>
                            <option value="en" <?php echo e(old('language') == 'en' ? 'selected' : ''); ?> class="text-black" >English</option>
                            <option value="id" <?php echo e(old('language') == 'id' ? 'selected' : ''); ?> class="text-black">Indonesian</option>
                        </select>
                    </div>

                    <div class="flex items-start pt-2">
                        <input type="checkbox" name="terms" id="terms" class="w-4 h-4 mt-0.5 mr-3" style="accent-color: #009C8F;">
                        <label for="terms" class="text-sm text-gray-500">Agree to Terms & Privacy Policy</label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full text-white py-3 rounded-lg font-medium hover:opacity-90 transition text-base shadow-md" style="background-color: #009C8F;">
                            Sign up
                        </button>
                    </div>

                    <div class="text-center pt-3">
                        <p class="text-gray-600 text-sm">
                            Already have an account?
                            <a href="#" class="font-medium underline" style="color: #009C8F;">Sign in</a>
                            here.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Container -->
        <div class="hidden lg:block lg:w-[30%] relative">
            <img src="<?php echo e(asset('assets/signup/right-image.jpg')); ?>" alt="Person writing" class="w-full h-full object-cover">
        </div>
    </div>

</body>
</html>

 <style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: opacity(0.3) grayscale(1);
    }
</style>
<?php /**PATH C:\Users\Bryan\Documents\MindCare\MindCare\resources\views/signup.blade.php ENDPATH**/ ?>