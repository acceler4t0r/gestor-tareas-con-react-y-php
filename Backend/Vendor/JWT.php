<?php
    namespace Vendor;

    /*
    debido al desconocimiento de si usar composer o no, me di la tarea de investigar los JWT y como funcionan.

    webgrafia: 
    - https://www.youtube.com/watch?v=tWQobKFQLG0
    - https://www.freecodecamp.org/news/php-jwt-authentication-implementation/
    - https://github.com/Oghenekparobo/php_auth_jwt_tut

    el codigo fue tomado de git y se hicieron ajustes con gpt para simplificar a mi necesidad
    */

    class JWT{
        #esta funcion se encarga de crear un json web token con datos y una clave secreta
        public static function encode(array $payload, string $key, string $algo = 'HS256'): string
        {
            #informacion general sobre el token
            $header = ['typ' => 'JWT', 'alg' => $algo];

            #convertimos esa informacion a texto y luego a una forma segura para enviarla por internet
            $base64UrlHeader = self::base64UrlEncode(json_encode($header));

            #lo mismo con los datos que queremos guardar en el token (como el email, rol, etc.)
            $base64UrlPayload = self::base64UrlEncode(json_encode($payload));

            #creamos una firma, que es como una huella digital que confirma que el token es valido
            $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $key, true);

            #tambien convertimos esa firma a formato seguro
            $base64UrlSignature = self::base64UrlEncode($signature);

            #unimos todas las partes: encabezado, datos y firma  (esto es el token final)
            return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
        }

        #esta funcion revisa si un token que recibimos es valido
        public static function decode(string $jwt, string $key): array
        {
            #dividimos el token en sus 3 partes usando el punto como separador
            [$headerB64, $payloadB64, $signatureB64] = explode('.', $jwt);

            #valida que nadier haya modificado el token
            $signatureCheck = self::base64UrlEncode(
                hash_hmac('sha256', "$headerB64.$payloadB64", $key, true)
            );

            #comparamos la firma original con la nuestra (si sale mal es porque movieron el token)
            if (!hash_equals($signatureCheck, $signatureB64)) {
                throw new \Exception("Firma JWT inválida");
            }

            #convertimos los datos que venian en el token a un arreglo
            $payload = json_decode(self::base64UrlDecode($payloadB64), true);

            #valida que el token no haya caducado
            if (isset($payload['exp']) && time() >= $payload['exp']) {
                #ya caduco el token
                throw new \Exception("Token expirado");
            }

            #si todo esta bien, devolvemos los datos guardados en el token
            return $payload;
        }

        #esta funcion convierte texto en un formato seguro para ponerlo en el token (Base64 URL)
        private static function base64UrlEncode(string $data): string
        {
            #codifica a Base64, reemplaza caracteres no seguros y quita el "=" del final
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        }

        #esta funcion hace lo contrario: convierte de ese formato seguro a texto normal
        private static function base64UrlDecode(string $data): string
        {
            return base64_decode(strtr($data, '-_', '+/'));
        }
    }


?>