<?php

namespace App\Helper;

class InvoiceGenerator
{
    public function generateInvoicer($input, $padLen = 7, $prefix = null)
    {
        if (is_string($prefix)) {
            return sprintf("%s%s", $prefix, str_pad($input, $padLen, "0", STR_PAD_LEFT));
        }

        return str_pad($input, $padLen, "0", STR_PAD_LEFT);
    }
}