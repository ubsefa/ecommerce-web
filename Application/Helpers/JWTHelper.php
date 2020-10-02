<?php

namespace Helpers;

class JWTHelper
{
    public static function encode($payload)
    {
        $header = array("typ" => "jwt", "alg" => SECURITY_HASHING_ALGORITHM);
        $segments = array();
        array_push($segments, self::urlsafeB64Encode(json_encode($header)));
        array_push($segments, self::urlsafeB64Encode(json_encode($payload)));
        $signature = hash_hmac(SECURITY_HASHING_ALGORITHM, implode(".", $segments), SECURITY_SECRET_KEY, true);
        array_push($segments, self::urlsafeB64Encode($signature));
        return implode(".", $segments);
    }

    public static function decode($jwt)
    {
        $tks = explode(".", $jwt);
        list($headB64, $payloadB64, $cryptoB64) = $tks;
        $payload = json_decode(self::urlsafeB64Decode($payloadB64));
        return hash_hmac(SECURITY_HASHING_ALGORITHM, "$headB64.$payloadB64", SECURITY_SECRET_KEY, true) != self::urlsafeB64Decode($cryptoB64) ? false : $payload;
    }

    public static function urlsafeB64Encode($value)
    {
        return str_replace("=", "", strtr(base64_encode($value), "+/", "-_"));
    }

    public static function urlsafeB64Decode($value)
    {
        $remainder = strlen($value) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $value .= str_repeat("=", $padlen);
        }
        return base64_decode(strtr($value, "-_", "+/"));
    }
}
