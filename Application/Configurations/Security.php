<?php

// Security Definitions
define("SECURITY_CIPHER_METHOD", "aes-256-cbc");
define("SECURITY_SECRET_KEY", openssl_digest(php_uname("r"), "sha256", true));
define("SECURITY_SECRET_IV", openssl_random_pseudo_bytes(openssl_cipher_iv_length(SECURITY_CIPHER_METHOD)));
define("SECURITY_HASHING_ALGORITHM", "sha256");
