<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    Mail::raw('This is a test email from your Laravel application to verify Gmail SMTP configuration is working correctly.', function($message) {
        $message->to('cosmasasango12@gmail.com')
                ->subject('Laravel Email Test - Quote Notification System')
                ->from('cosmasasango12@gmail.com', 'Wood Nork Green ERP');
    });
    
    echo "✅ Test email sent successfully to cosmasasango12@gmail.com\n";
    echo "📧 Please check your Gmail inbox (and spam folder)\n";
    echo "🔧 If you don't receive it, there might be an SMTP configuration issue\n";
    
} catch (Exception $e) {
    echo "❌ Failed to send email: " . $e->getMessage() . "\n";
    echo "🔧 Please check your Gmail SMTP settings and app password\n";
}