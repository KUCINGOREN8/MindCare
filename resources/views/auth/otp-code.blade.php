<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('messages.otptitle') }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #00C3B3; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
            <h1 style="margin: 0; font-size: 28px;">BeOkay</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">{{ __('messages.mentalhealth') }}</p>
        </div>

        <div
            style="background: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #1f2937; margin-top: 0;">{{ __('otptitlecode') }}</h2>

            <p style="margin-bottom: 16px;">{{ __('messages.hello') }},</p>

            <p style="margin-bottom: 20px;">{{ __('messages.otpdesc') }}:</p>

            <div
                style="background: #f8fafc; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 8px; margin: 20px 0; border-radius: 8px; border: 2px dashed #00C3B3;">
                {{ $otpCode }}
            </div>

            <p style="margin: 20px 0; color: #ef4444; font-weight: 600;">
                ⚠️ {{ __('otpwarning') }}.
            </p>

            <p style="color: #6b7280; font-size: 14px;">
                {{ __('messages.otpignore') }}.
            </p>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p style="margin: 0; color: #6b7280;">
                    {{ __('messages.bestregard') }},<br>
                    <strong>BeOkay Team</strong>
                </p>
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #9ca3af;">
                    {{ __('messages.otpauto') }}.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
