<?php
/**
 * This function generates a random string
 * @param  integer $Length The length of the random string
 * @param  string  $Chars  The Charset to use
 * @return string
 * @author Kyle Florence <kyle.florence@gmail.com>
 */
function Rand_Str($Length = 32, $Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
{
    $Chars_Length = (strlen($Chars) - 1);
    $String = $Chars{rand(0, $Chars_Length)};
    for ($I = 1; $I < $Length; $I = strlen($String))
    {
        $R = $Chars{rand(0, $Chars_Length)};
        if ($R != $String{$I - 1}) $String .=  $R;
    }
    return $String;
}
?>