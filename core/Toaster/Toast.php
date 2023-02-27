<?php
namespace Core\Toaster;

class Toast
{
    public function success(string $message): string
    {
        return "<div class='success'>$message</div>";
    }

    public function error(string $message): string
    {
        return "<div class='error'>$message</div>";
    }

    public function warning(string $message): string
    {
        return "<div class='warning'>$message</div>";
    }
}