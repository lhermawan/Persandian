<?php 

namespace App\Traits;

trait FormatMessageTraits
{
    protected function format(string $messages, string $string = null)
    {
        return str_replace('%xxx%', $string, $messages);
    }

    protected function returnMessage(string $level, string $message = null)
    {
        return (object)[
            'level' => $level,
            'message' => $message
        ];
    }

}