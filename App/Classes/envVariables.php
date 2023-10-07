<?php
    define("MODO", "production");
    
    ////////////////////
    // Credenciales
    ////////////////////
    if (MODO === "sandbox") {
        // Base de datos
        define("HOST", "172.16.0.2");
        define("USUARIO", "lexsoftsandbox");
        define("PASSWORD", "a4p5*sU0");
        define("BD", "lg_travel_hotelbeds");

        // Hotelbeds
        define("HOTELBEDS_API_KEY", "ffd9b2973f2e6c952d72d622711c4e21");
        define("HOTELBEDS_SECRET", "1f6c28f34b");
        define("HOTELBEDS_API_ENDPOINT", "https://api.test.hotelbeds.com/");

        // OpenPay
        define("OPENPAY_ID", "mpcdcu1qo0kl1mwvdgsm");
        define("OPENPAY_PRIVATE_KEY", "sk_3392fca2f9ed416bb9f0daa53a7a5a44");
    } else if (MODO === "production") {
        // Base de datos
        define("HOST", "lexgotravel.com");
        define("USUARIO", "admin-travel");
        define("PASSWORD", "ytd9#89T");
        define("BD", "lg_travel_hotelbeds");

        // Hotelbeds
        define("HOTELBEDS_API_KEY", "acd1c6fb89bfa031d5aaec4f027d95ff");
        define("HOTELBEDS_SECRET", "415ba570b3");
        define("HOTELBEDS_API_ENDPOINT", "https://api.hotelbeds.com/");

        // OpenPay
        define("OPENPAY_ID", "muxjo69goowkivk7i1mz");
        define("OPENPAY_PRIVATE_KEY", "sk_7fd523802fa246f58173c0d4d9a51177");
    }
?>