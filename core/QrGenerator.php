<?php

class QrGenerator
{
    public function generate($str)
    {
        QRcode::png($str);
    }
}